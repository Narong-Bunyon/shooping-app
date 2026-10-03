# ==========================================
# 1. Base PHP Image with System Dependencies
# ==========================================
FROM php:8.2-fpm

# Install system dependencies & Node.js (for Vite asset compilation)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Clear package cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions for Laravel & MySQL
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install latest Composer executable
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set container working directory
WORKDIR /var/www

# Copy existing application directory
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies and compile production assets with Vite
RUN npm install && npm run build

# Fix ownership & permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port 8000 for standalone PHP server mode
EXPOSE 8000

# Default command: Runs PHP Artisan Server (can be overridden by Docker Compose / Nginx)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
