# Usamos una imagen base de PHP con Apache.
FROM php:apache

# 1. Instalar dependencias del sistema y extensiones de PHP
# - git y unzip son necesarios para Composer.
# - mysqli es para la conexión a la base de datos.
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && docker-php-ext-install mysqli

# 2. Instalar Composer (gestor de dependencias de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Establecer el directorio de trabajo
WORKDIR /var/www/html

# 4. Copiar los archivos del proyecto y las dependencias de Composer
COPY . .
RUN composer install --no-interaction --no-plugins --no-scripts

# 5. Copiar la configuración del Virtual Host de Apache para apuntar a /public
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# 6. Habilitar el módulo rewrite de Apache
RUN a2enmod rewrite