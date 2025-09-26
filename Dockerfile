# syntax=docker/dockerfile:1.6
FROM php:8.2-fpm-alpine AS base

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

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

RUN composer install --optimize-autoloader --no-dev
RUN npm ci && npm run build

COPY . .

# Permisos necesarios
RUN chmod -R 775 storage bootstrap/cache

# Copiar entrypoint con permisos
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
