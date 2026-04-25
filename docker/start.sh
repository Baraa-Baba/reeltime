#!/bin/bash

php-fpm &

# Run migrations safely (no data loss)
php artisan migrate --force
 

# Cache optimizations
php artisan config:cache
php artisan route:cache

nginx -g "daemon off;"