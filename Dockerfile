# ------------------------------------------------------
# Stage 1: Build Laravel app with Composer
# ------------------------------------------------------
FROM php:8.3.24-cli AS build

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

# Install Composer (specific version: 2.8.10)
RUN curl -sS https://getcomposer.org/download/2.8.10/composer.phar -o /usr/bin/composer \
    && chmod +x /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy rest of the Laravel project
COPY . .

# Cache Laravel config, routes, and views
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# ------------------------------------------------------
# Stage 2: Production container
# ------------------------------------------------------
FROM php:8.3.24-cli

# Install required system dependencies again in final image
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

# Copy Composer from build stage
COPY --from=build /usr/bin/composer /usr/bin/composer

# Copy Laravel project files from build stage
WORKDIR /app
COPY --from=build /app /app

# Expose Render’s PORT
EXPOSE 10000

# Run Laravel
CMD php artisan serve --host 0.0.0.0 --port $PORT
