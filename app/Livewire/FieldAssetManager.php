<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\MaintenanceLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class FieldAssetManager extends Component
{
    use WithPagination, WithFileUploads;

    // Active View Tabs: 'scan' (Scan QR & Cek Alat), 'room' (Ceklis Ruangan), 'reports' (Riwayat Laporan Kerusakan)
    public string $activeTab = 'scan';

    // Room Checklist Filters
    public string $search = '';
    public string $selectedLocationId = '';
    public string $filterCondition = '';

    // Scanned / Manual Input Code
    public string $manualCode = '';

    // Asset Detail Inspection
    public ?int $selectedAssetId = null;
    public ?Asset $selectedAsset = null;
    public bool $isDetailOpen = false;

    // Quick Defect Report Modal
    public bool $isReportModalOpen = false;
    public string $report_notes = '';
    public string $report_condition = 'rusak_ringan';
    public string $report_type = 'perbaikan';
    public string $report_reporter = 'Petugas Ruangan';

    // Feedback Flash Alert
    public ?string $feedbackMessage = null;
    public string $feedbackType = 'success';

    public function mount(): void
    {
        // Default pilih ruangan pertama jika ada untuk Ceklis Ruangan
        if (empty($this->selectedLocationId)) {
            $firstLoc = Location::has('assets')->first() ?? Location::first();
            if ($firstLoc) {
                $this->selectedLocationId = (string) $firstLoc->id;
            }
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
        $this->dispatch('tab-changed', tab: $tab);
    }

    /**
     * Tangani hasil pemindaian kamera QR Code atau input manual
     */
    public function handleScannedCode(?string $code = null): void
    {
        $targetCode = trim($code ?: $this->manualCode);

        if (empty($targetCode)) {
            $this->feedbackMessage = 'Silakan masukkan atau scan kode aset terlebih dahulu.';
            $this->feedbackType = 'warning';
            return;
        }

        $asset = Asset::with(['category', 'location', 'maintenanceLogs' => fn($q) => $q->latest('maintenance_date')])
            ->where('asset_code', $targetCode)
            ->orWhere('serial_number', $targetCode)
            ->first();

        if ($asset) {
            $this->selectedAssetId = $asset->id;
            $this->selectedAsset = $asset;
            $this->manualCode = $asset->asset_code;
            $this->feedbackMessage = "Alat medis [{$asset->name}] berhasil ditemukan!";
            $this->feedbackType = 'success';
        } else {
            $this->selectedAssetId = null;
            $this->selectedAsset = null;
            $this->feedbackMessage = "Kode [{$targetCode}] tidak ditemukan dalam inventaris RS Ummi. Pastikan stiker QR resmi dari Admin IPSRS.";
            $this->feedbackType = 'danger';
        }
    }

    /**
     * Buka Modal Detail Alat Medis
     */
    public function openDetail(int $id): void
    {
        $asset = Asset::with(['category', 'location', 'maintenanceLogs' => fn($q) => $q->latest('maintenance_date')])->find($id);
        if ($asset) {
            $this->selectedAssetId = $asset->id;
            $this->selectedAsset = $asset;
            $this->isDetailOpen = true;
        }
    }

    public function closeDetail(): void
    {
        $this->isDetailOpen = false;
    }

    /**
     * Buka Modal Lapor Kerusakan
     */
    public function openReportModal(?int $assetId = null): void
    {
        if ($assetId) {
            $asset = Asset::with(['category', 'location'])->find($assetId);
            if ($asset) {
                $this->selectedAssetId = $asset->id;
                $this->selectedAsset = $asset;
            }
        }

        if (! $this->selectedAsset) {
            $this->feedbackMessage = 'Pilih atau scan alat medis terlebih dahulu sebelum melaporkan kerusakan.';
            $this->feedbackType = 'warning';
            return;
        }

        $this->isReportModalOpen = true;
        $this->report_notes = '';
        $this->report_condition = 'rusak_ringan';
        $this->report_type = 'perbaikan';
        $this->report_reporter = 'Perawat / Petugas Ruangan';
    }

    public function closeReportModal(): void
    {
        $this->isReportModalOpen = false;
    }

    /**
     * Kirim Tiket Laporan Kerusakan ke Meja Kerja Tim IPSRS
     */
    public function submitDefectReport(): void
    {
        if (! $this->selectedAsset) {
            return;
        }

        $this->validate([
            'report_notes' => ['required', 'string', 'min:3', 'max:500'],
            'report_condition' => ['required', 'in:rusak_ringan,rusak_berat'],
            'report_reporter' => ['required', 'string', 'max:100'],
        ], [
            'report_notes.required' => 'Mohon jelaskan gejala kerusakan atau kendala fisik alat.',
            'report_reporter.required' => 'Nama pelapor/petugas ruangan wajib diisi.',
        ]);

        // Perbarui kondisi fisik alat menjadi rusak & status menjadi pemeliharaan
        $this->selectedAsset->update([
            'condition' => $this->report_condition,
            'status' => 'pemeliharaan',
        ]);

        // Buat tiket log pemeliharaan untuk Admin/Teknisi IPSRS
        MaintenanceLog::create([
            'asset_id' => $this->selectedAsset->id,
            'type' => $this->report_type,
            'maintenance_date' => now(),
            'cost' => 0,
            'vendor_technician' => $this->report_reporter,
            'status' => 'dalam_proses',
            'notes' => 'Laporan Lapangan: ' . $this->report_notes,
        ]);

        $this->isReportModalOpen = false;
        $this->selectedAsset->refresh();
        $this->feedbackMessage = "Laporan kerusakan untuk [{$this->selectedAsset->name}] berhasil dikirim ke Admin IPSRS!";
        $this->feedbackType = 'success';
    }

    /**
     * Verifikasi Fisik Harian: Konfirmasi Alat Normal & Siap Pakai
     */
    public function confirmNormalCondition(int $assetId): void
    {
        $asset = Asset::find($assetId);
        if (! $asset) {
            return;
        }

        // Update status alat menjadi aktif dan kondisi baik
        $asset->update([
            'condition' => 'baik',
            'status' => 'aktif',
        ]);

        // Catat inspeksi rutin
        MaintenanceLog::create([
            'asset_id' => $asset->id,
            'type' => 'pemeliharaan_rutin',
            'maintenance_date' => now(),
            'cost' => 0,
            'vendor_technician' => 'Inspeksi Ruangan',
            'status' => 'selesai',
            'notes' => 'Ceklis Lapangan: Fisik alat lengkap, normal, dan siap pakai.',
        ]);

        if ($this->selectedAsset && $this->selectedAsset->id === $assetId) {
            $this->selectedAsset->refresh();
        }

        $this->feedbackMessage = "✅ Fisik alat [{$asset->name}] terverifikasi BAIK dan Siap Pakai!";
        $this->feedbackType = 'success';
    }

    public function updatedSelectedLocationId(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterCondition(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $locations = Location::withCount('assets')->orderBy('name')->get();

        // Query untuk Tab 2: Ceklis Ruangan
        $roomAssets = null;
        $roomStats = [
            'total' => 0,
            'ready' => 0,
            'maintenance' => 0,
        ];

        if ($this->selectedLocationId) {
            $roomQuery = Asset::with(['category', 'location'])
                ->where('location_id', $this->selectedLocationId)
                ->when($this->search, function ($q) {
                    $term = "%{$this->search}%";
                    $q->where(function ($sub) use ($term) {
                        $sub->where('name', 'LIKE', $term)
                            ->orWhere('asset_code', 'LIKE', $term)
                            ->orWhere('serial_number', 'LIKE', $term)
                            ->orWhere('brand', 'LIKE', $term);
                    });
                })
                ->when($this->filterCondition, fn($q) => $q->where('condition', $this->filterCondition))
                ->latest('updated_at');

            $allInRoom = Asset::where('location_id', $this->selectedLocationId)->get();
            $roomStats['total'] = $allInRoom->count();
            $roomStats['ready'] = $allInRoom->where('condition', 'baik')->where('status', 'aktif')->count();
            $roomStats['maintenance'] = $allInRoom->where(fn($a) => $a->condition !== 'baik' || $a->status === 'pemeliharaan')->count();

            $roomAssets = $roomQuery->paginate(12);
        }

        // Query untuk Tab 3: Riwayat & Status Tiket Laporan Lapangan
        $recentReports = MaintenanceLog::with(['asset.location', 'asset.category'])
            ->where('notes', 'LIKE', 'Laporan Lapangan%')
            ->orWhere('type', 'perbaikan')
            ->latest('maintenance_date')
            ->take(20)
            ->get();

        return view('livewire.field-asset-manager', [
            'locations' => $locations,
            'roomAssets' => $roomAssets,
            'roomStats' => $roomStats,
            'recentReports' => $recentReports,
        ]);
    }
}
