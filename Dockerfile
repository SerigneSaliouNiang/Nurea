FROM php:8.2-apache

# Installer pdo_mysql
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite
RUN a2enmod rewrite

# Activer l'affichage des erreurs PHP pour le debug
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
 && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
 && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-errors.ini

# Copier le projet
COPY ./Nurea /var/www/html/

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80