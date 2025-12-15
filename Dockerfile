FROM richarvey/nginx-php-fpm:3.1.6

# Copy your application code
COPY . .

# Image Configuration
ENV SKIP_COMPOSER 0
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# specific start command
CMD ["/start.sh"]