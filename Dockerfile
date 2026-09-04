FROM php:8.2-apache

# Installer l'extension pdo_mysql pour Aiven
RUN docker-php-ext-install pdo pdo_mysql

# Activer la réécriture d'URL Apache
RUN a2enmod rewrite

# Copier le contenu du sous-dossier imbriqué Nurea/Nurea à la racine du serveur web
COPY ./Nurea/Nurea /var/www/html/

# Donner les permissions requises
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80