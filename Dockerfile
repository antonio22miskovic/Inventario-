# Usar la imagen base de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl pdo_mysql zip mbstring exif bcmath

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos al contenedor
COPY . .

# Limpiar caché de Composer
RUN composer clear-cache

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --no-plugins

# Instalar dependencias de Node.js
RUN npm install && npm run build

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html

# Cachear la configuración de Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto
CMD ["apache2-foreground"]
