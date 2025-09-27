# syntax=docker/dockerfile:1.6

############################
# Etapa 1: Base PHP + Node
############################
FROM php:8.2-fpm-alpine AS base

# Dependencias del sistema necesarias
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

# Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

############################
# Etapa 2: Instalar dependencias
############################

# Copiamos solo los archivos de dependencias para usar caché de Docker
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Instalar dependencias PHP (sin ejecutar scripts todavía → artisan no existe aún)
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Instalar dependencias de Node
RUN npm ci

############################
# Etapa 3: Copiar proyecto y build
############################

# Ahora sí copiamos TODO el código fuente
COPY . .

# Compilar assets con Vite (ahora ya existen resources/, index.html, etc.)
RUN npm run build

############################
# Etapa 4: Configuración Laravel
############################

# Dar permisos a storage y bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Copiar el entrypoint que maneja APP_KEY y caches
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
