FROM php:8.2-apache

# Installer pdo_mysql pour Aiven
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite
RUN a2enmod rewrite

# Configurer Apache pour écouter sur le port dynamically injecté par Render (ou 80 par défaut)
ENV PORT=80
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Activer le rapport d'erreurs PHP
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
 && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
 && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-errors.ini

# Copier le contenu du projet
COPY ./Nurea /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80