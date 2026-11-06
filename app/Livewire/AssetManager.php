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

class AssetManager extends Component
{
    use WithPagination, WithFileUploads;

    // Active Admin Tab (Gaya Alkamart)
    public string $activeTab = 'tabAssets'; // tabAssets, tabMaintenance, tabReports, tabLogs

    // Filter & Search Properties
    public string $search = '';
    public string $filterCategory = '';
    public string $filterLocation = '';
    public string $filterCondition = '';
    public string $filterStatus = '';
    public int $perPage = 8;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Modal Control Flags
    public bool $isFormOpen = false;
    public bool $isDetailOpen = false;
    public bool $isDeleteOpen = false;
    public bool $isPrintLabelOpen = false;
    public bool $isLogModalOpen = false;
    public bool $isClearAllOpen = false;

    // Selection & Data
    public ?int $selectedAssetId = null;
    public ?Asset $selectedAsset = null;

    // Form Model Fields (Aset)
    public string $asset_code = '';
    public string $name = '';
    public string $category_id = '';
    public string $location_id = '';
    public string $brand = '';
    public string $model_type = '';
    public string $serial_number = '';
    public string $purchase_date = '';
    public $purchase_cost = 0;
    public string $condition = 'baik';
    public string $status = 'aktif';
    public string $notes = '';
    public $image = null;
    public ?string $image_path = null;

    // Form Maintenance Log Fields (Inovasi Input Servis)
    public string $log_type = 'kalibrasi';
    public string $log_date = '';
    public $log_cost = 0;
    public string $log_technician = '';
    public string $log_status = 'selesai';
    public string $log_notes = '';

    // Alert Notification Message
    public ?string $feedbackMessage = null;
    public string $feedbackType = 'success'; // success, danger, warning

