<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\MaintenanceLog;
use Illuminate\Database\Seeder;

class HospitalAssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $categories = [
            [
                'name' => 'Alat Medis & Elektromedik',
                'code' => 'MED',
                'description' => 'Peralatan medis diagnostik, monitoring pasien, dan penunjang hidup (life support).'
            ],
            [
                'name' => 'Peralatan Rawat Inap & Fasilitas',
                'code' => 'RAWAT',
                'description' => 'Bed pasien elektrik/manual, meja tindakan, lemari obat, dan fasilitas kamar.'
            ],
            [
                'name' => 'Peralatan Laboratorium & Farmasi',
                'code' => 'LAB',
                'description' => 'Alat analisis darah, centrifuge, mikroskop klinis, dan freezer reagen.'
            ],
            [
                'name' => 'Perangkat IT & Sistem Informasi RS',
                'code' => 'IT',
                'description' => 'Server SIMRS, workstation perawat, printer barcode, dan switch jaringan.'
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['code']] = Category::create($cat);
        }

        // 2. Locations / Ruangan RS Ummi
        $locations = [
            [
                'name' => 'Instalasi Gawat Darurat (IGD)',
                'code' => 'IGD',
                'building' => 'Gedung Medis Utama',
                'floor' => 'Lantai 1',
                'pic_name' => 'Ns. Rahmat Hidayat, S.Kep'
            ],
            [
                'name' => 'Intensive Care Unit (ICU)',
                'code' => 'ICU',
                'building' => 'Gedung Medis Utama',
                'floor' => 'Lantai 2',
                'pic_name' => 'dr. Sarah Amalia, Sp.An'
            ],
            [
                'name' => 'Kamar Operasi (OK / Bedah Sentral)',
                'code' => 'OK',
                'building' => 'Gedung Medis Utama',
                'floor' => 'Lantai 3',
                'pic_name' => 'Ns. Bambang Sutrisno, S.Kep'
            ],
            [
                'name' => 'Instalasi Radiologi & Imaging',
                'code' => 'RAD',
                'building' => 'Gedung Diagnostik Terpadu',
                'floor' => 'Lantai 1',
                'pic_name' => 'Ahmad Zaki, A.Md.Rad'
            ],
            [
                'name' => 'Ruang Rawat Inap VIP Ar-Rayan',
                'code' => 'VIP',
                'building' => 'Gedung Rawat Inap',
                'floor' => 'Lantai 4',
                'pic_name' => 'Siti Maryam, S.Kep'
            ],
            [
                'name' => 'Laboratorium Patologi Klinik',
                'code' => 'LAB',
                'building' => 'Gedung Diagnostik Terpadu',
                'floor' => 'Lantai 2',
                'pic_name' => 'Sri Wahyuni, S.Tr.Kes'
            ],
            [
                'name' => 'Pusat Data & Server SIMRS',
                'code' => 'IT',
                'building' => 'Gedung Manajemen',
                'floor' => 'Lantai 2',
                'pic_name' => 'Fahri Ramadhan, S.Kom'
            ],
        ];

        $locationModels = [];
        foreach ($locations as $loc) {
            $locationModels[$loc['code']] = Location::create($loc);
        }

        // 3. Hospital Assets Data
        $assets = [
            [
                'asset_code' => 'MED-IGD-001',
                'name' => 'Defibrillator Biphasic & Monitor',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['IGD']->id,
                'brand' => 'Mindray',
                'model_type' => 'BeneHeart D3',
                'serial_number' => 'MR-DF-2023-091',
                'purchase_date' => '2023-05-12',
                'purchase_cost' => 125000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Alat resusitasi jantung darurat di unit triage IGD. Terkalibrasi berkala.'
            ],
            [
                'asset_code' => 'MED-ICU-001',
                'name' => 'Ventilator Mekanik ICU Dewasa & Anak',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['ICU']->id,
                'brand' => 'Hamilton Medical',
                'model_type' => 'Hamilton-C3',
                'serial_number' => 'HM-VT-99214',
                'purchase_date' => '2022-11-20',
                'purchase_cost' => 485000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Unit ventilator utama ICU Bed 01. Flow sensor dan baterai backup prima.'
            ],
            [
                'asset_code' => 'MED-RAD-001',
                'name' => 'USG 4D General Electric Voluson E8',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['RAD']->id,
                'brand' => 'GE Healthcare',
                'model_type' => 'Voluson E8 Expert',
                'serial_number' => 'GE-USG-8812',
                'purchase_date' => '2023-02-15',
                'purchase_cost' => 750000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Dilengkapi probe konveks 3D/4D dan transvaginal.'
            ],
            [
                'asset_code' => 'MED-ICU-002',
                'name' => 'Bedside Patient Monitor 6 Parameter',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['ICU']->id,
                'brand' => 'Mindray',
                'model_type' => 'ePM 12M',
                'serial_number' => 'PM-12M-4402',
                'purchase_date' => '2024-01-10',
                'purchase_cost' => 45000000,
                'condition' => 'rusak_ringan',
                'status' => 'pemeliharaan',
                'notes' => 'Kabel sensor SpO2 mengalami intermittent signal, sedang dijadwalkan penggantian.'
            ],
            [
                'asset_code' => 'MED-OK-001',
                'name' => 'Sistem C-Arm Fluoroscopy Bedah',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['OK']->id,
                'brand' => 'Siemens Healthineers',
                'model_type' => 'Cios Select Flat Panel',
                'serial_number' => 'SM-CARM-092',
                'purchase_date' => '2022-08-30',
                'purchase_cost' => 1200000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Perangkat pencitraan real-time untuk bedah ortopedi dan vaskular di Kamar Bedah 1.'
            ],
            [
                'asset_code' => 'RAWAT-VIP-001',
                'name' => 'Bed Pasien Elektrik 4 Motor ICU/VIP',
                'category_id' => $categoryModels['RAWAT']->id,
                'location_id' => $locationModels['VIP']->id,
                'brand' => 'Paramon Medical',
                'model_type' => 'A5 ICU Electric Bed',
                'serial_number' => 'PM-BED-331',
                'purchase_date' => '2023-09-05',
                'purchase_cost' => 38000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Dilengkapi remote kontrol perawat, fungsi CPR cepat, dan matras anti-dekubitus.'
            ],
            [
                'asset_code' => 'MED-IGD-002',
                'name' => 'Syringe Infusion Pump Presisi',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['IGD']->id,
                'brand' => 'Terumo',
                'model_type' => 'TE-331 Precision',
                'serial_number' => 'TR-SP-7812',
                'purchase_date' => '2024-03-18',
                'purchase_cost' => 22500000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Digunakan untuk pemberian obat emergensi dosis ketat di ruang resusitasi IGD.'
            ],
            [
                'asset_code' => 'MED-OK-002',
                'name' => 'Surgical Suction Unit High Vacuum',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['OK']->id,
                'brand' => 'Medela',
                'model_type' => 'Dominant Flex',
                'serial_number' => 'MD-SC-1142',
                'purchase_date' => '2023-04-14',
                'purchase_cost' => 18500000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Kapasitas hisap 50 L/min, suction bedah steril.'
            ],
            [
                'asset_code' => 'LAB-LAB-001',
                'name' => 'Centrifuge Tabung Darah High-Speed',
                'category_id' => $categoryModels['LAB']->id,
                'location_id' => $locationModels['LAB']->id,
                'brand' => 'Hettich',
                'model_type' => 'Rotofix 32A',
                'serial_number' => 'HT-CF-5521',
                'purchase_date' => '2019-06-25',
                'purchase_cost' => 32000000,
                'condition' => 'rusak_berat',
                'status' => 'afkir',
                'notes' => 'Motor dynamo terbakar dan getaran melebihi toleransi. Direkomendasikan afkir/ganti baru.'
            ],
            [
                'asset_code' => 'LAB-LAB-002',
                'name' => 'Automated Hematology Analyzer 5-Diff',
                'category_id' => $categoryModels['LAB']->id,
                'location_id' => $locationModels['LAB']->id,
                'brand' => 'Sysmex',
                'model_type' => 'XN-350',
                'serial_number' => 'SY-XN-9021',
                'purchase_date' => '2023-07-12',
                'purchase_cost' => 310000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Mesin hitung sel darah otomatis 24 parameter. Terhubung interface SIMRS.'
            ],
            [
                'asset_code' => 'IT-IT-001',
                'name' => 'Rackmount Database Server SIMRS',
                'category_id' => $categoryModels['IT']->id,
                'location_id' => $locationModels['IT']->id,
                'brand' => 'Dell Technologies',
                'model_type' => 'PowerEdge R750 2U',
                'serial_number' => 'DL-PE-7721',
                'purchase_date' => '2023-01-20',
                'purchase_cost' => 95000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Server database utama rekam medis elektronik RS Ummi dengan RAID 10 SSD.'
            ],
            [
                'asset_code' => 'MED-IGD-003',
                'name' => 'Volumetric Infusion Pump Digital',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['IGD']->id,
                'brand' => 'Mindray',
                'model_type' => 'BeneFusion VP5',
                'serial_number' => 'MR-VP-6612',
                'purchase_date' => '2024-02-11',
                'purchase_cost' => 24000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Pengatur infus cairan obat otomatis dengan sensor deteksi gelembung udara.'
            ],
            [
                'asset_code' => 'MED-RAD-002',
                'name' => 'Mobile Digital X-Ray Portable',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['RAD']->id,
                'brand' => 'Shimadzu',
                'model_type' => 'MobileDaRt Evolution MX7',
                'serial_number' => 'SH-MX7-3312',
                'purchase_date' => '2022-10-05',
                'purchase_cost' => 620000000,
                'condition' => 'rusak_ringan',
                'status' => 'pemeliharaan',
                'notes' => 'Penggantian colimator lamp dan kalibrasi dosis radiasi berkala.'
            ],
            [
                'asset_code' => 'RAWAT-VIP-002',
                'name' => 'Hospital Bed Manual 3-Crank Deluxe',
                'category_id' => $categoryModels['RAWAT']->id,
                'location_id' => $locationModels['VIP']->id,
                'brand' => 'Acare',
                'model_type' => 'Deluxe 3 Crank Hospital Bed',
                'serial_number' => 'AC-BED-991',
                'purchase_date' => '2023-10-10',
                'purchase_cost' => 14500000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Bed cadangan rawat inap dengan pengatur posisi punggung, lutut, dan ketinggian.'
            ],
            [
                'asset_code' => 'MED-OK-003',
                'name' => 'Autoclave Sterilisator Uap Kapasitas Besar',
                'category_id' => $categoryModels['MED']->id,
                'location_id' => $locationModels['OK']->id,
                'brand' => 'Tuttnauer',
                'model_type' => '3870EA Hospital Autoclave',
                'serial_number' => 'TT-AC-4412',
                'purchase_date' => '2023-03-01',
                'purchase_cost' => 85000000,
                'condition' => 'baik',
                'status' => 'aktif',
                'notes' => 'Sterilisasi instrumen bedah kamar operasi dengan sistem vacuum drying.'
            ],
        ];

        $createdAssets = [];
        foreach ($assets as $a) {
            $createdAssets[$a['asset_code']] = Asset::create($a);
        }

        // 4. Maintenance Logs (Inovasi: Histori Kalibrasi & Servis)
        $maintenance = [
            [
                'asset_id' => $createdAssets['MED-IGD-001']->id,
                'maintenance_date' => '2026-05-10',
                'type' => 'kalibrasi',
                'cost' => 2500000,
                'vendor_technician' => 'Balai Pengujian Fasilitas Kesehatan (BPFK)',
                'status' => 'selesai',
                'notes' => 'Uji akurasi discharge energi joule defibrillator. Dinyatakan LAIK PAKAI hingga Mei 2027.'
            ],
            [
                'asset_id' => $createdAssets['MED-ICU-001']->id,
                'maintenance_date' => '2026-08-14',
                'type' => 'pemeliharaan_rutin',
                'cost' => 4200000,
                'vendor_technician' => 'PT. Medika Servis Solusindo',
                'status' => 'selesai',
                'notes' => 'Penggantian filter HEPA intake, kalibrasi flow sensor inspirasi/ekspirasi, dan uji baterai internal.'
            ],
            [
                'asset_id' => $createdAssets['MED-ICU-002']->id,
                'maintenance_date' => '2026-11-02',
                'type' => 'perbaikan',
                'cost' => 3800000,
                'vendor_technician' => 'Teknisi Elektromedik Internal RS',
                'status' => 'dalam_proses',
                'notes' => 'Penggantian connector board sensor SpO2 dan kalibrasi modul NIBP.'
            ],
            [
                'asset_id' => $createdAssets['MED-RAD-002']->id,
                'maintenance_date' => '2026-10-28',
                'type' => 'perbaikan',
                'cost' => 12500000,
                'vendor_technician' => 'Authorized Shimadzu Engineer',
                'status' => 'dalam_proses',
                'notes' => 'Penggantian lampu collimator dan penyesuaian ketepatan berkas radiasi optik.'
            ],
            [
                'asset_id' => $createdAssets['LAB-LAB-001']->id,
                'maintenance_date' => '2026-04-12',
                'type' => 'perbaikan',
                'cost' => 1500000,
                'vendor_technician' => 'CV. Jaya Medikal Servis',
                'status' => 'selesai',
                'notes' => 'Pemeriksaan rotor dinamo; motor aus dan rotor unbalance berat. Direkomendasikan write-off / afkir.'
            ]
        ];

        foreach ($maintenance as $m) {
            MaintenanceLog::create($m);
        }
    }
}

