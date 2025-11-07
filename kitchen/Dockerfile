# Usamos una imagen base de PHP con Apache.
FROM php:apache

# 1. Instalar dependencias del sistema y extensiones de PHP
# - git y unzip son necesarios para Composer.
# - libpng-dev es para la extensión GD.
# - mysqli y gd son las extensiones de PHP.
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    && docker-php-ext-install mysqli gd

# Copiar todos los archivos del proyecto al directorio web de Apache.
WORKDIR /var/www/html
COPY . .

# 2. Instalar Composer (gestor de dependencias de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Ejecutar composer install para instalar las dependencias del proyecto
RUN composer install --no-interaction --no-plugins --no-scripts