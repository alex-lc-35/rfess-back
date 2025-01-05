FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy all files before composer install
COPY . ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Warm up Symfony cache
# RUN php bin/console cache:warmup --env=prod

CMD ["php-fpm"]