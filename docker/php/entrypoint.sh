#!/bin/sh
set -e


if [ "$#" -gt 0 ]; then
    exec "$@"
fi

if [ ! -f /var/www/html/public/uploads/users/default-profile.png ]; then
    if [ -f /var/www/html/public/uploads/users/default-profile.png.bak ]; then
        cp /var/www/html/public/uploads/users/default-profile.png.bak /var/www/html/public/uploads/users/default-profile.png
    else
        echo "INFO: default-profile.png.bak not found, skipping default photo copy"
    fi
fi

php-fpm &
exec nginx -g "daemon off;"
