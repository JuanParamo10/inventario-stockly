FROM php:8.2-apache

# Instalar el driver de PostgreSQL para PHP
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar los archivos de tu proyecto al servidor
COPY . /var/www/html/

# Dar permisos a la carpeta
RUN chown -R www-data:www-data /var/www/html/

# Exponer el puerto 80
EXPOSE 80