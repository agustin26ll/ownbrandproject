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
# Build assets con Node/Vite
############################
# Limpiar node_modules y reinstalar para evitar conflictos
RUN npm ci

# Dar permisos de ejecución a Vite
RUN chmod +x node_modules/.bin/vite

# Ejecutar build de Vite
RUN npm run build

############################
# Instalar dependencias PHP
############################
RUN composer install --optimize-autoloader --no-dev

# Configuración de Laravel
RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

############################
# Servidor
############################
# Render asignará automáticamente un puerto con $PORT
EXPOSE $PORT

# Comando para producción
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=$PORT"]
