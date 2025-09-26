#!/bin/sh
set -e

echo "=== Entrypoint OwnBrand iniciado ==="

if [ ! -f storage/laravel.key ]; then
    echo "[INFO] Generando APP_KEY..."
    php artisan key:generate --force || { echo "[ERROR] Fallo generando APP_KEY"; exit 1; }
    touch storage/laravel.key
else
    echo "[INFO] APP_KEY ya existe, se omite key:generate."
fi

echo "[INFO] Ejecutando package:discover..."
php artisan package:discover --ansi || { echo "[ERROR] Fallo en package:discover"; exit 1; }

echo "[INFO] Cacheando configuración..."
php artisan config:cache || { echo "[ERROR] Fallo en config:cache"; exit 1; }

echo "[INFO] Cacheando rutas..."
php artisan route:cache || { echo "[ERROR] Fallo en route:cache"; exit 1; }

echo "[INFO] Cacheando vistas..."
php artisan view:cache || { echo "[ERROR] Fallo en view:cache"; exit 1; }

echo "=== Entrypoint finalizado correctamente ==="

exec "$@"
