#!/bin/sh
set -e


if [ "$#" -gt 0 ]; then
    exec "$@"
fi

if [ ! -f /var/www/html/public/uploads/users/default-profile.png ]; then
    cp /var/www/html/public/uploads/users/default-profile.png.bak /var/www/html/public/uploads/users/default-profile.png
fi

php-fpm &
exec nginx -g "daemon off;"
