# Using PHP 8.2 FPM for a container
FROM php:8.2-fpm

# Install system dependencies required for PHP extensions and other operations
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libzip-dev \
    procps \
    && rm -rf /var/lib/apt/lists/* # Clean up

# Install PHP extensions that are necessary for typical Laravel applications
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip sockets

# Install Redis extension using PECL
RUN pecl install redis && docker-php-ext-enable redis

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory inside the container to /var/www
WORKDIR /var/www

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy the application code to /var/www
COPY . /var/www

# Use Composer to install dependencies adhering to the composer.lock file
RUN composer install --no-dev --optimize-autoloader

# Expose port 9000 to other Docker containers
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
