# Usamos una imagen base de PHP con Apache.
FROM php:apache

# Instalar la extensión mysqli para la conexión a la base de datos.
RUN docker-php-ext-install mysqli

# Copiar todos los archivos del proyecto al directorio web de Apache.
WORKDIR /var/www/html
COPY . .