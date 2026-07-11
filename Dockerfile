# ==========================================
# STAGE 1: Grab PHP Packages (For Ziggy Routes)
# ==========================================
FROM composer:latest AS vendor-resolver
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs

# ==========================================
# STAGE 2: Compile the Vue Frontend
# ==========================================
FROM node:20 AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
# Bring in the vendor folder from Stage 1 so Vite can find Ziggy!
COPY --from=vendor-resolver /app/vendor ./vendor
RUN npm run build

# ==========================================
# STAGE 3: Build the PHP Apache Production App
# ==========================================
FROM php:8.3-apache

# Install Linux utilities and database extensions for Postgres / SQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_pgsql pdo_mysql

# Turn on Apache's rewriting engine for clean Laravel URLs
RUN a2enmod rewrite

# Point Apache's main target directly into Laravel's public entrypoint
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Pull Composer directly into our container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy code over
WORKDIR /var/www/html
COPY . .

# Grab the compiled Vue components from Stage 2
COPY --from=frontend-builder /app/public/build ./public/build

# Run clean production Composer optimization 
RUN composer install --no-dev --optimize-autoloader

# Give Apache permissions to write to your logs and cache folders
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose public web port
EXPOSE 80

# Run pending database migrations automatically on boot, then kick off Apache
CMD ["sh", "-c", "php artisan storage:link --force && php artisan migrate --force && apache2-foreground"]