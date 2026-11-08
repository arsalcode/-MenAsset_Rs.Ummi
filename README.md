# SIM Aset & Peralatan Medis - RS Ummi Bogor

Sistem informasi manajemen inventaris dan siklus hidup alat medis untuk unit IPSRS (Instalasi Pemeliharaan Sarana Rumah Sakit) dan SIMRS RS Ummi Bogor. Aplikasi dirancang menggunakan pendekatan *reactive monolith* berbasis Laravel 13 dan Livewire 4, memungkinkan interaksi data real-time pada dasbor operasional tanpa overhead manajemen state API eksternal.

---

## Spesifikasi Lingkungan (Environment Requirements)

Pastikan lingkungan kerja memenuhi kebutuhan berikut sebelum instalasi:

* **PHP**: `>= 8.2` (ekstensi aktif: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
* **Database**: MySQL `>= 5.7` / MariaDB `>= 10.3`
* **Dependency Manager**: Composer `>= 2.2`
* **Runtime Frontend**: Node.js `>= 18.x` & NPM
* **Local Web Stack**: Laragon / XAMPP / Docker / Native CLI

---

## Panduan Instalasi Lokal (Quick Setup)

Jalankan rangkaian perintah berikut secara berurutan pada terminal:

### 1. Clone Repositori
```bash
git clone https://github.com/arsalcode/-MenAsset_Rs.Ummi.git
cd -MenAsset_Rs.Ummi
```

### 2. Konfigurasi Environment
Salin template konfigurasi environment:
```bash
cp .env.example .env
```

Pastikan variabel koneksi database pada `.env` telah disesuaikan dengan server MySQL lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=menasset_rs_ummi
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan Database**: Buat skema database terlebih dahulu via MySQL client / phpMyAdmin / Heidisql jika belum ada:
> ```sql
> CREATE DATABASE menasset_rs_ummi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> ```

### 3. Install Dependensi & Generate Application Key
```bash
composer install
php artisan key:generate
```

### 4. Eksekusi Migrasi & Data Seeder
Jalankan migrasi skema tabel relasional beserta seeder inventaris awal:
```bash
php artisan migrate --seed
```

### 5. Kompilasi Aset Frontend
Install pustaka frontend dan bangun bundle produksi (Tailwind CSS v4 + Vite):
```bash
npm install
npm run build
```

### 6. Menjalankan Server Lokal
```bash
php artisan serve
```

Aplikasi aktif dan dapat diakses pada browser: `http://127.0.0.1:8000`

---

## Titik Akses & Modul Sistem

Sistem memisahkan alur kerja administratif dan inspeksi teknis lapangan:

| Modul | Endpoint | Deskripsi Fungsi |
| :--- | :--- | :--- |
| **Portal Admin SIMRS / IPSRS** | `/` | Dasbor utama manajemen aset: operasi CRUD, live search debounce, multi-filter klasifikasi, rekap metrik finansial/kondisi, dan histori log pemeliharaan. |
| **Portal Lapangan & Teknisi** | `/lapangan` | Interface responsif untuk staf ruangan: verifikasi nomor stiker aset / scanner QR, ceklis inventaris per ruangan, serta pengajuan tiket kendala alat medis. |

### Kredensial Default (Seeded Account)
* **Email**: `admin@rsummi.com`
* **Password**: `password123`

---

## Skema Database & Relasi (ERD Overview)

Sistem menggunakan 4 tabel inti yang dinormalisasi dengan integritas *foreign key constraint*:

```
[categories] ──(1:N)──< [assets] >──(N:1)── [locations]
                           │
                         (1:N)
                           │
                           v
                  [maintenance_logs]
```

* **`categories`**: Klasifikasi aset (`MED` untuk Alat Medis, `LAB` untuk Laboratorium, `RAD` untuk Radiologi, dll).
* **`locations`**: Metadata ruangan, penomoran lantai/gedung, dan nama penanggung jawab (PIC).
* **`assets`**: Master data aset, nomor seri, tanggal & nilai perolehan, status operasional, serta kondisi fisik.
* **`maintenance_logs`**: Riwayat uji fungsi, kalibrasi berkala BPFK, dan perbaikan teknisi (dikonfigurasi *cascade on delete* terhadap aset terkait).

---

## Pengujian Otomatis (Automated Testing)

Verifikasi fungsionalitas mencakup unit test dan feature test untuk menjamin keandalan logika bisnis (CRUD aset, filtering, pagination, transaksi mutasi data, dan responsivitas komponen Livewire):

```bash
php artisan test
```

**Ringkasan Hasil Pengujian:**
* **Total Suite**: 16 Test Cases
* **Assertions**: 56 Assertions
* **Status**: 100% Passed
* **Mekanisme**: Menggunakan trait `DatabaseTransactions` untuk memastikan integritas data testing tanpa merusak state database seeder.

---

## Catatan Arsitektur & Rekayasa Kode

1. **State Management Tanpa Page Reload**: Menggunakan Laravel Livewire 4 untuk mengisolasi mutasi komponen dasbor secara asinkronus via AJAX transport bawaan, menghilangkan kebutuhan state store frontend terpisah (Redux/Pinia).
2. **Optimasi Query Pencarian**: Fitur live search mengimplementasikan query scoping dinamis dengan sanitasi input untuk mencegah degradasi performa pada tabel berukuran besar.
3. **Responsivitas Multi-Perangkat**: Seluruh antarmuka dioptimalkan menggunakan utilitas Tailwind CSS v4 untuk mendukung transisi tampilan dari layar monitor workstation administrasi hingga smartphone teknisi lapangan.
