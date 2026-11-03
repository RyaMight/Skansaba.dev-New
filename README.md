# Skansaba.dev — Website Jurusan RPL

Website landing page + CMS admin untuk Jurusan Rekayasa Perangkat Lunak. Dibangun dengan **Laravel 12**, **Livewire 4**, dan **MySQL**.

## Fitur

- Landing page publik: beranda, berita, galeri, prestasi, alumni, karya siswa, fasilitas, struktur organisasi
- Panel admin (`/admin`) untuk mengelola seluruh konten
- Global settings (nama situs, logo, warna tema) yang dipakai di seluruh halaman
- Upload & crop gambar bawaan

## Prasyarat

Pastikan sudah terinstal di komputer:

| Tools | Versi Minimal | Keterangan |
|---|---|---|
| [PHP](https://www.php.net/downloads) | >= 8.2 | Ekstensi yang dibutuhkan: `pdo_mysql`, `mbstring`, `gd`, `zip`, `fileinfo` |
| [Composer](https://getcomposer.org/) | >= 2.x | Package manager PHP |
| [Node.js + npm](https://nodejs.org/) | >= 20 | Untuk compile asset frontend |
| [MySQL](https://www.mysql.com/) / MariaDB | 8.x / 10.4+ | Bisa pakai [Laragon](https://laragon.org/) atau [XAMPP](https://www.apachefriends.org/) |

> 💡 **Rekomendasi**: pakai **Laragon** karena sudah menyediakan PHP, MySQL, dan Composer dalam satu paket.

## Langkah Instalasi

### 1. Clone repository

```bash
git clone https://github.com/RyaMight/Skansaba.dev-New.git
cd Skansaba.dev-New
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Setup file environment

Salin file contoh environment:

```bash
copy .env.example .env     # Windows
# cp .env.example .env     # Linux/Mac
```

Lalu sesuaikan konfigurasi database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skansabadev_landing_page
DB_USERNAME=root
DB_PASSWORD=
```

> Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan setup MySQL kamu.

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Buat database

Buat database dengan nama `skansabadev_landing_page` (atau sesuaikan `DB_DATABASE` di `.env`).

Lewat phpMyAdmin, atau lewat command line:

```sql
CREATE DATABASE skansabadev_landing_page CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Jalankan migration + seeder

```bash
php artisan migrate --seed
```

Ini akan membuat semua tabel dan mengisi data awal (konten landing page, slider, berita, dll) beserta gambar-gambarnya.

### 7. Buat storage link

Penting! Supaya gambar yang di-upload bisa diakses:

```bash
php artisan storage:link
```

> ⚠️ Di Windows, jalankan terminal sebagai **Administrator** kalau perintah ini gagal (Windows butuh privilege untuk membuat symlink). Alternatif: Laravel otomatis memakai junction.

### 8. Install & build asset frontend

```bash
npm install
```

Untuk development (asset dengan hot reload):

```bash
npm run dev
```

Atau untuk production (build sekali saja):

```bash
npm run build
```

### 9. Jalankan server

```bash
php artisan serve
```

Buka browser ke **http://127.0.0.1:8000** 🎉

> Kalau pakai `npm run dev`, jalankan di terminal terpisah dan biarkan jalan bersamaan dengan `php artisan serve`.

## Akun Login Default

Login ke panel admin di **http://127.0.0.1:8000/login**:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@rpl.sch.id` | `admin123` |
| Guru | `guru@rpl.sch.id` | `guru123` |

> ⚠️ Segera ganti password default setelah login di environment produksi!

## Struktur Halaman

| URL | Keterangan |
|---|---|
| `/` | Beranda (landing page) |
| `/berita` | Daftar berita |
| `/gallery` | Galeri foto |
| `/prestasi` | Prestasi siswa |
| `/alumni` | Data alumni |
| `/karya` | Karya siswa |
| `/fasilitas` | Fasilitas jurusan |
| `/struktur-organisasi` | Struktur organisasi |
| `/login` | Login admin/guru |
| `/admin` | Dashboard admin (perlu login) |

## Troubleshooting

### Gambar/foto tidak muncul (404)

Pastikan `php artisan storage:link` sudah dijalankan dan folder `public/storage` adalah symlink ke `storage/app/public`, bukan file biasa. Cek dengan:

```bash
php artisan storage:link
```

Kalau muncul error "link already exists" tapi gambar tetap tidak muncul, hapus `public/storage` lalu jalankan ulang perintahnya.

### `SQLSTATE[HY000] [2002] No connection could be made`

MySQL belum jalan. Nyalakan MySQL dulu:
- **Laragon** → Start All
- **XAMPP** → Start MySQL
- Pastikan port di `.env` sesuai (default `3306`)

### `no such table: ...`

Migration belum dijalankan:

```bash
php artisan migrate --seed
```

### `No application encryption key has been specified`

Application key belum di-generate:

```bash
php artisan key:generate
```

### Error `SET FOREIGN_KEY_CHECKS` saat seeding

Seeder lama memakai sintaks MySQL murni. Sudah diperbaiki di `DatabaseSeeder.php` dengan `\Schema::disableForeignKeyConstraints()` yang kompatibel untuk MySQL maupun SQLite.

### Setelah composer update gagal di `post-update-cmd`

Script `php artisan boost:update` butuh koneksi database. Pastikan MySQL jalan sebelum `composer update`.

## Teknologi yang Dipakai

- [Laravel 12](https://laravel.com/) — Framework PHP
- [Livewire 4](https://livewire.laravel.com/) — Full-stack framework untuk Laravel
- [Intervention Image](https://image.intervention.io/) — Manipulasi & crop gambar
- [Tailwind CSS](https://tailwindcss.com/) — Styling (via Vite)
- [Vite](https://vitejs.dev/) — Build tool frontend
- [Pest](https://pestphp.com/) — Testing framework
