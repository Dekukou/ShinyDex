#!/bin/sh
set -e

echo "⏳ Waiting for database..."
until php bin/console doctrine:query:sql "SELECT 1" >/dev/null 2>&1; do
  sleep 1
done

echo "✅ Database is ready"

echo "🧹 Clearing cache"
php bin/console cache:clear

echo "📦 Running migrations"
php bin/console doctrine:migrations:migrate --no-interaction || true

echo "🚀 Starting PHP-FPM"
exec "$@"
