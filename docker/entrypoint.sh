#!/bin/sh
set -e

cd /var/www/html

# ------------------------------------------------------------------
# 1. .env — jika sudah ada (dibawa dari repo/server), PAKEKAN apa adanya.
#    Hanya sinkronkan DB_HOST agar menunjuk ke MySQL host via container.
# ------------------------------------------------------------------
if [ ! -f .env ]; then
    echo "[entrypoint] .env tidak ditemukan, membuat dari contoh..."
    if [ -f .env.example ]; then
        cp .env.example .env
    else
        {
            echo "APP_NAME=\"Skansaba.dev\""
            echo "APP_ENV=production"
            echo "APP_KEY="
            echo "APP_DEBUG=false"
            echo "APP_URL=http://localhost"
            echo "LOG_CHANNEL=stderr"
            echo ""
            echo "DB_CONNECTION=mysql"
            echo "DB_HOST=host.docker.internal"
            echo "DB_PORT=3306"
            echo "DB_DATABASE=skansaba"
            echo "DB_USERNAME=skansaba"
            echo "DB_PASSWORD=secret"
            echo ""
            echo "SESSION_DRIVER=database"
            echo "CACHE_STORE=database"
            echo "QUEUE_CONNECTION=database"
            echo "FILESYSTEM_DISK=public"
        } > .env
    fi
    chown www-data:www-data .env
fi

# DB_HOST di dalam container HARUS host.docker.internal (MySQL ada di host).
# Nilai 127.0.0.1 di .env akan menunjuk ke container sendiri → error koneksi.
if grep -q "^DB_HOST=127.0.0.1" .env; then
    echo "[entrypoint] Menyesuaikan DB_HOST -> host.docker.internal"
    sed -i 's/^DB_HOST=127.0.0.1/DB_HOST=host.docker.internal/' .env
fi
if ! grep -q "^DB_HOST=" .env; then
    echo "DB_HOST=host.docker.internal" >> .env
fi

# ------------------------------------------------------------------
# 2. Direktori storage + cache bootstrap
# ------------------------------------------------------------------
mkdir -p storage/framework/{cache/data,sessions,views} storage/app/public bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# ------------------------------------------------------------------
# 3. APP_KEY — generate hanya jika belum ada
# ------------------------------------------------------------------
if ! grep -q "^APP_KEY=base64" .env; then
    echo "[entrypoint] Generate APP_KEY..."
    php artisan key:generate --force
fi

# ------------------------------------------------------------------
# 4. Tunggu MySQL host siap
# ------------------------------------------------------------------
echo "[entrypoint] Menunggu MySQL di host.docker.internal..."
until php -r '
    try {
        new PDO("mysql:host=" . getenv("DB_HOST") . ";port=" . (getenv("DB_PORT") ?: 3306),
            getenv("DB_USERNAME"), getenv("DB_PASSWORD"), [PDO::ATTR_TIMEOUT => 3]);
        exit(0);
    } catch (Exception $e) { exit(1); }
' 2>/dev/null; do
    sleep 2
done
echo "[entrypoint] MySQL siap."

echo "[entrypoint] Menjalankan migrate..."
php artisan migrate --force

# ------------------------------------------------------------------
# 5. Seed — hanya jika tabel users masih kosong (database baru).
#    Database yang sudah terisi (mis. skansabadev_landing_page) tidak
#    akan di-seed ulang / ditimpa.
# ------------------------------------------------------------------
SEEDED=$(php -r '
    try {
        $pdo = new PDO("mysql:host=" . getenv("DB_HOST") . ";port=" . (getenv("DB_PORT") ?: 3306) . ";dbname=" . getenv("DB_DATABASE"),
            getenv("DB_USERNAME"), getenv("DB_PASSWORD"));
        echo (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    } catch (Exception $e) { echo "-1"; }
' 2>/dev/null || echo "-1")

if [ "$SEEDED" = "0" ]; then
    echo "[entrypoint] Database kosong — menjalankan seeder (foto & data dari repo ikut terpasang)..."
    php artisan db:seed --force
elif [ "$SEEDED" = "-1" ]; then
    echo "[entrypoint] Tidak bisa membaca tabel users (migrasi mungkin belum penuh) — lewati seed."
else
    echo "[entrypoint] Database sudah terisi ($SEEDED users) — seed dilewati."
fi

# ------------------------------------------------------------------
# 6. Storage link + cache produksi
# ------------------------------------------------------------------
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache

echo "[entrypoint] Skansaba.dev siap di port 80."

exec "$@"
