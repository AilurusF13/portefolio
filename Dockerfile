FROM php:8.2-apache

# Installation des dépendances SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Activation du module rewrite pour les routes propres
RUN a2enmod rewrite

# Copie du projet
COPY . /var/www/html/

# Correction des droits pour Apache (www-data)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html