FROM php:8.2-apache

# Installer l'extension pdo_mysql
RUN docker-php-ext-install pdo pdo_mysql

# Activer le mod_rewrite d'Apache
RUN a2enmod rewrite

# Copier le contenu du sous-dossier Nurea directement dans la racine d'Apache
COPY ./Nurea /var/www/html/

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80