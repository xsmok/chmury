#!/bin/bash

# Kopiowanie konfiguracji Nginx
cp /home/site/wwwroot/default /etc/nginx/sites-available/default

# Uruchomienie PHP-FPM
service php8.4-fpm start || service php8.3-fpm start || service php8.2-fpm start || service php-fpm start

# Restart Nginx
service nginx reload
