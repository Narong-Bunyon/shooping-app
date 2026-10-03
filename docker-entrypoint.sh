#!/bin/sh
set -e

# 1. Install Composer dependencies if vendor directory is missing or empty
if [ ! -f "/var/www/vendor/autoload.php" ]; then
    echo "===> Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 2. Build Vite assets if build directory is missing
if [ ! -f "/var/www/public/build/manifest.json" ]; then
    echo "===> Building Vite production assets..."
    npm install && npm run build
fi

# 3. Ensure permissions for storage and cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Execute default container command (e.g. php-fpm or php artisan serve)
exec "$@"
