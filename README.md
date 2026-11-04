# Skansaba.dev — Website Jurusan RPL

Website landing page + CMS admin & portofolio siswa untuk Jurusan Rekayasa Perangkat Lunak SMKN 1 Bantul. Dibangun dengan **Laravel 12**, **Livewire 4**, **Tailwind CSS 4**, dan **MySQL**.

> 📌 **Catatan:** Dokumen ini terus diperbarui seiring berkembangnya project. Kalau kamu baru melakukan `git pull`, langsung ikuti bagian **[🔄 Panduan Update Setelah `git pull`](#-panduan-update-setelah-git-pull-wajib-untuk-tim)** di bawah agar project langsung berjalan tanpa error.

---

## 🔄 Panduan Update Setelah `git pull` (Wajib untuk Tim)

Jika kamu sudah pernah meng-clone project ini sebelumnya dan baru saja menarik perubahan terbaru (`git pull origin main`), **jalankan perintah berikut secara berurutan**:

```bash
# 1. Tarik kode terbaru dari repository
git pull origin main

# 2. Install package PHP baru (termasuk enshrined/svg-sanitize untuk fitur SVG README)
composer install

# 3. Jalankan migrasi terbaru (cooldown username 7 hari, tabel aset SVG, & penghapusan kolom headline)
php artisan migrate

# (Opsional) Jika ingin mereset database + memuat ulang data seeder guru & karya terbaru:
# php artisan migrate:fresh --seed

# 4. Install dependensi Node & kompilasi ulang asset CSS/JS (Dark Mode + Studio Cropper)
npm install
npm run build

# 5. Bersihkan cache view, route, dan config Laravel
php artisan optimize:clear
```

> 💡 **Kenapa wajib menjalankan langkah di atas setelah `git pull`?**
> - Ada penambahan package `enshrined/svg-sanitize` di `composer.json` untuk sanitasi keamanan file `.svg` siswa.
> - Ada migrasi database baru (`username_changed_at`, tabel `profil_aset_svg`, dan penghapusan kolom `headline`).
> - Ada pembaruan style CSS di `resources/css/app.css` (integrasi **Dark Mode**, **Custom Scrollbar**, dan **Studio Image Cropper**).

---

## Fitur

### Halaman publik (tanpa login)

- **Mode Tema Terang / Gelap (Dark Mode)** — toggle tema di seluruh halaman publik maupun panel dashboard
- **Beranda** — landing page dengan slider, profil singkat, mitra, unit produksi, dan testimoni
- **Berita** — daftar + detail berita (URL berbasis `slug`)
- **Galeri** — daftar + detail galeri foto
- **Prestasi** — daftar + detail prestasi siswa
- **Alumni** — daftar + detail data alumni
- **Karya Siswa & Profil Siswa** — etalase portofolio karya siswa & direktori profil siswa RPL dengan tab filter, pencarian, dan lencana **Tech Stack**
- **Detail Karya Siswa** — halaman detail proyek lengkap dengan kartu **Profil Pembuat Karya / Siswa** beserta Tech Stack terverifikasi
- **Profil Publik Siswa (`/profil/{slug}`)** — halaman portofolio ala GitHub dengan README Markdown, aset gambar SVG, lencana Tech Stack terverifikasi, saluran kontak, dan daftar karya
- **Fasilitas** — daftar + detail fasilitas jurusan
- **Struktur Organisasi** — daftar + detail struktur organisasi RPL
- **Unit Produksi** — profil unit produksi / bengkel
- **Dokumen** — daftar dokumen kurikulum yang bisa diunduh

### Panel admin, guru & dashboard siswa

- Sistem **3 role**: `admin`, `guru`, dan `siswa` — dengan middleware `role:` untuk membatasi akses per modul
- **Dashboard** untuk semua role yang terautentikasi
- **Akun & Portofolio Siswa (`/siswa/*`)**:
  - **Dashboard & Profil Siswa**: ringkasan portofolio, status karya, dan pengelolaan lencana **Keahlian / Tech Stack**
  - **Hias Profil Publik**: indikator kelengkapan profil (0–100%), panduan interaktif (`<x-info-popover>`), live preview badge keahlian + pilihan cepat, **Username Kustom dengan Cooldown 7 Hari** + modal konfirmasi, **Editor README Markdown** dengan template instan (*Siswa Magang* & *Developer GitHub*), serta **Drag & Drop Upload Aset SVG** yang disanitasi otomatis
  - **Saluran Kontak**: pengelolaan tautan sosial media & kontak profesional (Instagram, GitHub, LinkedIn, WhatsApp, dll)
- **Studio Image Cropper (`<x-image-cropper>`)**:
  - Upload & crop gambar (Intervention Image + Croppie) ke format WebP
  - Pilihan bingkai avatar (Lingkaran & Squircle), slider geser horizontal (kanan-kiri), zoom, rotasi, **Edit Ulang Posisi** foto aktif tanpa upload ulang, serta live preview profil & navbar
- **Kurasi & Manajemen Karya Siswa**:
  - **Koleksi Karya Siswa (`/kelola/karya-siswa`)**: tampilan kartu portofolio visual dengan filter status cepat (*Terpublikasi*, *Butuh Review*, *Draft*), penjelasan status eksplisit, modal detail lengkap, dan konfirmasi hapus
  - **Verifikasi Karya (`/admin/verifikasi-karya`)**: kurasi pengajuan karya oleh Admin/Guru dengan modal preview in-page, badge notifikasi sidebar, dan pemberian tag keahlian terverifikasi otomatis
  - **Akun Siswa RPL (`/admin/siswa-rpl`)**: manajemen akun siswa khusus RPL, pembuatan akun siswa baru, dan pengelolaan multi-tag keahlian terverifikasi
- **Global settings** (nama situs, logo, warna tema, kontak) yang dipakai di seluruh halaman
- **Status konten & audit log** (`ContentStatusLog`, `LoginAudit`) untuk tracking perubahan konten

## Teknologi yang Dipakai

| Teknologi | Versi | Peran |
|---|---|---|
| [Laravel](https://laravel.com/) | 12.x | Framework utama |
| [Livewire](https://livewire.laravel.com/) | 4.x | Komponen reaktif full-stack |
| [Intervention Image](https://image.intervention.io/) | 3.x | Manipulasi & kompresi gambar WebP |
| [enshrined/svg-sanitize](https://github.com/darylldoyle/svg-sanitizer) | 0.22+ | Sanitasi keamanan file SVG README siswa |
| [Tailwind CSS](https://tailwindcss.com/) | 4.x | Styling & Dark Mode (via Vite) |
| [Alpine.js](https://alpinejs.dev/) | 3.x | Interaktivitas ringan di frontend |
| [Vite](https://vitejs.dev/) | 7.x | Build tool frontend |
| [Croppie](https://foliotek.github.io/Croppie/) | 2.6.5 | Studio crop gambar di sisi klien |
| [Pest](https://pestphp.com/) | 4.x | Testing framework |

## Prasyarat

Pastikan sudah terinstal di komputer:

| Tools | Versi Minimal | Keterangan |
|---|---|---|
| [PHP](https://www.php.net/downloads) | >= 8.2 | Ekstensi yang dibutuhkan: `pdo_mysql`, `mbstring`, `gd`, `zip`, `fileinfo`, `dom`, `xml` |
| [Composer](https://getcomposer.org/) | >= 2.x | Package manager PHP |
| [Node.js + npm](https://nodejs.org/) | >= 20 | Untuk compile asset frontend |
| [MySQL](https://www.mysql.com/) / MariaDB | 8.x / 10.4+ | Bisa pakai [Laragon](https://laragon.org/) atau [XAMPP](https://www.apachefriends.org/) |

> 💡 **Rekomendasi**: pakai **Laragon** karena sudah menyediakan PHP, MySQL, dan Composer dalam satu paket.

## Langkah Instalasi Awal (Fresh Clone)

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

Ini akan membuat semua tabel dan mengisi data awal (konten landing page, slider, berita, galeri, prestasi, struktur organisasi guru, alumni, karya siswa, dll) beserta gambar-gambarnya.

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
| Admin | `admin@rpl.sch.id` | `admin123` | Semua modul + manajemen user, verifikasi karya, akun siswa RPL & settings |
| Guru | `guru@rpl.sch.id` | `guru123` | Prestasi, galeri, struktur organisasi, verifikasi karya, akun siswa RPL |
| Siswa | `siswa@rpl.sch.id` | `skansabajaya123` | Karya siswa, alumni, profil siswa, hias profil publik & saluran kontak |

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
| `/prestasi/{id}` | Detail prestasi |
| `/alumni` | Data alumni |
| `/alumni/{id}` | Detail alumni |
| `/karya` | Etalase Karya Siswa & Direktori Profil Siswa |
| `/karya/{id}` | Detail karya siswa + kartu profil & tech stack pembuat |
| `/fasilitas` | Fasilitas jurusan |
| `/fasilitas/{id}` | Detail fasilitas |
| `/struktur-organisasi` | Struktur organisasi |
| `/struktur-organisasi/{id}` | Detail struktur organisasi |
| `/unit-produksi` | Unit produksi |
| `/dokumen` | Dokumen kurikulum |
| `/profil/{slug}` | Halaman profil publik portofolio siswa (berdasarkan `username` atau `id`) |
| `/login` | Login admin/guru/siswa |

### Admin / Guru / Siswa (perlu login)

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
| `/admin/verifikasi-karya` | admin, guru | Kurasi & verifikasi karya siswa + tag keahlian |
| `/admin/siswa-rpl` | admin, guru | Kelola akun siswa RPL & tag keahlian terverifikasi |
| `/kelola/karya-siswa` | admin, guru, siswa | Koleksi & manajemen karya siswa |
| `/admin/alumni` | admin, siswa | Kelola data alumni |
| `/siswa/profil` | siswa | Dashboard & profil siswa |
| `/siswa/profil-publik` | siswa | Hias profil publik, README Markdown, aset SVG & username |
| `/siswa/saluran` | siswa | Kelola saluran kontak & sosial media siswa |
| `/settings` | semua yang login | Pengaturan akun sendiri |

## Perintah yang Sering Dipakai

```bash
composer dev                         # jalankan server + queue + pail + vite sekaligus (development)
php artisan test                     # jalankan seluruh unit & feature test (Pest/PHPUnit)
composer lint                        # perbaiki style kode otomatis (Laravel Pint)
php artisan pail                     # log request secara live
php artisan migrate:fresh --seed     # reset database + isi ulang data awal
php artisan optimize:clear           # bersihkan cache view/route/config setelah pull
```

## Troubleshooting

### Error `Column not found: headline` atau `username_changed_at` setelah `git pull`

Jalankan migrasi terbaru karena ada perubahan skema tabel `users` dan penambahan tabel `profil_aset_svg`:

```bash
php artisan migrate
php artisan optimize:clear
```

### Error `Class "enshrined\svgSanitize\Sanitizer" not found`

Package SVG sanitizer belum ter-install di lokal. Jalankan:

```bash
composer install
```

Jika koneksi internet ke GitHub melambat saat mengunduh zip, gunakan opsi source:

```bash
composer install --prefer-source
```

### Tampilan Dark Mode atau Studio Cropper berantakan setelah `git pull`

Asset Vite belum di-build ulang. Jalankan:

```bash
npm install
npm run build
php artisan view:clear
```

### Gambar/foto tidak muncul (404)

Pastikan `php artisan storage:link` sudah dijalankan dan folder `public/storage` adalah symlink ke `storage/app/public`, bukan folder biasa. Cek dengan:

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

### Setelah composer update gagal di `post-update-cmd`

Script `php artisan boost:update` butuh koneksi database. Pastikan MySQL jalan sebelum `composer update`.

## Struktur Folder Penting

```
app/
├── Helpers/              # Helper functions (app/helpers.php, di-autoload)
├── Livewire/             # Semua komponen Livewire (halaman publik + manager admin)
│   ├── Admin/            # Modul khusus admin/guru (SiswaRplManager)
│   ├── Components/       # Komponen reusable (SidebarMenu, MarkdownEditor, Footer, dll)
│   ├── Siswa/            # Halaman khusus siswa (ProfilDashboard, ProfilPublikEdit, SaluranSaya)
│   └── Auth/             # Login
├── Models/               # Eloquent models (User, KaryaSiswa, ProfilAsetSvg, Berita, dll)
├── Policies/             # Authorization policies
└── Services/             # Layanan bisnis (ImageService, MarkdownService, SvgService, UsernameFilter)
database/
├── migrations/           # Skema tabel & riwayat perubahan database
└── seeders/              # Data awal untuk semua konten
resources/
├── css/                  # Konfigurasi Tailwind CSS 4, Dark Mode, Scrollbar, & Croppie Studio
└── views/                # Blade views & komponen UI
public/                   # Asset hasil build Vite + symlink storage
```

---

**Terakhir di-update:** 4 November 2026
**Maintainer:** [@RyaMight](https://github.com/RyaMight)
