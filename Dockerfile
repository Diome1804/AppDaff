FROM php:8.3-cli

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
WORKDIR /var/www
COPY . /var/www/

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Configuration des permissions
RUN chmod -R 755 /var/www

# Port pour Render
EXPOSE 8000

# Démarrer le serveur PHP intégré
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
