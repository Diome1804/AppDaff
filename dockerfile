FROM php:8.3-cli

# Install extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpq5 \
    postgresql-client \
    unzip \
    curl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && php -m | grep -i pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances du projet
WORKDIR /var/www
COPY . /var/www
RUN composer install --no-dev --optimize-autoloader

# Droits
RUN chown -R www-data:www-data /var/www

# Variable d'environnement par défaut (sera écrasée par Render)
ENV DATABASE_URL=postgresql://postgres_v119_user:BV6rAIpMA3rA7Z8qUb60oJiNvgmLbjqr@dpg-dlv8fnqdb04c73fa50bg-a.oregon-postgres.render.com/postgres_v119

EXPOSE 8000

# Démarrer le serveur intégré PHP pour Render
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
