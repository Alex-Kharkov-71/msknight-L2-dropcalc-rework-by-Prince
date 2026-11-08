#!/bin/sh
# Starts php-fpm and nginx; if either dies the container stops (so Docker restarts it).
php-fpm83 -F -y /etc/php83/php-fpm.conf &
PHP=$!
nginx -g 'daemon off;' &
NGX=$!
trap 'kill $PHP $NGX 2>/dev/null; exit 0' TERM INT
while kill -0 "$PHP" 2>/dev/null && kill -0 "$NGX" 2>/dev/null; do sleep 3; done
kill "$PHP" "$NGX" 2>/dev/null
exit 1
