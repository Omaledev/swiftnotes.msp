FROM richarvey/nginx-php-fpm:3.1.6

# 1. Copy application code
COPY . .

# 2. Image Configuration
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# --- FIX FOR PUSHER ERROR (Kept exactly as you had it) ---
ENV BROADCAST_DRIVER log
ENV BROADCAST_CONNECTION log
ENV PUSHER_APP_KEY void
ENV PUSHER_APP_ID void
ENV PUSHER_APP_SECRET void
ENV PUSHER_HOST void
ENV PUSHER_PORT 443
ENV PUSHER_SCHEME https
ENV PUSHER_APP_CLUSTER mt1
# ----------------------------

# 3. INSTALL NODE.JS & BUILD ASSETS (The New Part)
# This fixes the "Vite manifest not found" error
RUN apk add --no-cache nodejs npm
RUN npm install
RUN npm run build

# 4. Install PHP Dependencies (Kept exactly as you had it)
RUN composer install --no-dev --optimize-autoloader

# 5. Clear Caches & Start Server (Kept exactly as you had it)
CMD ["/bin/sh", "-c", "php artisan route:clear && php artisan config:clear && php artisan view:clear && /start.sh"]