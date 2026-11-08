<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\MaintenanceLog;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class FieldAssetManager extends Component
{
    use WithPagination, WithFileUploads;

    // Active Mobile View: 'scan' (Pemindai QR), 'list' (Katalog Ruangan), 'create' (Input Baru)
    public string $activeTab = 'scan';

    // Search & Filter
    public string $search = '';
    public string $filterLocation = '';
    public string $filterCondition = '';

    // Asset Detail & Bottom Sheet
    public ?int $selectedAssetId = null;
    public ?Asset $selectedAsset = null;
    public bool $isDetailOpen = false;

    // Quick Defect Report Modal
    public bool $isReportModalOpen = false;
    public string $report_notes = '';
    public string $report_condition = 'rusak_ringan';
    public string $report_type = 'perbaikan';
    public string $report_technician = '';

    // Create New Asset Form Fields (Mobile Friendly)
    public string $asset_code = '';
    public string $name = '';
    public string $category_id = '';
    public string $location_id = '';
    public string $brand = '';
    public string $model_type = '';
    public string $serial_number = '';
    public string $condition = 'baik';
    public string $status = 'aktif';
    public $purchase_cost = 0;
    public $image = null;
    public string $notes = '';

    // Feedback Alert
    public ?string $feedbackMessage = null;
    public string $feedbackType = 'success';

    protected function rules(): array
    {
        return [
            'asset_code' => ['required', 'string', 'max:50', 'unique:assets,asset_code'],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model_type' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'condition' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'status' => ['required', 'in:aktif,pemeliharaan,afkir'],
            'image' => ['nullable', 'image', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected array $messages = [
        'asset_code.required' => 'Kode aset wajib diisi.',
        'asset_code.unique' => 'Kode aset ini sudah terdaftar di sistem.',
        'name.required' => 'Nama alat medis wajib diisi.',
        'category_id.required' => 'Pilih kategori alat.',
        'location_id.required' => 'Pilih ruangan penempatan alat.',
    ];

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
        $this->dispatch('tab-changed', tab: $tab);
    }

    /**
     * Otomatis generate kode aset baru berdasarkan kategori
     */
    public function generateAssetCode(): void
    {
        if (! $this->category_id) {
            $prefix = 'MED';
        } else {
            $cat = Category::find($this->category_id);
            $prefix = $cat ? $cat->code : 'MED';
        }

        $count = Asset::where('asset_code', 'LIKE', "{$prefix}-%")->count() + 1;
        $this->asset_code = sprintf('%s-%03d', $prefix, $count);
    }

    public function updatedCategoryId(): void
    {
        if (empty($this->asset_code)) {
            $this->generateAssetCode();
        }
    }

    /**
     * Tangani hasil scan kamera QR Code
     */
    public function handleScannedCode(string $code): void
    {
        $cleanCode = trim($code);
        
        // Cari berdasarkan kode aset atau nomor seri
        $asset = Asset::with(['category', 'location', 'maintenanceLogs' => fn($q) => $q->latest('maintenance_date')])
            ->where('asset_code', $cleanCode)
            ->orWhere('serial_number', $cleanCode)
            ->first();

        if ($asset) {
            $this->selectedAssetId = $asset->id;
            $this->selectedAsset = $asset;
            $this->isDetailOpen = true;
            $this->feedbackMessage = "Alat medis [{$asset->name}] berhasil terdeteksi!";
            $this->feedbackType = 'success';
        } else {
            // Jika belum ada, tawarkan pendaftaran instan
            $this->asset_code = $cleanCode;
            $this->feedbackMessage = "Kode [{$cleanCode}] belum terdaftar. Formulir pendaftaran disiapkan.";
            $this->feedbackType = 'warning';
            $this->activeTab = 'create';
        }
    }

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
        $this->selectedAssetId = null;
        $this->selectedAsset = null;
    }

    public function openReportModal(): void
    {
        $this->isReportModalOpen = true;
        $this->report_notes = '';
        $this->report_condition = 'rusak_ringan';
        $this->report_type = 'perbaikan';
        $this->report_technician = auth()->user()->name ?? 'Petugas Ruangan';
    }

    public function closeReportModal(): void
    {
        $this->isReportModalOpen = false;
    }

    /**
     * Kirim Laporan Kerusakan dari Lapangan ke Tim IPSRS
     */
    public function submitDefectReport(): void
    {
        if (! $this->selectedAsset) {
            return;
        }

        $this->validate([
            'report_notes' => ['required', 'string', 'min:3', 'max:500'],
            'report_condition' => ['required', 'in:rusak_ringan,rusak_berat'],
        ], [
            'report_notes.required' => 'Mohon jelaskan gejala kerusakan atau kendala alat.',
        ]);

        // Perbarui kondisi aset
        $this->selectedAsset->update([
            'condition' => $this->report_condition,
            'status' => 'pemeliharaan',
        ]);

        // Catat riwayat log servis
        MaintenanceLog::create([
            'asset_id' => $this->selectedAsset->id,
            'type' => $this->report_type,
            'maintenance_date' => now(),
            'cost' => 0,
            'vendor_technician' => $this->report_technician ?: 'Petugas Ruangan RS Ummi',
            'status' => 'dalam_proses',
            'notes' => 'Laporan Lapangan: ' . $this->report_notes,
        ]);

        $this->isReportModalOpen = false;
        $this->selectedAsset->refresh();
        $this->feedbackMessage = 'Laporan kerusakan berhasil dikirim! Status alat diperbarui menjadi Dalam Pemeliharaan.';
        $this->feedbackType = 'success';
    }

    /**
     * Update kondisi alat secara cepat (misal setelah dicek berfungsi normal kembali)
     */
    public function quickSetCondition(string $condition): void
    {
        if (! $this->selectedAsset) {
            return;
        }

        $this->selectedAsset->update([
            'condition' => $condition,
            'status' => $condition === 'baik' ? 'aktif' : 'pemeliharaan',
        ]);

        $this->selectedAsset->refresh();
        $this->feedbackMessage = 'Status kondisi alat berhasil diperbarui menjadi ' . strtoupper(str_replace('_', ' ', $condition)) . '!';
        $this->feedbackType = 'success';
    }

    /**
     * Simpan pendaftaran alat medis baru dari lapangan
     */
    public function saveAsset(): void
    {
        $validated = $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('assets', 'public');
        }

        $asset = Asset::create([
            'asset_code' => strtoupper(trim($validated['asset_code'])),
            'name' => trim($validated['name']),
            'category_id' => $validated['category_id'],
            'location_id' => $validated['location_id'],
            'brand' => $validated['brand'] ?: null,
            'model_type' => $validated['model_type'] ?: null,
            'serial_number' => $validated['serial_number'] ?: null,
            'purchase_date' => now()->toDateString(),
            'purchase_cost' => $validated['purchase_cost'] ?: 0,
            'condition' => $validated['condition'],
            'status' => $validated['status'],
            'image_path' => $imagePath,
            'notes' => $validated['notes'] ?: null,
        ]);

        // Reset form
        $this->reset(['asset_code', 'name', 'category_id', 'location_id', 'brand', 'model_type', 'serial_number', 'image', 'notes']);
        $this->condition = 'baik';
        $this->status = 'aktif';
        $this->purchase_cost = 0;

        $this->feedbackMessage = "Alat medis [{$asset->name}] berhasil didaftarkan di sistem inventaris!";
        $this->feedbackType = 'success';

        // Langsung tampilkan detail alat yang baru diinput
        $this->openDetail($asset->id);
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();
        $locations = Location::withCount('assets')->orderBy('name')->get();

        $assetsQuery = Asset::with(['category', 'location'])
            ->when($this->search, function ($q) {
                $term = "%{$this->search}%";
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'LIKE', $term)
                        ->orWhere('asset_code', 'LIKE', $term)
                        ->orWhere('serial_number', 'LIKE', $term)
                        ->orWhere('brand', 'LIKE', $term);
                });
            })
            ->when($this->filterLocation, fn($q) => $q->where('location_id', $this->filterLocation))
            ->when($this->filterCondition, fn($q) => $q->where('condition', $this->filterCondition))
            ->latest('updated_at');

        $assets = $assetsQuery->paginate(8);

        return view('livewire.field-asset-manager', [
            'categories' => $categories,
            'locations' => $locations,
            'assets' => $assets,
        ]);
    }
}

