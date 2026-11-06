<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class AssetManager extends Component
{
    use WithPagination;

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

    // Selection & Data
    public ?int $selectedAssetId = null;
    public ?Asset $selectedAsset = null;

    // Form Model Fields
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

        // Auto suggest code based on count
        $nextId = Asset::max('id') + 1;
        $this->asset_code = 'MED-RSU-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $this->purchase_date = date('Y-m-d');
        $this->condition = 'baik';
        $this->status = 'aktif';

        $this->isFormOpen = true;
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
        $this->notes = $asset->notes ?? '';

        $this->isFormOpen = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

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
                'notes' => $this->notes ?: null,
            ]
        );

        $isEdit = !is_null($this->selectedAssetId);
        $this->feedbackMessage = $isEdit 
            ? "Data aset '{$this->name}' berhasil diperbarui!" 
            : "Aset baru '{$this->name}' berhasil ditambahkan ke inventaris RS Ummi!";
        $this->feedbackType = 'success';

        $this->closeModal();
    }

    public function openDetailModal(int $id): void
    {
        $this->selectedAsset = Asset::with(['category', 'location', 'maintenanceLogs'])->findOrFail($id);
        $this->isDetailOpen = true;
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

            $this->feedbackMessage = "Aset '{$name}' telah dihapus dari sistem.";
            $this->feedbackType = 'danger';
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->isFormOpen = false;
        $this->isDetailOpen = false;
        $this->isDeleteOpen = false;
        $this->resetForm();
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
        $this->notes = '';
    }

    public function render()
    {
        // Query Assets with Relations
        $query = Asset::with(['category', 'location']);

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

        // Dropdown Filters
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

        // Stats Dashboard Metrics
        $stats = [
            'total' => Asset::count(),
            'good' => Asset::where('condition', 'baik')->where('status', 'aktif')->count(),
            'need_attention' => Asset::where('condition', '!=', 'baik')->orWhere('status', '!=', 'aktif')->count(),
            'total_value' => Asset::sum('purchase_cost'),
        ];

        return view('livewire.asset-manager', [
            'assets' => $assets,
            'stats' => $stats,
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
        ])->layout('layouts.app');
    }
}
