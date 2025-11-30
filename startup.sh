#!/bin/bash

# Kopiowanie konfiguracji Nginx
cp /home/site/wwwroot/default /etc/nginx/sites-available/default

# Czekaj na utworzenie socketa PHP-FPM (max 30 sekund)
for i in {1..30}; do
    if [ -S /run/php/php-fpm.sock ]; then
        echo "PHP-FPM socket found"
        break
    fi
    echo "Waiting for PHP-FPM socket... ($i/30)"
    sleep 1
done

# Reload Nginx
service nginx reload
