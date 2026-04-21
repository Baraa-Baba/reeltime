#!/bin/bash
php-fpm &
php artisan migrate:fresh --seed --force 
php artisan config:cache
php artisan route:cache
nginx -g "daemon off;"