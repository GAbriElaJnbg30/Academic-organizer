#!/bin/bash

cd /home/site/wwwroot/public/

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

cd /home/site/wwwroot/public/
