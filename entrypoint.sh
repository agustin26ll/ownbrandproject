#!/bin/sh
set -e

echo ">>> Entrypoint iniciado"

# Verificar APP_KEY
if [ -z "$APP_KEY" ]; then
    echo ">>> APP_KEY no está definido. Generando nueva key..."
    php artisan key:generate --force || { echo "!!! Error al generar APP_KEY"; exit 1; }
else
    echo ">>> APP_KEY ya definido en el entorno"
fi

# Cache de configuración
echo ">>> Cacheando configuración..."
php artisan config:cache || { echo "!!! Error al cachear configuración"; exit 1; }

echo ">>> Cacheando rutas..."
php artisan route:cache || { echo "!!! Error al cachear rutas"; exit 1; }

echo ">>> Cacheando vistas..."
php artisan view:cache || { echo "!!! Error al cachear vistas"; exit 1; }

echo ">>> Entrypoint finalizado correctamente"

exec "$@"
