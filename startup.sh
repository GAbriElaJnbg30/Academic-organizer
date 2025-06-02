#!/bin/bash

cd /home/site/wwwroot/public/

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

chmod -R 775 storage
chmod -R 775 bootstrap/cache

cd /home/site/wwwroot/public/
