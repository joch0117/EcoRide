#!/bin/sh

# Donner les droits à www-data sur le cache Symfony
chown -R www-data:www-data /var/www/html/var
chmod -R 775 /var/www/html/var

# Lancer PHP-FPM normalement
exec php-fpm
