FROM php:8.3-apache

# Install extensions pour PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le code de l'application
WORKDIR /var/www/html
COPY . /var/www/html/

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Configuration des permissions
RUN chmod -R 755 /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Vérifier que les extensions PostgreSQL sont installées
RUN php -m | grep pdo_pgsql

# Port pour Render
EXPOSE 80

# Apache démarre automatiquement
