# syntax=docker/dockerfile:1.6

FROM php:8.2-fpm-alpine AS base

# Instalar dependencias
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

# Copiar archivos (EXCLUYENDO node_modules)
COPY . .
COPY .env.production .env

# IMPORTANTE: Configurar APP_URL antes del build
RUN sed -i "s|APP_URL=.*|APP_URL=https://ownbrandproject.onrender.com|g" .env
RUN sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|g" .env
RUN sed -i "s|APP_ENV=.*|APP_ENV=production|g" .env

# Build de assets
RUN npm ci
RUN npm run build

# Verificar que los archivos se crearon
RUN ls -la public/build/assets/

# Dependencias PHP
RUN composer install --optimize-autoloader --no-dev

# Configuración Laravel
RUN php artisan key:generate --force
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Permisos
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000"]