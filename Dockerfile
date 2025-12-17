# Use the official PHP image with Apache (handles .htaccess automatically)
FROM php:8.2-apache

# 1. Install system dependencies (Git, Zip, etc. needed for Composer)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm

# 2. Clear cache to keep image small
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. Enable Apache "mod_rewrite" (THIS FIXES YOUR 404 ISSUE)
RUN a2enmod rewrite

# 5. Configure Apache to serve from the "public" folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Set working directory
WORKDIR /var/www/html

# 7. Copy application code
COPY . .

# 8. Install PHP Dependencies
# (We install composer manually here since the official image doesn't have it)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 9. Build Frontend Assets
RUN npm install
RUN npm run build

# 10. Fix Permissions (Crucial for Render/Linux)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Environment Variables (Your custom ones)
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# 12. Start Apache
# We use a custom entrypoint to cache config before starting
CMD ["/bin/sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && apache2-foreground"]