# Use the official PHP image with Apache
FROM php:8.2-apache

# 1. Install system dependencies
# ADDED: libpq-dev (Required for PostgreSQL)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    nodejs \
    npm

# 2. Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install PHP extensions
# ADDED: pdo_pgsql (Required to talk to Render DB)
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# 4. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 5. Configure Apache to serve from "public"
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 6. Set working directory
WORKDIR /var/www/html

# 7. Copy application code
COPY . .

# --- FIX: Dummy variables for Build Process ---
ENV BROADCAST_DRIVER log
ENV PUSHER_APP_KEY build_key
ENV PUSHER_APP_ID build_id
ENV PUSHER_APP_SECRET build_secret
ENV PUSHER_HOST build_host
ENV PUSHER_PORT 443
ENV PUSHER_SCHEME https
ENV PUSHER_APP_CLUSTER mt1
# ----------------------------------------------

# 8. Install PHP Dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 9. Build Frontend Assets
RUN npm install
RUN npm run build

# 10. Fix Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Final Environment Setup
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# 12. Start Apache (With Database Migration)
CMD ["/bin/sh", "-c", "php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && apache2-foreground"]