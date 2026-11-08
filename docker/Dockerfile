# DropCalc: nginx + php-fpm in one small unprivileged container.
# Code and config.php are mounted from the host (see docker-compose.example.yml), nothing is baked in.
FROM alpine:3.20

RUN apk add --no-cache nginx php83 php83-fpm php83-mysqli php83-gd php83-simplexml php83-xml \
        php83-dom php83-mbstring php83-session php83-ctype php83-iconv php83-openssl \
 && adduser -D -H -u 10001 -s /sbin/nologin app \
 && rm -f /etc/nginx/http.d/*.conf /etc/php83/php-fpm.d/www.conf \
 && mkdir -p /var/www/dropcalc

COPY docker/nginx.conf       /etc/nginx/nginx.conf
COPY docker/php-fpm.conf     /etc/php83/php-fpm.conf
COPY docker/php-overrides.ini /etc/php83/conf.d/99-dropcalc.ini
COPY docker/entrypoint.sh    /entrypoint.sh
RUN chmod 755 /entrypoint.sh

USER 10001:10001
WORKDIR /var/www/dropcalc
EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
