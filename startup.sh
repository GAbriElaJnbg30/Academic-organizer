#!/bin/bash

cd /home/site/wwwroot
composer install --no-dev --optimize-autoloader

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

cd /home/site/wwwroot/public
php -S 0.0.0.0:8080
