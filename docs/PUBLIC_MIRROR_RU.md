# Публичное зеркало DropCalc (копия баз с домашнего сервера)

Схема: домашний сервер раз в час сам заливает копии баз (логин + игровые) во внешнюю MariaDB через туннель.
Внешний сервер к домашнему не обращается. DropCalc на внешнем сервере (контейнер) работает с копиями.

## 1. Туннель
Любой: WireGuard (рекомендую) или постоянный SSH-туннель. Ниже адрес внешнего сервера в туннеле — `10.8.0.2`, домашнего — `10.8.0.1`.

## 2. Внешняя MariaDB
В `my.cnf`: `bind-address = 10.8.0.2` (3306 наружу не открывать, firewall тоже). Имена публичных баз начинаем с `pub_`:
```sql
CREATE USER 'sync'@'10.8.0.1' IDENTIFIED BY '<пароль>';
GRANT ALL ON `pub\_%`.* TO 'sync'@'10.8.0.1';        -- создаёт/подменяет только базы pub_*
CREATE USER 'dropcalc'@'%' IDENTIFIED BY '<пароль2>'; -- для контейнера DropCalc
GRANT SELECT ON `pub\_%`.* TO 'dropcalc'@'%';
GRANT ALL ON `pub\_%`.knightdrop TO 'dropcalc'@'%';   -- см. п. 5: нужны права записи на свои таблицы
```
(права для `dropcalc` уточняются после установки таблиц, см. п. 5).

## 3. Домашний сервер
1. `scripts/sync.conf.example` → `/etc/dropcalc-sync.conf` (chmod 600), заполнить.
2. Файлы `LOCAL_CNF` и `REMOTE_CNF` вида `[client]\nuser=...\npassword=...` (chmod 600).
3. Секрет: `openssl rand -hex 32` → `PEPPER` в конфиге.
4. Проверка вручную: `scripts/sync-to-public.sh`, затем cron: `0 * * * * /path/scripts/sync-to-public.sh >> /var/log/dropcalc-sync.log 2>&1`.

Что делает скрипт: заливает каждую базу во временную `pub_*__new`, у аккаунтов подменяет пароль на `base64(sha256(PEPPER . base64(sha1(пароль))))`, обнуляет почту и IP, затем **одним `RENAME TABLE`** подменяет таблицы (сайт не видит неполных данных) и удаляет временное. Таблицы `knightdrop`, `knightipok`, `knightsettings`, `knightimportstat` не копируются — у публичного сайта они свои.

## 4. Контейнер с DropCalc
`config.php` публичного сайта: базы `pub_*`, адрес БД — внутренний; плюс
```php
$password_pepper = "<тот же секрет, что PEPPER>";
$install_admins = array();     // на публичном сайте установщика нет
```
Рекомендации: порт MariaDB наружу не публиковать, php-fpm не от root, файловая система контейнера read-only (кроме tmp).

## 5. Первый запуск на публичном сайте
1. Выполнить синхронизацию.
2. Установить недостающие таблицы DropCalc (`knightdrop`, `knightsettings` и т. п.) — на локальном сайте нельзя, это делается на публичном: временно добавить свой логин в `$install_admins`, открыть «Install tables» для каждой базы, затем **убрать** логин из `$install_admins`.
   Либо выполнить `sql/install_login_db.sql` и `sql/install_game_db.sql` вручную (таблицы создаются только если их нет).
3. Настройки публичного сайта (в игровых базах): `UPDATE knightsettings SET allowpassreset = 0;` и пороги доступа как на локальном.

## 6. nginx: закрыть опасные страницы публичного сайта
```nginx
location ~ ^/(install|import[a-z]*|servertools|settings2?|dbutils|delete|cpasswd|acc-change|acc-notes|bana|export|check)\.php$ { return 404; }
```
Копии только для чтения: любые изменения (смена пароля, бан, заметки) на публичном сайте будут затёрты следующей синхронизацией, поэтому эти страницы закрыты.

## Что считать риском
Хеши паролей на внешнем сервере защищены секретом: без `PEPPER` (он лежит в конфиге контейнера и в `/etc/dropcalc-sync.conf` дома) дамп базы подбирать нельзя. Если секрет и дамп утекут вместе — защита пропадает. Почта и IP игроков в копии обнулены.

## 7. Контейнер (nginx + php-fpm), файлы в `docker/`
Образ собирается из `docker/Dockerfile` (Alpine, nginx и php-fpm в одном контейнере, работает от непривилегированного пользователя, корневая ФС только для чтения, без capabilities). Код и `config.php` монтируются с хоста, поэтому обновление кода — это `git pull`, пересборка не нужна.

1. Скопируйте `docker/docker-compose.example.yml`, поправьте пути (чекаут репозитория и файл `config.public.php`).
2. Публичный `config.public.php` — это копия `config.php.example` с вашими данными: БД `l2login`, `l2s1`, `l2s2`, `l2s3`, адрес MariaDB `172.28.0.1` (шлюз сети контейнера), пользователь `dropcalc`, `$password_pepper`, `$install_admins = array();`.
3. Права в MariaDB привяжите к фиксированному адресу контейнера: `'dropcalc'@'172.28.0.10'`. `bind-address` MariaDB должен включать `172.28.0.1` (проще `0.0.0.0` плюс firewall, закрывающий 3306 снаружи).
4. Запуск: `docker compose up -d --build`.

### nginx на хосте (TLS) → контейнер
```nginx
location / {
    proxy_pass http://127.0.0.1:8081;
    proxy_set_header Host $host;
    proxy_set_header X-Forwarded-For $remote_addr;   # DropCalc привязывает токен к IP клиента
    proxy_set_header X-Forwarded-Proto $scheme;
}
```

### Изоляция сети контейнера (если взломают DropCalc)
Контейнеру нужен только MariaDB. Запретите ему всё остальное, особенно туннель и домашнюю сеть:
```bash
iptables -I DOCKER-USER -s 172.28.0.0/24 -j DROP
iptables -I DOCKER-USER -s 172.28.0.0/24 -d 172.28.0.1 -p tcp --dport 3306 -j ACCEPT
iptables -I DOCKER-USER -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
```
(порядок вставки `-I` обратный: последняя команда оказывается первым правилом). Правила `INPUT` хоста для трафика из `172.28.0.0/24` тоже ограничьте портом 3306.

Файлы контейнера проверены только по синтаксису (nginx, php-fpm, YAML); сборка образа на живом Docker не проверялась.
