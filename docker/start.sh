#!/bin/bash

php-fpm &

# Run migrations safely (no data loss)
php artisan migrate --force

# Seed ONLY if needed (careful!)
php artisan db:seed --force
php8.4 artisan db:seed --class=UserSeeder

# Cache optimizations
php artisan config:cache
php artisan route:cache

nginx -g "daemon off;"