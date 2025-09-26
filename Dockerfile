# syntax=docker/dockerfile:1.6

############################
# Base PHP + Node
############################
FROM php:8.2-fpm-alpine AS base

# Instalar dependencias del sistema
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

WORKDIR /var/www/html

############################
# Copiar archivos del proyecto
# (excluye vendor, node_modules y .env con .dockerignore)
############################
COPY . .

############################
# Dependencias PHP
############################
RUN composer install --optimize-autoloader --no-dev

############################
# Dependencias Node y Build
############################
RUN npm ci && npm run build

############################
# Configuración Laravel
############################
RUN php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

############################
# Permisos
############################
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
