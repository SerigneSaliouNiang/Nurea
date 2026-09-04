FROM php:8.2-apache

# Installer l'extension pdo_mysql pour la BDD
RUN docker-php-ext-install pdo pdo_mysql

# Activer le module de réécriture Apache
RUN a2enmod rewrite

# Copier le code dans le dossier web
COPY . /var/www/html/

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80