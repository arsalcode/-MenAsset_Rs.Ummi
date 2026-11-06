# Sistem Manajemen Aset Rumah Sakit (RS Ummi Bogor)

Sistem Informasi Manajemen Inventaris & Aset Rumah Sakit berbasis web yang dibangun untuk memantau, mendata, dan mengelola peralatan medis, fasilitas ruangan, serta histori kalibrasi dan pemeliharaan alat kesehatan secara terpadu.

Aplikasi ini dikembangkan sebagai pemenuhan **Tugas Seleksi Magang Posisi IT Fullstack Web Developer**.

---

## 🛠️ Tech Stack

* **Backend Framework**: [Laravel 13](https://laravel.com) (PHP 8.5)
* **Frontend Interactivity**: [Laravel Livewire 4](https://livewire.laravel.com) (Komponen Reaktif Real-time tanpa page reload)
* **CSS Framework**: [Tailwind CSS v4](https://tailwindcss.com) + Vite
* **Database**: MySQL 5.7+ / 8.0 (Relasi Foreign Key & Cascade Rules)
* **Testing Framework**: PHPUnit & Laravel Feature Test (8 test suites, 25 assertions)

---

## ✨ Fitur Aplikasi

### A. Fitur Wajib (MVP)
1. **Dasbor Aset Interaktif**: Tabel daftar aset rumah sakit dilengkapi pagination dinamis berbasis Livewire (tanpa reload halaman).
2. **CRUD Lengkap (Create, Read, Update, Delete)**:
   * **Create**: Pendaftaran aset baru dengan validasi data ketat & penomoran kode otomatis.
   * **Read**: Modal detail kelengkapan aset, spesifikasi teknis, lokasi gedung/lantai, PIC penanggung jawab ruangan, serta histori servis.
   * **Update**: Pembaruan spesifikasi teknis, harga perolehan, kondisi fisik, dan status operasional.
   * **Delete**: Penghapusan data dengan modal konfirmasi keamanan.
3. **Database Relasional**: Struktur 4 tabel yang terhubung integritas foreign key (`categories`, `locations`, `assets`, `maintenance_logs`).

### B. Fitur Eksplorasi & Inovasi
1. **Live Search & Multi-Filter Real-time**: Pencarian instan (kode aset, nama alat, merk, nomor seri) dan filter dropdown simultan berdasarkan *Kategori*, *Ruangan*, dan *Kondisi Alat*.
2. **Metric Summary Cards**: 4 widget ringkasan statistik di dasbor utama:
   * *Total Inventaris Unit*
   * *Alat Siap Operasional (Kondisi Baik)*
   * *Alat Perlu Kalibrasi / Rusak*
   * *Estimasi Nilai Kapitalisasi Aset (Format Rupiah IDR)*
3. **Histori Kalibrasi & Pemeliharaan Alat Medis (`maintenance_logs`)**: Pencatatan riwayat uji fungsi, kalibrasi berkala BPFK, dan servis teknisi untuk kepatuhan standar keselamatan medis rumah sakit.
4. **Desain UI/UX Modern & Responsif**: Mengadopsi standar desain enterprise (sidebar elegan, indikator status sistem *live*, badge semantik, tipografi *Plus Jakarta Sans* & *JetBrains Mono*).

---

## 🗄️ Skema Database & Relasi (ERD)

```
┌─────────────────────────┐            ┌─────────────────────────┐
│       categories        │            │        locations        │
├─────────────────────────┤            ├─────────────────────────┤
│ id (PK)                 │            │ id (PK)                 │
│ name (Alat Medis, IT..) │            │ name (IGD, ICU, OK..)   │
│ code (MED, LAB, IT..)   │            │ building & floor        │
│ description             │            │ pic_name (Penanggung Jwb)│
└────────────┬────────────┘            └────────────┬────────────┘
             │ 1                                    │ 1
             │                                      │
             │ N                                    │ N
┌────────────┴──────────────────────────────────────┴────────────┐
│                             assets                             │
├────────────────────────────────────────────────────────────────┤
│ id (PK)                                                        │
│ asset_code (Unique, ex: MED-IGD-001)                           │
│ name (Defibrillator, Ventilator, USG 4D...)                    │
│ category_id (FK -> categories)                                 │
│ location_id (FK -> locations)                                  │
│ brand & model_type (Mindray, GE, Siemens..)                    │
│ serial_number                                                  │
│ purchase_date & purchase_cost (Decimal)                        │
│ condition (enum: 'baik', 'rusak_ringan', 'rusak_berat')        │
│ status (enum: 'aktif', 'pemeliharaan', 'afkir')                │
│ notes                                                          │
└───────────────────────────────┬────────────────────────────────┘
                                │ 1
                                │
                                │ N
┌───────────────────────────────┴────────────────────────────────┐
│                        maintenance_logs                        │
├────────────────────────────────────────────────────────────────┤
│ id (PK)                                                        │
│ asset_id (FK -> assets, cascade delete)                        │
│ maintenance_date                                               │
│ type (enum: 'kalibrasi', 'pemeliharaan_rutin', 'perbaikan')    │
│ cost                                                           │
│ vendor_technician (BPFK, Authorized Service)                   │
│ status (enum: 'selesai', 'dalam_proses')                       │
│ notes                                                          │
└────────────────────────────────────────────────────────────────┘
```

---

## 🚀 Panduan Menjalankan Aplikasi (Quick Start)

### 1. Kebutuhan Sistem
* PHP 8.2 ke atas (Direkomendasikan PHP 8.3 / 8.4 / 8.5)
* Composer 2.x
* Node.js v18+ & NPM
* MySQL Database Server (Laragon / XAMPP)

### 2. Langkah Instalasi

```bash
# 1. Clone repositori ini
git clone https://github.com/arsal-dev/MenAsset_Rs.Ummi.git
cd MenAsset_Rs.Ummi

# 2. Salin environment & atur konfigurasi database
cp .env.example .env
```

Pastikan variabel database pada file `.env` disesuaikan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=menasset_rs_ummi
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 3. Install dependency PHP & generate application key
composer install
php artisan key:generate

# 4. Jalankan migrasi database & data seeder realistis RS Ummi
php artisan migrate --seed

# 5. Install frontend dependency & compile aset
npm install
npm run build

# 6. Jalankan web server development
php artisan serve
```

Aplikasi dapat diakses melalui browser di: **`http://localhost:8000`**

---

## ⌨️ Daftar Perintah Berguna (Commands)

| Perintah | Deskripsi |
| :--- | :--- |
| `php artisan serve` | Menjalankan server lokal Laravel |
| `php artisan migrate:fresh --seed` | Mereset database dan menginput data demo RS Ummi |
| `php artisan test` | Menjalankan automated test suite (Unit & Feature) |
| `npm run dev` | Menjalankan Vite dev server untuk hot module replacement |
| `npm run build` | Melakukan build produksi Tailwind CSS |

---

## 🧪 Pengujian Otomatis (Automated Tests)

Proyek ini telah dilengkapi dengan unit & feature tests menggunakan PHPUnit untuk memastikan seluruh logika bisnis dan CRUD Livewire berjalan sempurna:

```bash
php artisan test
```

Hasil pengujian:
```
PASS  Tests\Unit\ExampleTest
✓ that true is true

PASS  Tests\Feature\AssetManagementTest
✓ dashboard page loads successfully and displays hospital branding
✓ livewire component renders and lists assets
✓ search filter narrows down results
✓ can create new asset via livewire
✓ validation prevents empty asset submission
✓ can view detail modal with maintenance history

PASS  Tests\Feature\ExampleTest
✓ the application returns a successful response

Tests:    8 passed (25 assertions)
Duration: 2.63s
```

---

## 👥 Data Demo Bawaan Seeder

Saat menjalankan `php artisan db:seed`, sistem otomatis menyediakan data inventaris realistis:
* **Akun Admin SIMRS**: `admin@rsummi.com` / `password123`
* **Ruangan**: IGD, ICU, Kamar Operasi (OK), Radiologi, Rawat Inap VIP, Laboratorium Patologi, Ruang Server SIMRS.
* **Alat Medis**: Defibrillator Mindray D3, Ventilator Hamilton-C3, USG 4D GE Voluson E8, Patient Monitor, C-Arm Fluoroscopy Siemens, Hospital Bed Elektrik, dan lainnya.
