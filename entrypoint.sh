#!/bin/sh
set -e

echo "🚀 Iniciando entrypoint..."

# Si no hay APP_KEY, generarlo (Render ya tiene env, pero sirve como fallback)
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY vacío, generando uno nuevo..."
    php artisan key:generate --force
else
    echo "✅ APP_KEY ya configurado."
fi

echo "🔄 Limpiando y cacheando configuración..."
php artisan config:clear || echo "⚠️ Falló config:clear"
php artisan config:cache || echo "⚠️ Falló config:cache"

echo "🔄 Cacheando rutas..."
php artisan route:cache || echo "⚠️ Falló route:cache"

echo "🔄 Cacheando vistas..."
php artisan view:cache || echo "⚠️ Falló view:cache"

echo "✅ Entrypoint finalizado."

exec "$@"
