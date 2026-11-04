# Skansaba.dev — Website Jurusan RPL

Website landing page + CMS admin untuk Jurusan Rekayasa Perangkat Lunak. Dibangun dengan **Laravel 12**, **Livewire 4**, dan **MySQL**.

> 📌 **Catatan:** Dokumen ini akan terus di-updated seiring berkembangnya project. Kalau kamu menambah fitur / halaman baru, jangan lupa perbarui bagian [Fitur](#fitur), [Struktur Halaman](#struktur-halaman), dan [Fitur Panel Admin](#fitur-panel-admin).

## Fitur

### Halaman publik (tanpa login)

- **Beranda** — landing page dengan slider, profil singkat, mitra, unit produksi, dan testimoni
- **Berita** — daftar + detail berita (URL berbasis `slug`)
- **Galeri** — daftar + detail galeri foto
- **Prestasi** — daftar + detail prestasi siswa
- **Alumni** — daftar + detail data alumni
- **Karya siswa** — daftar + detail karya siswa
- **Fasilitas** — daftar + detail fasilitas jurusan
- **Struktur organisasi** — daftar + detail struktur organisasi RPL
- **Unit produksi** — profil unit produksi / bengkel
- **Dokumen** — daftar dokumen kurikulum yang bisa diunduh
- **Profil** — halaman profil dinamis berdasarkan `slug` (`/profil/{slug}`)

### Panel admin & dashboard

- Sistem **3 role**: `admin`, `guru`, dan `siswa` — dengan middleware `role:` untuk membatasi akses per modul
- **Dashboard** untuk semua role yang terautentikasi
- **Akun siswa**: profil dashboard, edit profil publik, dan halaman saluran (`/siswa/*`)
- **Global settings** (nama situs, logo, warna tema, kontak) yang dipakai di seluruh halaman
- **Upload & crop gambar** bawaan (Intervention Image + Croppie), termasuk kompresi dan transparansi
- **Editor Markdown** untuk konten berita/artikel
- **Status konten & audit log** (`ContentStatusLog`, `LoginAudit`) untuk tracking perubahan konten
- **Manajemen user** (admin-only) dan **login audit**

## Teknologi yang Dipakai

| Teknologi | Versi | Peran |
|---|---|---|
| [Laravel](https://laravel.com/) | 12.x | Framework utama |
| [Livewire](https://livewire.laravel.com/) | 4.x | Komponen reaktif full-stack |
| [Intervention Image](https://image.intervention.io/) | 3.x | Manipulasi & crop gambar |
| [Tailwind CSS](https://tailwindcss.com/) | 4.x | Styling (via Vite) |
| [Alpine.js](https://alpinejs.dev/) | 3.x | Interaktivitas ringan di frontend |
| [Vite](https://vitejs.dev/) | 7.x | Build tool frontend |
| [Croppie](https://foliotek.github.io/Croppie/) | — | Crop gambar di sisi klien |
| [Pest](https://pestphp.com/) | 4.x | Testing framework |

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

Ini akan membuat semua tabel dan mengisi data awal (konten landing page, slider, berita, galeri, prestasi, alumni, karya siswa, dll) beserta gambar-gambarnya.

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

<details>
<summary>⚡ Alternatif: satu perintah untuk semua (mode dev Laravel starter kit)</summary>

```bash
composer dev
```

Perintah ini menjalankan `php artisan serve`, queue listener, `php artisan pail`, dan `npm run dev` sekaligus dalam satu terminal.

</details>

## Akun Login Default

Login di **http://127.0.0.1:8000/login**:

| Role | Email | Password | Akses |
|---|---|---|---|
| Admin | `admin@rpl.sch.id` | `admin123` | Semua modul + manajemen user & settings |
| Guru | `guru@rpl.sch.id` | `guru123` | Prestasi, galeri, struktur organisasi |
| Siswa | `siswa@rpl.sch.id` | `skansabajaya123` | Karya siswa, alumni, profil & saluran siswa |

> ⚠️ Segera ganti password default setelah login di environment produksi!

## Struktur Halaman

### Publik

| URL | Keterangan |
|---|---|
| `/` | Beranda (landing page) |
| `/berita` | Daftar berita |
| `/berita/{slug}` | Detail berita |
| `/gallery` | Galeri foto |
| `/gallery/{id}` | Detail galeri |
| `/prestasi` | Prestasi siswa |
| `/alumni` | Data alumni |
| `/karya` | Karya siswa |
| `/fasilitas` | Fasilitas jurusan |
| `/struktur-organisasi` | Struktur organisasi |
| `/unit-produksi` | Unit produksi |
| `/dokumen` | Dokumen kurikulum |
| `/profil/{slug}` | Halaman profil dinamis |
| `/login` | Login admin/guru/siswa |

### Admin / Dashboard (perlu login)

| URL | Role yang bisa akses | Keterangan |
|---|---|---|
| `/dashboard` | admin, guru, siswa | Dashboard utama |
| `/admin/landing-page-slider` | admin | Kelola slider beranda |
| `/admin/berita` | admin | Kelola berita |
| `/admin/fasilitas` | admin | Kelola fasilitas |
| `/admin/dokumen-kurikulum` | admin | Kelola dokumen kurikulum |
| `/admin/prospek-karir` | admin | Kelola prospek karir & mitra industri |
| `/admin/mitra` | admin | Kelola mitra |
| `/admin/global-settings` | admin | Pengaturan global situs |
| `/admin/users` | admin | Manajemen user |
| `/admin/struktur-organisasi-rpl` | admin, guru | Kelola struktur organisasi |
| `/admin/prestasi` | admin, guru | Kelola prestasi |
| `/admin/galeri` | admin, guru | Kelola galeri |
| `/admin/karya-siswa` | admin, guru, siswa | Kelola karya siswa |
| `/admin/alumni` | admin, siswa | Kelola data alumni |
| `/siswa/profil` | siswa | Profil dashboard siswa |
| `/siswa/profil-publik` | siswa | Edit profil publik siswa |
| `/siswa/saluran` | siswa | Saluran siswa |
| `/settings` | semua yang login | Pengaturan akun sendiri |

## Perintah yang Sering Dipakai

```bash
composer dev         # jalankan server + queue + pail + vite sekaligus (development)
composer test        # jalankan lint (Pint) + test (Pest)
composer lint        # perbaiki style kode otomatis (Laravel Pint)
php artisan pail     # log request secara live
php artisan migrate:refresh --seed   # reset database + isi ulang data awal
```

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

## Struktur Folder Penting

```
app/
├── Helpers/              # Helper functions (app/helpers.php, di-autoload)
├── Livewire/             # Semua komponen Livewire (halaman publik + manager admin)
│   ├── Components/       # Komponen reusable (SidebarMenu, MarkdownEditor, Footer, dll)
│   ├── Siswa/            # Halaman khusus siswa (ProfilDashboard, SaluranSaya, dll)
│   └── Auth/             # Login
├── Models/               # Eloquent models (Berita, Galeri, Prestasi, Alumni, dll)
└── Policies/             # Authorization policies
database/seeders/         # Data awal untuk semua konten
resources/views/          # Blade views untuk tiap komponen Livewire
public/                   # Asset hasil build Vite + symlink storage
```

---

**Terakhir di-update:** 4 November 2026
**Maintainer:** [@RyaMight](https://github.com/RyaMight)
