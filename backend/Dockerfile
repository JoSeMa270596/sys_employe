# Usamos una imagen base con PHP y Apache
FROM php:8.2-apache

# Instalación de dependencias del sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim unzip git curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Instalación de Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Configura Apache
RUN a2enmod rewrite
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www

# Copiar los archivos del proyecto (excepto lo definido en .dockerignore)
COPY . .

# Configuración inicial de Laravel
RUN set -x \
    && composer install --no-interaction --optimize-autoloader \
    && cp .env.example .env \
    && php artisan key:generate \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www \
    && php artisan migrate

# Puerto expuesto
EXPOSE 80

CMD ["apache2-foreground"]
