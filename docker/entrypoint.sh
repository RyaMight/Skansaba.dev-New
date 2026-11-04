#!/bin/sh
set -e

cd /var/www/html

# ------------------------------------------------------------------
# Baca variabel dari file .env (bukan getenv — env var container tidak
# selalu ter-set, sedangkan .env Laravel selalu ada di sini via mount)
# ------------------------------------------------------------------
env_value() {
    grep -E "^$1=" .env 2>/dev/null | head -n1 | cut -d'=' -f2- | tr -d '"' | tr -d "'"
}

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
# Catatan: .env di-mount dari host, jadi TIDAK boleh di-modify via sed -i
# (bind mount = file "resource busy" saat di-replace). Edit manual di host.
DB_HOST_VAL="$(env_value DB_HOST)"
if [ "$DB_HOST_VAL" = "127.0.0.1" ] || [ -z "$DB_HOST_VAL" ]; then
    echo "[entrypoint] PERINGATAN: DB_HOST di .env adalah '$DB_HOST_VAL'."
    echo "[entrypoint] Di dalam container, MySQL host harus diakses via host.docker.internal."
    echo "[entrypoint] Fix permanen di host:  sed -i 's/^DB_HOST=127.0.0.1/DB_HOST=host.docker.internal/' .env"
    echo "[entrypoint] Untuk sesi ini, koneksi dipaksa via host.docker.internal."
    DB_HOST_VAL="host.docker.internal"
fi

# Baca konfigurasi DB lainnya dari file .env (dengan fallback env var container)
DB_PORT_VAL="${DB_PORT:-$(env_value DB_PORT)}"
DB_PORT_VAL="${DB_PORT_VAL:-3306}"
DB_DATABASE_VAL="${DB_DATABASE:-$(env_value DB_DATABASE)}"
DB_USERNAME_VAL="${DB_USERNAME:-$(env_value DB_USERNAME)}"
DB_PASSWORD_VAL="${DB_PASSWORD:-$(env_value DB_PASSWORD)}"

echo "[entrypoint] DB target: ${DB_USERNAME_VAL}@${DB_HOST_VAL}:${DB_PORT_VAL}/${DB_DATABASE_VAL}"

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
# 4. Tunggu MySQL host siap (test via PDO, nilai dari .env)
# ------------------------------------------------------------------
echo "[entrypoint] Menunggu MySQL di ${DB_HOST_VAL}:${DB_PORT_VAL}..."
until php -r '
    [$h, $p, $u, $pw] = [$argv[1], $argv[2], $argv[3], $argv[4]];
    try {
        new PDO("mysql:host={$h};port={$p}", $u, $pw, [PDO::ATTR_TIMEOUT => 3]);
        exit(0);
    } catch (Exception $e) { exit(1); }
' "$DB_HOST_VAL" "$DB_PORT_VAL" "$DB_USERNAME_VAL" "$DB_PASSWORD_VAL" 2>/dev/null; do
    echo "[entrypoint]   MySQL belum siap, coba lagi dalam 3 detik..."
    sleep 3
done
echo "[entrypoint] MySQL siap."

echo "[entrypoint] Menjalankan migrate..."
php artisan migrate --force

# ------------------------------------------------------------------
# 5. Seed — hanya jika tabel users masih kosong (database baru).
#    Database yang sudah terisi tidak akan di-seed ulang / ditimpa.
# ------------------------------------------------------------------
SEEDED=$(php -r '
    [$h, $p, $u, $pw, $db] = [$argv[1], $argv[2], $argv[3], $argv[4], $argv[5]];
    try {
        $pdo = new PDO("mysql:host={$h};port={$p};dbname={$db}", $u, $pw);
        echo (int) $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    } catch (Exception $e) { echo "-1"; }
' "$DB_HOST_VAL" "$DB_PORT_VAL" "$DB_USERNAME_VAL" "$DB_PASSWORD_VAL" "$DB_DATABASE_VAL" 2>/dev/null || echo "-1")

if [ "$SEEDED" = "0" ]; then
    echo "[entrypoint] Database kosong — menjalankan seeder (data & foto dari repo ikut terpasang)..."
    php artisan db:seed --force
elif [ "$SEEDED" = "-1" ]; then
    echo "[entrypoint] Tidak bisa membaca tabel users — lewati seed."
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
