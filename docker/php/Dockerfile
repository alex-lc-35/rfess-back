# Etape 1 : build avec Composer
FROM php:8.2-fpm AS builder

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copier uniquement les fichiers nécessaires à Composer pour utiliser le cache Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Etape 2 : Image finale allégée
FROM php:8.2-fpm

# Installer les mêmes extensions PHP pour l'image finale
RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copier le projet complet depuis l'image "builder"
COPY --chown=www-data:www-data . ./
COPY --from=builder /app/vendor /var/www/html/vendor

# Préparer le cache Symfony
RUN php bin/console cache:warmup --env=prod || true

CMD ["php-fpm"]
