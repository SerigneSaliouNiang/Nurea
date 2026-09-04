FROM php:8.2-apache

# Installer l'extension pdo_mysql pour Aiven
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite
RUN a2enmod rewrite

# Configuration du port Render
ENV PORT=80
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Rediriger toutes les erreurs PHP vers stderr/stdout pour les voir sur Render Logs
RUN echo "log_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
 && echo "error_log = /dev/stderr" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
 && echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-errors.ini \
 && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-errors.ini

# Copier le code du projet
COPY ./Nurea /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80