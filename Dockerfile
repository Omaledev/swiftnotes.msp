FROM richarvey/nginx-php-fpm:3.1.6

# 1. Copy application code
COPY . .

# 2. Image Configuration
ENV SKIP_COMPOSER 0
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# --- FIX FOR PUSHER ERROR ---
# We set these to "log" or "void" so the build doesn't crash looking for real keys
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

# 3. FORCE Composer to install dependencies NOW
RUN composer install --no-dev --optimize-autoloader

# Clear caches before starting the server
CMD ["/bin/sh", "-c", "php artisan route:clear && php artisan config:clear && php artisan view:clear && /start.sh"]