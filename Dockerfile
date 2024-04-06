FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libzip-dev \
    # Add any other dependencies your extensions might need
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip sockets
RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Avoid Composer root warning
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy application files to the container
COPY . /var/www

RUN composer install --optimize-autoloader --no-dev

EXPOSE 9000
CMD ["php-fpm"]
