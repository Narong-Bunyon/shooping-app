#!/bin/sh

# 1. Install Composer dependencies if vendor directory is missing
if [ ! -f "/var/www/vendor/autoload.php" ]; then
    echo "===> Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader || true
fi

# 2. Build Vite assets if build directory is missing
if [ ! -f "/var/www/public/build/manifest.json" ]; then
    echo "===> Building Vite production assets..."
    (npm install && npm run build) || true
fi

# 3. Set storage & bootstrap permissions safely
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# 4. ALWAYS execute the main command (php-fpm)
exec "$@"
