# Skansaba.dev — Deploy di Ubuntu Server / Homelab (Docker)

Panduan lengkap menjalankan web ini di server dengan satu perintah build.

## Isi file

| File | Fungsi |
|---|---|
| `Dockerfile` | Build image produksi: PHP 8.3-FPM + Nginx + Supervisor + GD/Intl/OPcache, multi-stage (composer vendor + Vite assets sudah di-build di dalam image) |
| `compose.yaml` | Orkestrasi: service `app` + MySQL 8.4 dengan healthcheck |
| `docker/entrypoint.sh` | Bootstrap otomatis saat container start: generate `.env` + `APP_KEY`, tunggu MySQL, `migrate`, seed sekali, `storage:link`, cache config/route/view |
| `.dockerignore` | Mengecualikan node_modules, .git, env, dll dari build context |
| `.env.docker.example` | Template variabel untuk compose (port, password DB) |

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
1. `.env` Laravel dibuat (APP_KEY di-generate sekali)
2. Tunggu MySQL healthy → `php artisan migrate`
3. Seeder jalan **sekali** (ditandai file `storage/app/.seeded`) → isi beranda, karya, prestasi, berita, dll
4. `php artisan storage:link` + cache config/route/view
5. Supervisor menyalakan php-fpm + nginx + Laravel scheduler

### Login default (dari seeder)
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

## Catatan

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
