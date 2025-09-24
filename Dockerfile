# syntax=docker/dockerfile:1.6

############################
# Base PHP
############################
FROM php:8.2-fpm-alpine AS base

# Instalar dependencias de Laravel
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

############################
# Build assets con Node/Vite
############################
RUN npm install
RUN npm run build

############################
# Instalar dependencias PHP
############################
RUN composer install --optimize-autoloader --no-dev
RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

############################
# Servidor
############################
# Exponer el puerto que Render asignará
EXPOSE 10000

# Comando para producción
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=$PORT"]
