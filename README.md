# Sistem Manajemen Aset & Peralatan Medis (RS Ummi Bogor)

Sistem Informasi Manajemen Inventaris & Aset Rumah Sakit berbasis web modern yang dibangun untuk memantau, mendata, dan mengelola peralatan medis, fasilitas ruangan, serta histori kalibrasi dan pemeliharaan alat kesehatan secara terpadu.

Aplikasi ini dikembangkan sebagai pemenuhan **Tugas Seleksi Magang Posisi IT Fullstack Web Developer RS Ummi Bogor**.

---

## 🛠️ Tech Stack & Arsitektur

* **Backend Framework**: [Laravel 13](https://laravel.com) (PHP 8.2+ / PHP 8.5)
* **Frontend Interactivity**: [Laravel Livewire 4](https://livewire.laravel.com) (Komponen Reaktif Real-time tanpa page reload)
* **CSS Framework**: [Tailwind CSS v4](https://tailwindcss.com) + Vite
* **Database**: MySQL 5.7+ / 8.0 (Integritas Relasi Foreign Key & Cascade Rules)
* **Testing Framework**: PHPUnit & Laravel Feature Test (**16 test suites, 56 assertions - 100% PASS**)
* **UI/UX Design**: Desain Modern Khas Enterprise Kesehatan (RS Ummi Emerald Green, Dark/Light Mode, Responsif Mobile & Desktop)

---

## ✨ Fitur Utama Aplikasi

### 1. Portal Admin IPSRS & SIMRS (`/`)
* **Dasbor Aset Interaktif**: Tabel daftar aset rumah sakit dilengkapi pagination dinamis berbasis Livewire (tanpa reload halaman).
* **CRUD Lengkap (Create, Read, Update, Delete)**:
  * **Create**: Pendaftaran aset baru dengan validasi data ketat & penomoran kode otomatis (`MED-xxx`, `LAB-xxx`, `RWT-xxx`, `RAD-xxx`, dll).
  * **Read**: Modal detail kelengkapan aset, spesifikasi teknis, lokasi gedung/lantai, PIC penanggung jawab ruangan, serta histori servis.
  * **Update**: Pembaruan spesifikasi teknis, harga perolehan, kondisi fisik, dan status operasional.
  * **Delete**: Penghapusan data dengan modal konfirmasi keamanan.
* **Live Search & Multi-Filter Real-time**: Pencarian instan (kode aset, nama alat, merk, nomor seri) dan filter dropdown simultan berdasarkan *Kategori*, *Ruangan*, dan *Kondisi Alat*.
* **Metric Summary Cards**: Widget ringkasan statistik di dasbor utama:
  * *Total Inventaris Unit*
  * *Alat Siap Operasional (Kondisi Baik)*
  * *Alat Perlu Kalibrasi / Rusak*
  * *Estimasi Nilai Kapitalisasi Aset (Format Rupiah IDR)*
* **Histori Servis & Kalibrasi (`maintenance_logs`)**: Pencatatan riwayat uji fungsi, kalibrasi berkala BPFK, dan servis teknisi untuk kepatuhan standar keselamatan medis rumah sakit.

### 2. Portal Petugas Lapangan & Teknisi Medis (`/lapangan`)
* **Pemindai Stiker QR Code**: Simulasi scan kamera & input kode QR untuk verifikasi fisik aset secara instan di lapangan.
* **Ceklis & Inventaris Per Ruangan**: Pengecekan kondisi alat medis berdasarkan ruangan (IGD, ICU, OK, dll) dengan filter cepat dan tombol pembaruan status fisik langsung dari lokasi.
* **Tiket Laporan Kerusakan Lapangan**: Form pelaporan cepat kendala teknis alat medis oleh perawat/petugas lapangan lengkap dengan tingkat urgensi dan status penanganan.

### 3. UI/UX Responsif & Tema
* **Dual Theme (Light & Dark Mode)**: Dilengkapi toggle tema dengan aksen khas RS Ummi Emerald Green (`#047857`, `#10B981`).
* **100% Mobile Responsive**: Tata letak navbar adaptif, tombol aksi fleksibel, dan tabel yang nyaman diakses dari smartphone maupun tablet teknisi di lapangan.

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
│ asset_code (Unique, ex: MED-001, LAB-001)                      │
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

# 2. Salin environment
cp .env.example .env
```

Pastikan database `menasset_rs_ummi` sudah dibuat di MySQL (atau sesuaikan konfigurasi pada file `.env`):
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

Aplikasi siap diakses melalui browser:
* **Portal Admin IPSRS**: **`http://localhost:8000/`**
* **Portal Petugas Lapangan**: **`http://localhost:8000/lapangan`**

---

## ⌨️ Daftar Perintah Berguna (Commands)

| Perintah | Deskripsi |
| :--- | :--- |
| `php artisan serve` | Menjalankan server lokal Laravel |
| `php artisan migrate:fresh --seed` | Mereset database dan menginput data demo lengkap RS Ummi |
| `php artisan test` | Menjalankan automated test suite (Unit & Feature) |
| `npm run dev` | Menjalankan Vite dev server untuk hot module replacement |
| `npm run build` | Melakukan compile produksi Tailwind CSS |

---

## 🧪 Pengujian Otomatis (Automated Tests)

Proyek ini telah dilengkapi dengan unit & feature tests menggunakan PHPUnit untuk memastikan seluruh logika bisnis, komponen Livewire, dan alur kerja aplikasi teruji dengan baik:

```bash
php artisan test
```

Hasil pengujian:
```
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\AssetManagementTest
  ✓ dashboard page loads successfully and displays hospital branding
  ✓ report tab displays financial cards
  ✓ livewire component renders and lists assets
  ✓ search filter narrows down results
  ✓ can create new asset via livewire
  ✓ validation prevents empty asset submission
  ✓ can view detail modal with maintenance history

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

   PASS  Tests\Feature\FieldAssetManagementTest
  ✓ field portal page loads successfully
  ✓ field tabs navigation switches views
  ✓ field staff can scan existing qr code to view detail
  ✓ scanning unregistered code shows danger alert
  ✓ field staff can confirm normal condition checklist
  ✓ field staff can submit defect report
  ✓ field staff can filter assets by room

  Tests:    16 passed (56 assertions)
  Duration: 4.74s
```

---

## 👥 Data Demo Bawaan Seeder

Saat menjalankan `php artisan migrate --seed`, sistem otomatis menyediakan data inventaris realistis:
* **Akun Admin SIMRS**: `admin@rsummi.com` / `password123`
* **Ruangan**: IGD, ICU, Kamar Operasi (OK), Radiologi, Rawat Inap VIP, Laboratorium Patologi, Ruang Server SIMRS.
* **Alat Medis**: Defibrillator Mindray D3, Ventilator Hamilton-C3, USG 4D GE Voluson E8, Patient Monitor Mindray, C-Arm Fluoroscopy Siemens, Hospital Bed Elektrik Paramount, Centrifuge Hettich Rotofix, dan lainnya.
