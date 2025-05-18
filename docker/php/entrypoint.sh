#!/bin/sh
set -e

# Si on nous passe une commande, on l’exécute directement
if [ "$#" -gt 0 ]; then
    exec "$@"
fi

# Sinon on lance le stack classique PHP-FPM + Nginx
php-fpm &
exec nginx -g "daemon off;"
