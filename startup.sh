#!/bin/bash

# Kopiowanie konfiguracji Nginx
cp /home/site/wwwroot/default /etc/nginx/sites-available/default

# Restart Nginx
service nginx reload
