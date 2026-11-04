#!/bin/sh
set -e

cd /var/www/html

# ------------------------------------------------------------------
# 1. .env produksi — generate sekali, dipertahankan di volume antar restart
# ------------------------------------------------------------------
if [ ! -f .env ]; then
    echo "[entrypoint] Membuat .env produksi..."
    {
        echo "APP_NAME=\"Skansaba.dev\""
        echo "APP_ENV=production"
        echo "APP_KEY="
        echo "APP_DEBUG=false"
        echo "APP_URL=http://localhost"
        echo "LOG_CHANNEL=stderr"
        echo "LOG_LEVEL=info"
        echo ""
        echo "DB_CONNECTION=mysql"
        echo "DB_HOST=db"
        echo "DB_PORT=3306"
        echo "DB_DATABASE=${DB_DATABASE:-skansaba}"
        echo "DB_USERNAME=${DB_USERNAME:-skansaba}"
        echo "DB_PASSWORD=${DB_PASSWORD:-secret}"
        echo ""
        echo "SESSION_DRIVER=database"
        echo "SESSION_LIFETIME=120"
        echo "CACHE_STORE=database"
        echo "QUEUE_CONNECTION=database"
        echo ""
        echo "FILESYSTEM_DISK=public"
    } > .env
    chown www-data:www-data .env
fi

# ------------------------------------------------------------------
# 2. Direktori storage + cache bootstrap
# ------------------------------------------------------------------
mkdir -p storage/framework/{cache/data,sessions,views} storage/app/public bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# ------------------------------------------------------------------
# 3. APP_KEY — generate sekali, simpan di .env
# ------------------------------------------------------------------
if ! grep -q "^APP_KEY=base64" .env; then
    echo "[entrypoint] Generate APP_KEY..."
    php artisan key:generate --force
fi

# ------------------------------------------------------------------
# 4. Tunggu MySQL siap, lalu migrate + seed
# ------------------------------------------------------------------
echo "[entrypoint] Menunggu MySQL..."
until mysql -h "${DB_HOST:-db}" -u "${DB_USERNAME:-skansaba}" -p"${DB_PASSWORD:-secret}" "${DB_DATABASE:-skansaba}" -e "SELECT 1" >/dev/null 2>&1; do
    sleep 2
done
echo "[entrypoint] MySQL siap."

echo "[entrypoint] Menjalankan migrate..."
php artisan migrate --force

# Seed hanya sekali: tandai dengan file penanda di storage
if [ ! -f storage/app/.seeded ]; then
    echo "[entrypoint] Menjalankan seeder (pertama kali)..."
    php artisan db:seed --force
    touch storage/app/.seeded
    chown www-data:www-data storage/app/.seeded
fi

# ------------------------------------------------------------------
# 5. Storage link + cache produksi
# ------------------------------------------------------------------
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache

echo "[entrypoint] Skansaba.dev siap di port 80."

exec "$@"
