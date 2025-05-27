#!/bin/sh
set -e

# 1. Création automatique des dossiers nécessaires
mkdir -p /var/www/html/public/uploads/users
chown -R www-data:www-data /var/www/html/public/uploads
chmod -R 775 /var/www/html/public/uploads


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