    // Validation Rules
    protected function rules(): array
    {
        return [
            'asset_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('assets', 'asset_code')->ignore($this->selectedAssetId)
            ],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', 'exists:locations,id'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model_type' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'status' => ['required', 'in:aktif,pemeliharaan,afkir'],
            'image' => ['nullable', 'image', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    protected array $messages = [
        'asset_code.required' => 'Kode aset rumah sakit wajib diisi.',
        'asset_code.unique' => 'Kode aset ini sudah terdaftar di sistem.',
        'name.required' => 'Nama alat/aset rumah sakit wajib diisi.',
        'category_id.required' => 'Silakan pilih kategori aset.',
        'location_id.required' => 'Silakan tentukan ruangan penempatan aset.',
        'purchase_cost.required' => 'Harga perolehan wajib diisi (bisa 0 jika hibah).',
        'purchase_cost.numeric' => 'Harga perolehan harus berupa angka.',
    ];

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function selectCategoryPill(string $catId): void
    {
        if ($this->filterCategory === $catId) {
            $this->filterCategory = '';
        } else {
            $this->filterCategory = $catId;
        }
        $this->resetPage();
    }

    // Reset pagination on filter change
    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterCategory(): void { $this->resetPage(); }
    public function updatingFilterLocation(): void { $this->resetPage(); }
    public function updatingFilterCondition(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->selectedAssetId = null;

        // Auto suggest kode ringkas 3 karakter alfanumerik (M01, L01, R01, I01)
        $firstCat = Category::first();
        if ($firstCat) {
            $this->category_id = (string) $firstCat->id;
            $this->suggestCode();
        } else {
            $this->asset_code = 'A01';
        }

        $this->purchase_date = date('Y-m-d');
        $this->condition = 'baik';
        $this->status = 'aktif';

        $this->isFormOpen = true;
    }

    public function updatedCategoryId($val): void
    {
        if ($this->isFormOpen && empty($this->selectedAssetId) && empty($this->asset_code)) {
            $this->suggestCode();
        }
    }

    private function suggestCode(): void
    {
        $cat = Category::find($this->category_id);
        $prefix = $cat ? strtoupper($cat->code) : 'MED';
        $count = Asset::where('asset_code', 'like', $prefix . '-%')->count() + 1;
        $this->asset_code = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $asset = Asset::findOrFail($id);

        $this->selectedAssetId = $asset->id;
        $this->asset_code = $asset->asset_code;
        $this->name = $asset->name;
        $this->category_id = (string) $asset->category_id;
        $this->location_id = (string) $asset->location_id;
        $this->brand = $asset->brand ?? '';
        $this->model_type = $asset->model_type ?? '';
        $this->serial_number = $asset->serial_number ?? '';
        $this->purchase_date = $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '';
        $this->purchase_cost = (float) $asset->purchase_cost;
        $this->condition = $asset->condition;
        $this->status = $asset->status;
        $this->image = null;
        $this->image_path = $asset->image_path;
        $this->notes = $asset->notes ?? '';

        $this->isFormOpen = true;
    }

    public function save(): void
    {
        $this->validate();

        $imagePath = $this->image_path;
        if ($this->image) {
            $imagePath = 'storage/' . $this->image->store('assets', 'public');
        }

        Asset::updateOrCreate(
            ['id' => $this->selectedAssetId],
            [
                'asset_code' => $this->asset_code,
                'name' => $this->name,
                'category_id' => $this->category_id,
                'location_id' => $this->location_id,
                'brand' => $this->brand ?: null,
                'model_type' => $this->model_type ?: null,
                'serial_number' => $this->serial_number ?: null,
                'purchase_date' => $this->purchase_date ?: null,
                'purchase_cost' => $this->purchase_cost,
                'condition' => $this->condition,
                'status' => $this->status,
                'image_path' => $imagePath,
                'notes' => $this->notes ?: null,
            ]
        );

        $isEdit = !is_null($this->selectedAssetId);
        $this->feedbackMessage = $isEdit 
            ? "Data aset '{$this->name}' berhasil diperbarui!" 
            : "Aset baru '{$this->name}' berhasil didaftarkan ke SIMRS RS Ummi!";
        $this->feedbackType = 'success';

        $this->closeModal();
    }

    public function openDetailModal(int $id): void
    {
        $this->selectedAsset = Asset::with(['category', 'location', 'maintenanceLogs'])->findOrFail($id);
        $this->isDetailOpen = true;
    }

    public function openPrintLabelModal(int $id): void
    {
        $this->selectedAsset = Asset::with(['category', 'location'])->findOrFail($id);
        $this->isPrintLabelOpen = true;
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedAssetId = $id;
        $this->selectedAsset = Asset::findOrFail($id);
        $this->isDeleteOpen = true;
    }

    public function delete(): void
    {
        if ($this->selectedAssetId) {
            $asset = Asset::findOrFail($this->selectedAssetId);
            $name = $asset->name;
            $asset->delete();

            $this->feedbackMessage = "Aset '{$name}' telah dihapus dari inventaris.";
            $this->feedbackType = 'danger';
        }

        $this->closeModal();
    }

    public function openAddLogModal(int $assetId): void
    {
        $this->selectedAssetId = $assetId;
        $this->selectedAsset = Asset::findOrFail($assetId);
        $this->log_type = 'kalibrasi';
        $this->log_date = date('Y-m-d');
        $this->log_cost = 0;
        $this->log_technician = 'Teknisi Elektromedik RS';
        $this->log_status = 'selesai';
        $this->log_notes = '';
        $this->isLogModalOpen = true;
    }

    public function saveLog(): void
    {
        $this->validate([
            'log_date' => ['required', 'date'],
            'log_type' => ['required', 'in:kalibrasi,pemeliharaan_rutin,perbaikan'],
            'log_cost' => ['required', 'numeric', 'min:0'],
            'log_technician' => ['nullable', 'string', 'max:150'],
            'log_status' => ['required', 'in:selesai,dalam_proses'],
            'log_notes' => ['required', 'string', 'max:500'],
        ]);

        MaintenanceLog::create([
            'asset_id' => $this->selectedAssetId,
            'maintenance_date' => $this->log_date,
            'type' => $this->log_type,
            'cost' => $this->log_cost,
            'vendor_technician' => $this->log_technician,
            'status' => $this->log_status,
            'notes' => $this->log_notes,
        ]);

        $this->feedbackMessage = "Riwayat pemeliharaan/kalibrasi berhasil dicatat!";
        $this->feedbackType = 'success';
        $this->isLogModalOpen = false;

        // refresh selected asset if open
        if ($this->isDetailOpen && $this->selectedAsset) {
            $this->selectedAsset->load('maintenanceLogs');
        }
    }

    public function closeModal(): void
    {
        $this->isFormOpen = false;
        $this->isDetailOpen = false;
        $this->isDeleteOpen = false;
        $this->isPrintLabelOpen = false;
        $this->isLogModalOpen = false;
        $this->isClearAllOpen = false;
        $this->resetForm();
    }

    public function handleClearButton(): void
    {
        if ($this->search || $this->filterCategory || $this->filterLocation || $this->filterCondition || $this->filterStatus) {
            $this->resetFilters();
            $this->feedbackMessage = 'Semua filter pencarian berhasil dibersihkan.';
            $this->feedbackType = 'info';
            return;
        }
        $this->isClearAllOpen = true;
    }

    public function clearAllProducts(): void
    {
        Asset::truncate();
        $this->isClearAllOpen = false;
        $this->feedbackMessage = 'Semua data produk/aset berhasil dikosongkan.';
        $this->feedbackType = 'danger';
        $this->resetPage();
    }

    public function reseedDefaultAssets(): void
    {
        \Artisan::call('db:seed', ['--class' => 'HospitalAssetSeeder', '--force' => true]);
        $this->isClearAllOpen = false;
        $this->feedbackMessage = 'Data master aset RS Ummi berhasil dimuat ulang ke data bawaan.';
        $this->feedbackType = 'success';
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterCategory = '';
        $this->filterLocation = '';
        $this->filterCondition = '';
        $this->filterStatus = '';
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->asset_code = '';
        $this->name = '';
        $this->category_id = '';
        $this->location_id = '';
        $this->brand = '';
        $this->model_type = '';
        $this->serial_number = '';
        $this->purchase_date = '';
        $this->purchase_cost = 0;
        $this->condition = 'baik';
        $this->status = 'aktif';
        $this->image = null;
        $this->image_path = null;
        $this->notes = '';
    }

    public function render()
    {
        // Query Assets with Relations
        $query = Asset::with(['category', 'location']);

        // Tab Filter: Perlu Servis & Kalibrasi
        if ($this->activeTab === 'tabMaintenance') {
            $query->where(function ($q) {
                $q->where('condition', '!=', 'baik')
                  ->orWhere('status', '!=', 'aktif');
            });
        }

        // Search Filter
        if (!empty($this->search)) {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('asset_code', 'like', $s)
                  ->orWhere('serial_number', 'like', $s)
                  ->orWhere('brand', 'like', $s)
                  ->orWhere('model_type', 'like', $s);
            });
        }

        // Dropdown & Pill Filters
        if (!empty($this->filterCategory)) {
            $query->where('category_id', $this->filterCategory);
        }

        if (!empty($this->filterLocation)) {
            $query->where('location_id', $this->filterLocation);
        }

        if (!empty($this->filterCondition)) {
            $query->where('condition', $this->filterCondition);
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $assets = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Stats Dashboard Metrics (Alkamart Style)
        $stats = [
            'total' => Asset::count(),
            'good' => Asset::where('condition', 'baik')->where('status', 'aktif')->count(),
            'need_attention' => Asset::where(function ($q) {
                $q->where('condition', '!=', 'baik')
                  ->orWhere('status', '!=', 'aktif');
            })->count(),
            'total_value' => Asset::sum('purchase_cost'),
            'total_maintenance_cost' => MaintenanceLog::sum('cost'),
            'total_logs' => MaintenanceLog::count(),
        ];

        // All Logs for Tab 4
        $logs = MaintenanceLog::with(['asset.location', 'asset.category'])
            ->orderBy('maintenance_date', 'desc')
            ->paginate(10, ['*'], 'logsPage');

        return view('livewire.asset-manager', [
            'assets' => $assets,
            'stats' => $stats,
            'logs' => $logs,
            'categories' => Category::withCount('assets')->orderBy('name')->get(),
            'locations' => Location::withCount('assets')->orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
