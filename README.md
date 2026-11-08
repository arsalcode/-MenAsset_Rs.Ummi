# 📋 Panduan Cara Menjalankan Aplikasi
## Sistem Manajemen Aset & Peralatan Medis (RS Ummi Bogor)

Dokumen ini berisi panduan lengkap langkah demi langkah untuk menginstalasi dan menjalankan aplikasi **Sistem Manajemen Aset & Peralatan Medis RS Ummi Bogor** di lingkungan lokal (Localhost).

---

## 💻 1. Kebutuhan Sistem (Prerequisites)

Sebelum menjalankan aplikasi, pastikan perangkat Anda telah terpasang:
* **PHP**: Versi 8.2 ke atas (Direkomendasikan PHP 8.3 / 8.4 / 8.5)
* **Web Server & Database**: MySQL Server (menggunakan **Laragon** atau **XAMPP**)
* **Composer**: Versi 2.x
* **Node.js**: Versi 18+ & **NPM**
* **Git**

---

## 🚀 2. Langkah-Langkah Menjalankan Aplikasi

Ikuti urutan perintah di bawah ini melalui Terminal / Command Prompt:

### Langkah 1: Clone Repositori
```bash
git clone https://github.com/arsalcode/-MenAsset_Rs.Ummi.git
cd -MenAsset_Rs.Ummi
```

### Langkah 2: Salin File Environment
Salin template konfigurasi `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*(Di Windows PowerShell atau CMD jika perintah `cp` tidak tersedia: `copy .env.example .env`)*

### Langkah 3: Konfigurasi Database MySQL
1. Buka MySQL di Laragon / XAMPP (pastikan service MySQL sudah aktif / **Start**).
2. Buat database baru dengan nama:
   ```sql
   CREATE DATABASE menasset_rs_ummi;
   ```
3. Konfigurasi pada file `.env` sudah disesuaikan secara default:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=menasset_rs_ummi
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` jika MySQL Anda menggunakan kata sandi khusus).*

### Langkah 4: Install Dependency PHP & Generate App Key
```bash
composer install
php artisan key:generate
```

### Langkah 5: Jalankan Migrasi & Seeder Database
Perintah ini akan membuat struktur tabel beserta data awal realistis peralatan medis RS Ummi:
```bash
php artisan migrate --seed
```

### Langkah 6: Install Dependency Frontend & Compile Aset
```bash
npm install
npm run build
```

### Langkah 7: Jalankan Web Server Laravel
```bash
php artisan serve
```

Server development sekarang sudah berjalan! Silakan buka browser Anda di:
👉 **`http://localhost:8000`**

---

## 🌐 3. URL Akses & Halaman Aplikasi

Aplikasi memiliki dua portal utama yang saling terintegrasi:

| Portal | URL Akses | Deskripsi |
| :--- | :--- | :--- |
| **Portal Admin SIMRS / IPSRS** | `http://localhost:8000/` | Kelola seluruh aset medis, CRUD lengkap, multi-filter real-time, ringkasan statistik, dan histori servis/kalibrasi. |
| **Portal Petugas Lapangan** | `http://localhost:8000/lapangan` | Portal mobile-friendly untuk teknisi/perawat lapangan: pemindai stiker QR, ceklis per ruangan, dan tiket pelaporan kerusakan. |

> **Akun Default Demo (Seeder):**  
> * **Email**: `admin@rsummi.com`  
> * **Password**: `password123`

---

## 🧪 4. Menjalankan Pengujian Otomatis (Automated Tests)

Aplikasi telah dilengkapi unit test dan feature test (16 test suites, 56 assertions - 100% Pass) untuk menguji seluruh fungsi CRUD, filter, dan komponen Livewire:

```bash
php artisan test
```

---

## ⌨️ 5. Perintah Tambahan yang Berguna

| Perintah | Fungsi |
| :--- | :--- |
| `php artisan serve` | Menjalankan server aplikasi |
| `php artisan migrate:fresh --seed` | Mengosongkan dan mengisi ulang database demo |
| `npm run dev` | Menjalankan Vite development server (hot-reload) |
| `npm run build` | Melakukan build produksi Tailwind CSS |

---

## 🛠️ Ringkasan Tech Stack
* **Framework Backend**: Laravel 13 (PHP 8.5)
* **Frontend Interactivity**: Laravel Livewire 4 (Reaktif tanpa reload halaman)
* **Styling**: Tailwind CSS v4 + Lucide Icons
* **Database**: MySQL (Integritas Relasi FK & Cascade Rules)
* **Pengujian**: PHPUnit 12 (16 Tests, 56 Assertions)
