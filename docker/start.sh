#!/bin/bash
php-fpm &
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
nginx -g "daemon off;"