# syntax=docker/dockerfile:1.6

############################
# Base PHP + Node
############################
FROM php:8.2-fpm-alpine AS base

# Instalar dependencias de Laravel y Node
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
# Build assets con Node/Vite - CORREGIDO
############################
# Instalar dependencias de Node
RUN npm ci

# Configurar Vite para producción ANTES del build
RUN sed -i "s|APP_URL=http://localhost:8000|APP_URL=https://ownbrandproject.onrender.com|g" .env

# Build de Vite para producción
RUN npm run build

############################
# Instalar dependencias PHP
############################
RUN composer install --optimize-autoloader --no-dev

# Configuración de Laravel para producción
RUN php artisan key:generate --force
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Permisos para storage y bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

############################
# Servidor
############################
EXPOSE 8000

# Comando para producción - CORREGIDO
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000"]