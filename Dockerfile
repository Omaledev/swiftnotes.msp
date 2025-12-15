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

# 3. FORCE Composer to install dependencies NOW (during build)
# This prevents the "missing autoload.php" error
RUN composer install --no-dev --optimize-autoloader

# 4. Start the server
CMD ["/start.sh"]