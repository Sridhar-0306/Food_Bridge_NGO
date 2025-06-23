FROM php:8.1-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Show PHP errors (optional, for debug)
RUN echo "display_errors = On\nerror_reporting = E_ALL" > /usr/local/etc/php/conf.d/errors.ini

# ✅ Copy backend first
COPY backend/ /var/www/html/backend/

# ✅ Then copy frontend
COPY public/ /var/www/html/
