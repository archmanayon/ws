# ------------------------------------------------------
# Stage 1: Build Laravel app with Composer
# ------------------------------------------------------
FROM php:8.3.24-cli AS build

# Install system dependencies + PHP extensions Laravel needs
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev libzip-dev zip libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath gd mbstring exif

# Install Composer (specific version: 2.8.10)
RUN curl -sS https://getcomposer.org/download/2.8.10/composer.phar -o /usr/bin/composer \
    && chmod +x /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json ./
COPY composer.lock* ./

# Allow unlimited memory for Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy Laravel project
COPY . .

# Cache Laravel config, routes, and views
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# ------------------------------------------------------
# Stage 2: Production container
# ------------------------------------------------------
FROM php:8.3.24-cli

# Install required system dependencies again
RUN apt-get update && apt-get install -y \
    unzip git curl libpq-dev libzip-dev zip libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath gd mbstring exif

# Copy Composer from build stage
COPY --from=build /usr/bin/composer /usr/bin/composer

# Copy built Laravel app
WORKDIR /app
COPY --from=build /app /app

# Expose Render’s dynamic port
EXPOSE 10000

# Run Laravel
CMD php artisan serve --host 0.0.0.0 --port $PORT
