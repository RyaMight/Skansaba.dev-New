# Skansaba.dev — Deploy di Ubuntu Server / Homelab (Docker)

Panduan lengkap menjalankan web ini di server dengan satu perintah build.

## Isi file

| File | Fungsi |
|---|---|
| `Dockerfile` | Build image produksi: PHP 8.3-FPM + Nginx + Supervisor + GD/Intl/OPcache, multi-stage (composer vendor + Vite assets sudah di-build di dalam image) |
| `compose.yaml` | Service `app` saja — MySQL pakai yang sudah ter-install di host (via `host.docker.internal`) |
| `docker/entrypoint.sh` | Bootstrap saat container start: pakai `.env` host (mount), sinkronkan DB_HOST, tunggu MySQL, `migrate`, **seed hanya jika DB kosong**, `storage:link`, cache |
| `.dockerignore` | Mengecualikan node_modules, .git, env, dll dari build context |
| `.env.docker.example` | Template variabel untuk compose (port) |

## Cara deploy (dari nol)

```bash
# 1. Install docker (kalau belum)
curl -fsSL https://get.docker.com | sh

# 2. Clone repo & masuk folder
git clone https://github.com/RyaMight/Skansaba.dev-New.git skansaba
cd skansaba

# 3. Siapkan env compose
cp .env.docker.example .env
nano .env    # ganti APP_PORT & password DB

# 4. Build & jalan
docker compose up -d --build
```

Selesai — web jalan di `http://IP-SERVER:8000` (atau port yang kamu set).

### Yang otomatis terjadi saat pertama kali start
1. `.env` Laravel host di-mount ke container dan dipakai (DB_HOST 127.0.0.1 → disesuaikan otomatis ke `host.docker.internal`)
2. `APP_KEY` sudah ada dari `.env` — tidak digenerate ulang
3. Tunggu MySQL host siap → `php artisan migrate`
4. **Seed hanya jika database kosong** (tabel `users` 0 baris) → data + foto dari seeder teman-teman terpasang
   - Database yang sudah terisi (`skansabadev_landing_page`) **tidak disentuh**
5. `php artisan storage:link` + cache config/route/view
6. Supervisor menyalakan php-fpm + nginx + Laravel scheduler

### Login default (dari seeder, hanya jika DB kosong saat pertama start)
- Admin: `admin@rpl.sch.id` / `admin123`
- Guru: `guru@rpl.sch.id` / `guru123`
- Siswa: NISN `0061234567` / `skansabajaya123`

> Ganti password setelah login pertama!

## Perintah harian

```bash
docker compose logs -f app        # lihat log aplikasi
docker compose restart app        # restart aplikasi
docker compose down               # matikan (data DB & storage tetap aman di volume)
docker compose up -d --build      # update kode + rebuild

# Masuk ke shell container
docker compose exec app sh

# Artisan di dalam container
docker compose exec app php artisan <command>
```

## Data yang persisten

- **MySQL** → volume `skansaba-db-data`
- **File upload user** (SVG README, foto profil, dll) → volume `skansaba-storage`
  (`storage/app/public` di container, tetap aman walau container/rebuild)

## Update aplikasi

```bash
git pull
docker compose up -d --build
```

Migrasi baru otomatis dijalankan oleh entrypoint saat container start.

## Sinkronisasi .env

`compose.yaml` me-mount `./.env` (host) ke `/var/www/html/.env` (container) — file Laravel .env asli server-mu **langsung terpakai**, tidak digenerate/ditimpa.

Syarat isinya (sudah disesuaikan entrypoint otomatis):
- `DB_HOST=127.0.0.1` akan otomatis diubah ke `host.docker.internal` saat container start
- Lainnya (`DB_DATABASE=skansabadev_landing_page`, username, password) tetap

## MySQL host: izinkan koneksi dari container

```sql
-- sudo mysql -u root -p
CREATE USER IF NOT EXISTS 'skansaba_user'@'%' IDENTIFIED BY '1324';
GRANT ALL PRIVILEGES ON skansabadev_landing_page.* TO 'skansaba_user'@'%';
FLUSH PRIVILEGES;
```

Dan di `/etc/mysql/mysql.conf.d/mysqld.cnf`: `bind-address = 0.0.0.0`, lalu `sudo systemctl restart mysql`.

- **Seed hanya sekali** — restart/rebuild tidak akan menghapus atau meng-seed ulang data.
  Kalau benar-benar mau reset + seed ulang:
  ```bash
  docker compose exec app php artisan migrate:fresh --seed --force
  ```
- **Reverse proxy**: kalau di-depan ada Nginx/Caddy/Traefik dengan domain + HTTPS,
  cukup arahkan ke `APP_PORT`. Set `APP_URL` di `.env` Laravel di container
  (`docker compose exec app nano .env` lalu `docker compose exec app php artisan config:cache`)
  agar URL gambar benar.
- **Backup DB**:
  ```bash
  docker compose exec db mysqldump -u root -p"$DB_ROOT_PASSWORD" skansaba > backup.sql
  ```
