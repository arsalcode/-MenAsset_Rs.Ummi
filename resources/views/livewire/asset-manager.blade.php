<div class="space-y-8">
    <!-- Flash Notification Feedback -->
    @if ($feedbackMessage)
        <div class="p-4 rounded-xl flex items-center justify-between shadow-xs transition-all duration-300 {{ $feedbackType === 'danger' ? 'bg-rose-50 border border-rose-200 text-rose-800' : 'bg-emerald-50 border border-emerald-200 text-emerald-900' }}">
            <div class="flex items-center gap-3">
                @if ($feedbackType === 'danger')
                    <svg class="size-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                @else
                    <svg class="size-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
                <span class="text-sm font-medium">{{ $feedbackMessage }}</span>
            </div>
            <button wire:click="$set('feedbackMessage', null)" class="text-slate-400 hover:text-slate-600 p-1">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Metric / Stat Summary Cards (Desain Terinspirasi Proyek Manajer) -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1: Total Aset -->
        <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold tracking-wider text-slate-500 uppercase">Total Inventaris</p>
                    <h3 class="mt-1 font-bold text-2xl text-slate-900 font-mono">{{ $stats['total'] }} <span class="text-xs font-normal text-slate-500 font-sans">Unit</span></h3>
                </div>
                <div class="flex items-center justify-center size-11 rounded-xl bg-slate-100 text-slate-700">
                    <svg class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] text-slate-500 flex items-center gap-1.5">
                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                Tercatat di SIMRS RS Ummi
            </p>
        </div>

        <!-- Card 2: Siap Pakai / Baik -->
        <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold tracking-wider text-slate-500 uppercase">Siap Operasional</p>
                    <h3 class="mt-1 font-bold text-2xl text-emerald-600 font-mono">{{ $stats['good'] }} <span class="text-xs font-normal text-slate-500 font-sans">Alat</span></h3>
                </div>
                <div class="flex items-center justify-center size-11 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] text-slate-500 flex items-center gap-1.5">
                <span class="size-1.5 rounded-full bg-emerald-500"></span>
                Kondisi baik & fungsi optimal
            </p>
        </div>

        <!-- Card 3: Perlu Servis / Kalibrasi -->
        <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold tracking-wider text-slate-500 uppercase">Perlu Kalibrasi / Servis</p>
                    <h3 class="mt-1 font-bold text-2xl text-amber-600 font-mono">{{ $stats['need_attention'] }} <span class="text-xs font-normal text-slate-500 font-sans">Unit</span></h3>
                </div>
                <div class="flex items-center justify-center size-11 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] text-slate-500 flex items-center gap-1.5">
                <span class="size-1.5 rounded-full bg-amber-500"></span>
                Butuh perhatian teknisi medis
            </p>
        </div>

        <!-- Card 4: Total Nilai Aset -->
        <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold tracking-wider text-slate-500 uppercase">Estimasi Nilai Aset</p>
                    <h3 class="mt-1 font-bold text-xl text-slate-900 font-mono tracking-tight">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</h3>
                </div>
                <div class="flex items-center justify-center size-11 rounded-xl bg-blue-50 text-blue-600">
                    <svg class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-[11px] text-slate-500 flex items-center gap-1.5">
                <span class="size-1.5 rounded-full bg-blue-500"></span>
                Kapitalisasi inventaris RS Ummi
            </p>
        </div>
    </div>

    <!-- Main Table Card with Search & Action Header -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        <!-- Table Toolbar -->
        <div class="p-6 border-b border-slate-200/80 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Daftar Aset & Peralatan Medis</h2>
                    <p class="text-xs text-slate-500">Kelola informasi kelengkapan, lokasi ruangan, dan status operasional aset.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs transition-colors">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Aset Baru</span>
                    </button>
                </div>
            </div>

            <!-- Search & Filters Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-2">
                <!-- Live Search Bar -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari kode, nama alat, merk, atau nomor seri..." 
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                    />
                </div>

                <!-- Filter Kategori -->
                <div>
                    <select wire:model.live="filterCategory" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Ruangan / Lokasi -->
                <div>
                    <select wire:model.live="filterLocation" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="">Semua Ruangan</option>
                        @foreach ($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kondisi & Reset -->
                <div class="flex items-center gap-2">
                    <select wire:model.live="filterCondition" class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
                        <option value="">Semua Kondisi</option>
                        <option value="baik">Kondisi Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>

                    @if ($search || $filterCategory || $filterLocation || $filterCondition || $filterStatus)
                        <button wire:click="resetFilters" title="Reset Filter" class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl border border-slate-200">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table Display (Gaya Bersih & Rapi Manajer) -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 cursor-pointer hover:text-slate-800" wire:click="sortBy('asset_code')">
                            <div class="flex items-center gap-1.5">
                                <span>Kode Aset</span>
                                @if ($sortField === 'asset_code')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 cursor-pointer hover:text-slate-800" wire:click="sortBy('name')">
                            <div class="flex items-center gap-1.5">
                                <span>Nama Aset & Spesifikasi</span>
                                @if ($sortField === 'name')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Ruangan Penempatan</th>
                        <th class="px-6 py-4">Kondisi</th>
                        <th class="px-6 py-4 cursor-pointer hover:text-slate-800" wire:click="sortBy('purchase_cost')">
                            <div class="flex items-center gap-1.5">
                                <span>Harga Perolehan</span>
                                @if ($sortField === 'purchase_cost')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($assets as $asset)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Kode Aset -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 border border-slate-200/60">
                                    {{ $asset->asset_code }}
                                </span>
                            </td>

                            <!-- Nama Aset & Spesifikasi -->
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $asset->name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $asset->brand ?? '-' }} {{ $asset->model_type ? '· ' . $asset->model_type : '' }}
                                    @if ($asset->serial_number)
                                        <span class="font-mono text-[11px] text-slate-400">· SN: {{ $asset->serial_number }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700">
                                    {{ $asset->category->name ?? '-' }}
                                </span>
                            </td>

                            <!-- Lokasi Ruangan -->
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                <div class="font-medium text-slate-800">{{ $asset->location->name ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $asset->location->building ?? '' }} · {{ $asset->location->floor ?? '' }}</div>
                            </td>

                            <!-- Kondisi & Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($asset->condition === 'baik')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ● Baik
                                    </span>
                                @elseif ($asset->condition === 'rusak_ringan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        ▲ Rusak Ringan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        ✖ Rusak Berat
                                    </span>
                                @endif
                            </td>

                            <!-- Harga Perolehan -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-medium text-slate-800">
                                Rp {{ number_format($asset->purchase_cost, 0, ',', '.') }}
                            </td>

                            <!-- Action Buttons -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Detail Action -->
                                    <button 
                                        wire:click="openDetailModal({{ $asset->id }})" 
                                        class="px-2.5 py-1 text-xs font-medium text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors border border-transparent hover:border-emerald-200"
                                        title="Lihat Detail Kelengkapan Aset"
                                    >
                                        Detail
                                    </button>

                                    <!-- Edit Action -->
                                    <button 
                                        wire:click="openEditModal({{ $asset->id }})" 
                                        class="px-2.5 py-1 text-xs font-medium text-slate-700 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-200"
                                        title="Sunting Data Aset"
                                    >
                                        Sunting
                                    </button>

                                    <!-- Delete Action -->
                                    <button 
                                        wire:click="confirmDelete({{ $asset->id }})" 
                                        class="px-2.5 py-1 text-xs font-medium text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-200"
                                        title="Hapus Aset"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="size-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    <p class="text-sm font-medium text-slate-600">Tidak ada aset rumah sakit yang cocok.</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter yang dipilih.</p>
                                    <button wire:click="resetFilters" class="mt-3 text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                                        Reset Semua Filter
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Pagination Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $assets->links() }}
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: Form Tambah / Sunting Aset (Create & Update) -->
    <!-- ========================================================================= -->
    @if ($isFormOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-2xl w-full overflow-hidden transition-all">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">
                            {{ $selectedAssetId ? 'Sunting Data Aset Rumah Sakit' : 'Tambah Aset Rumah Sakit Baru' }}
                        </h3>
                        <p class="text-xs text-slate-500">Lengkapi formulir registrasi aset di bawah ini dengan benar.</p>
                    </div>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body (Form Fields) -->
                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kode Aset -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Aset RS <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                wire:model="asset_code" 
                                placeholder="Contoh: MED-IGD-001" 
                                class="w-full px-3.5 py-2 text-sm border rounded-xl font-mono uppercase focus:outline-none focus:ring-2 focus:ring-emerald-500/20 {{ $errors->has('asset_code') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}"
                            />
                            @error('asset_code') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Aset -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Alat / Aset <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                wire:model="name" 
                                placeholder="Contoh: Defibrillator Mindray D3" 
                                class="w-full px-3.5 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}"
                            />
                            @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Aset <span class="text-rose-500">*</span></label>
                            <select wire:model="category_id" class="w-full px-3.5 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 {{ $errors->has('category_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">
                                <option value="">Pilih Kategori...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lokasi Ruangan -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Ruangan Penempatan <span class="text-rose-500">*</span></label>
                            <select wire:model="location_id" class="w-full px-3.5 py-2 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 {{ $errors->has('location_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">
                                <option value="">Pilih Ruangan...</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->building }})</option>
                                @endforeach
                            </select>
                            @error('location_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Merk / Pabrikan -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Merk / Brand</label>
                            <input type="text" wire:model="brand" placeholder="Contoh: Mindray, GE, Siemens" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                        </div>

                        <!-- Tipe / Model -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe / Model</label>
                            <input type="text" wire:model="model_type" placeholder="Contoh: BeneHeart D3" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                        </div>

                        <!-- Nomor Seri -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Seri (Serial Number)</label>
                            <input type="text" wire:model="serial_number" placeholder="Contoh: MR-DF-2023-091" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                        </div>

                        <!-- Tanggal Pengadaan -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Pengadaan</label>
                            <input type="date" wire:model="purchase_date" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20" />
                        </div>

                        <!-- Harga Perolehan -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Harga Perolehan (Rp) <span class="text-rose-500">*</span></label>
                            <input 
                                type="number" 
                                wire:model="purchase_cost" 
                                placeholder="0" 
                                class="w-full px-3.5 py-2 text-sm border rounded-xl font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500/20 {{ $errors->has('purchase_cost') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}"
                            />
                            @error('purchase_cost') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kondisi Aset -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kondisi Fisik & Fungsi <span class="text-rose-500">*</span></label>
                            <select wire:model="condition" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                <option value="baik">Baik (Siap Pakai)</option>
                                <option value="rusak_ringan">Rusak Ringan (Bisa Digunakan Sebagian)</option>
                                <option value="rusak_berat">Rusak Berat (Tidak Berfungsi)</option>
                            </select>
                        </div>

                        <!-- Status Operasional -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Status Operasional <span class="text-rose-500">*</span></label>
                            <select wire:model="status" class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                                <option value="aktif">Aktif Digunakan</option>
                                <option value="pemeliharaan">Dalam Perbaikan / Kalibrasi</option>
                                <option value="afkir">Afkir / Rekomendasi Pemusnahan</option>
                            </select>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan / Spesifikasi Khusus</label>
                            <textarea wire:model="notes" rows="2" placeholder="Tuliskan catatan teknis atau histori singkat aset..." class="w-full px-3.5 py-2 text-sm border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20"></textarea>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition-colors">
                            <span wire:loading.remove wire:target="save">Simpan Data Aset</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 2: Detail Kelengkapan Aset & Histori Kalibrasi (Read Detail) -->
    <!-- ========================================================================= -->
    @if ($isDetailOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-3xl w-full overflow-hidden">
                <!-- Header Detail -->
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold px-2.5 py-0.5 rounded-md bg-emerald-100 text-emerald-800 border border-emerald-200">
                                {{ $selectedAsset->asset_code }}
                            </span>
                            @if ($selectedAsset->condition === 'baik')
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Kondisi Baik</span>
                            @elseif ($selectedAsset->condition === 'rusak_ringan')
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Rusak Ringan</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Rusak Berat</span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $selectedAsset->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $selectedAsset->brand ?? '-' }} · Model: {{ $selectedAsset->model_type ?? '-' }}</p>
                    </div>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body Detail -->
                <div class="p-6 space-y-6">
                    <!-- Specification Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-xs">
                        <div>
                            <span class="text-slate-400 block font-medium">Kategori</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">{{ $selectedAsset->category->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Nomor Seri (Serial Number)</span>
                            <span class="font-mono font-semibold text-slate-800 mt-0.5 block">{{ $selectedAsset->serial_number ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Ruangan Penempatan</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">{{ $selectedAsset->location->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Gedung & Lantai</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">{{ $selectedAsset->location->building ?? '-' }} ({{ $selectedAsset->location->floor ?? '-' }})</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Penanggung Jawab (PIC)</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">{{ $selectedAsset->location->pic_name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Harga Perolehan</span>
                            <span class="font-mono font-bold text-slate-900 mt-0.5 block">Rp {{ number_format($selectedAsset->purchase_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if ($selectedAsset->notes)
                        <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-200/60 text-xs">
                            <span class="font-bold text-blue-900 block mb-1">Catatan Teknis Aset:</span>
                            <p class="text-blue-800 leading-relaxed">{{ $selectedAsset->notes }}</p>
                        </div>
                    @endif

                    <!-- Maintenance History (Inovasi Rekam Medis Alat RS) -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                <span>Histori Kalibrasi & Servis Terjadwal</span>
                                <span class="px-2 py-0.5 text-[10px] font-mono bg-slate-100 text-slate-600 rounded-md">
                                    {{ $selectedAsset->maintenanceLogs->count() }} Catatan
                                </span>
                            </h4>
                        </div>

                        @if ($selectedAsset->maintenanceLogs->isNotEmpty())
                            <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100">
                                @foreach ($selectedAsset->maintenanceLogs as $log)
                                    <div class="p-3.5 bg-white hover:bg-slate-50/60 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $log->type) }}</span>
                                                <span class="font-mono text-slate-400">· {{ $log->maintenance_date->format('d M Y') }}</span>
                                            </div>
                                            <p class="text-slate-500 mt-0.5">{{ $log->notes }}</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Oleh: {{ $log->vendor_technician ?? 'Teknisi RS' }}</p>
                                        </div>
                                        <div class="text-right sm:shrink-0">
                                            <span class="font-mono font-semibold text-slate-800">Rp {{ number_format($log->cost, 0, ',', '.') }}</span>
                                            <span class="block text-[10px] uppercase font-bold {{ $log->status === 'selesai' ? 'text-emerald-600' : 'text-amber-600' }}">
                                                {{ $log->status }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 rounded-xl border border-dashed border-slate-200 text-center text-xs text-slate-400">
                                Belum ada riwayat kalibrasi atau pemeliharaan yang tercatat untuk alat ini.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Detail -->
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <button wire:click="closeModal" class="px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200/80 rounded-xl transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 3: Konfirmasi Hapus Data Aset (Delete Confirmation) -->
    <!-- ========================================================================= -->
    @if ($isDeleteOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full p-6 text-center">
                <div class="size-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Hapus Data Aset Ini?</h3>
                <p class="text-xs text-slate-500 mt-2">
                    Apakah Anda yakin ingin menghapus data aset <strong class="text-slate-800">{{ $selectedAsset->name }}</strong> ({{ $selectedAsset->asset_code }})?
                </p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <button wire:click="closeModal" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button wire:click="delete" class="px-5 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs transition-colors">
                        Ya, Hapus Data
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
