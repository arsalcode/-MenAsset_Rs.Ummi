<div class="w-full">
    <!-- ================= KOP SURAT CETAK (Hanya Muncul Saat Print / window.print()) ================= -->
    <div class="print-only p-8 mb-6 border-b-2 border-slate-900">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-lg bg-emerald-800 text-white text-3xl font-bold flex items-center justify-center">
                    <i class="ri-hospital-fill"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">RUMAH SAKIT UMMI BOGOR</h1>
                    <p class="text-xs text-slate-600">Jl. Empang No. 2, Kota Bogor, Jawa Barat | Telp: (0251) 8341600</p>
                    <p class="text-xs font-semibold text-emerald-800">INSTALASI PEMELIHARAAN SARANA RUMAH SAKIT (IPSRS)</p>
                </div>
            </div>
            <div class="text-right text-xs text-slate-600">
                <p><strong>DOKUMEN INVENTARIS RESMI</strong></p>
                <p>Tanggal Cetak: {{ now()->format('d F Y') }}</p>
            </div>
        </div>
        <div class="mt-4 text-center">
            <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide">REKAPITULASI DATA INVENTARIS ASET & ALAT KESEHATAN</h2>
        </div>
    </div>

    <!-- ================= SUB-NAV ADMIN TABS (PERSIS SEPERTI GAMBAR 2 ALKAMART) ================= -->
    <div class="no-print admin-tab-nav">
        <!-- Tab 1: Kelola Aset -->
        <button 
            wire:click="setTab('tabAssets')" 
            class="admin-tab-btn {{ $activeTab === 'tabAssets' ? 'active' : '' }}" 
            title="Kelola Aset"
        >
            <i class="ri-box-3-line"></i>
            <span class="tab-label">Kelola Aset</span>
        </button>

        <!-- Tab 2: Perlu Servis & Kalibrasi (Ada Badge Alert Count) -->
        <button 
            wire:click="setTab('tabMaintenance')" 
            class="admin-tab-btn {{ $activeTab === 'tabMaintenance' ? 'active' : '' }}" 
            title="Perlu Servis & Kalibrasi"
        >
            <i class="ri-error-warning-line"></i>
            <span class="tab-label">Restok & Servis</span>
            @if ($stats['need_attention'] > 0)
                <span class="badge-alert-count">{{ $stats['need_attention'] }}</span>
            @endif
        </button>

        <!-- Tab 3: Rekapitulasi Nilai Aset -->
        <button 
            wire:click="setTab('tabReports')" 
            class="admin-tab-btn {{ $activeTab === 'tabReports' ? 'active' : '' }}" 
            title="Rekap Pendapatan / Nilai Aset"
        >
            <i class="ri-bar-chart-grouped-line"></i>
            <span class="tab-label">Rekap Pendapatan</span>
        </button>

        <!-- Tab 4: Log Pemeliharaan -->
        <button 
            wire:click="setTab('tabLogs')" 
            class="admin-tab-btn {{ $activeTab === 'tabLogs' ? 'active' : '' }}" 
            title="Riwayat Servis & Kalibrasi"
        >
            <i class="ri-history-line"></i>
            <span class="tab-label">Riwayat Transaksi Servis</span>
        </button>
    </div>

    <!-- Container Konten Utama -->
    <div class="p-6 space-y-6">
        <!-- Flash Alert Notification -->
        @if ($feedbackMessage)
            <div class="p-4 rounded-xl flex items-center justify-between border shadow-xs transition-all {{ $feedbackType === 'danger' ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-emerald-50 border-emerald-200 text-emerald-900' }}">
                <div class="flex items-center gap-3">
                    <i class="{{ $feedbackType === 'danger' ? 'ri-error-warning-fill text-rose-600' : 'ri-checkbox-circle-fill text-emerald-600' }} text-xl"></i>
                    <span class="text-sm font-semibold">{{ $feedbackMessage }}</span>
                </div>
                <button wire:click="$set('feedbackMessage', null)" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>
        @endif



        <!-- ========================================================================= -->
        <!-- TAB 1 & TAB 2: Kelola Aset & Filter Alat Butuh Servis -->
        <!-- ========================================================================= -->
        @if ($activeTab === 'tabAssets' || $activeTab === 'tabMaintenance')

            @if ($activeTab === 'tabMaintenance')
                <!-- Alert Box Khusus Tab Restok / Perlu Kalibrasi (Khas Alkamart) -->
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-900 flex items-start gap-3">
                    <i class="ri-error-warning-fill text-amber-600 text-xl shrink-0 mt-0.5"></i>
                    <div class="text-xs">
                        <strong class="font-bold block text-sm">Pemberitahuan Kalibrasi & Pemeliharaan Alat Medis:</strong>
                        Daftar di bawah menyaring alat kesehatan dan peralatan rumah sakit yang berstatus <strong>Perlu Kalibrasi</strong>, <strong>Rusak Ringan</strong>, atau <strong>Rusak Berat</strong>. Segera jadwalkan teknisi IPSRS atau pihak BPFK agar alat siap operasional kembali.
                    </div>
                </div>
            @endif

            <!-- ================= UNIFIED TOOLBAR & CATEGORY FILTER (Khas Alkamart) ================= -->
            <div class="no-print bg-white rounded-xl border border-slate-200 shadow-xs divide-y divide-slate-100">
                <!-- Baris 1: Filter Kategori Cepat (Pills Ringkas & Elegan) -->
                <div class="px-4 py-3 bg-slate-50/70 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-2 overflow-x-auto scrollbar-none py-0.5">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5 mr-1 shrink-0">
                            <i class="ri-filter-3-line text-emerald-700"></i> Kategori:
                        </span>

                        <!-- Pill: Semua -->
                        <button 
                            wire:click="selectCategoryPill('')" 
                            class="h-8 px-3.5 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 transition-all shrink-0 {{ empty($filterCategory) ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:border-emerald-700 hover:text-emerald-700 hover:bg-emerald-50/40' }}"
                        >
                            <i class="ri-apps-2-line"></i>
                            <span>Semua</span>
                            <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-mono {{ empty($filterCategory) ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-100 text-slate-600' }}">
                                {{ $stats['total'] }}
                            </span>
                        </button>

                        <!-- Mapping Nama Kategori Ringkas agar Rapi -->
                        @php
                            $shortCategoryNames = [
                                'MED' => 'Alat Medis',
                                'RAWAT' => 'Rawat Inap',
                                'LAB' => 'Laboratorium',
                                'IT' => 'IT & SIMRS',
                            ];
                        @endphp

                        <!-- Pills Kategori dari Database -->
                        @foreach ($categories as $cat)
                            <button 
                                wire:click="selectCategoryPill('{{ $cat->id }}')" 
                                title="{{ $cat->name }}"
                                class="h-8 px-3.5 rounded-full text-xs font-semibold inline-flex items-center gap-1.5 transition-all shrink-0 whitespace-nowrap {{ $filterCategory == $cat->id ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:border-emerald-700 hover:text-emerald-700 hover:bg-emerald-50/40' }}"
                            >
                                @if ($cat->code === 'MED')
                                    <i class="ri-heart-pulse-line"></i>
                                @elseif ($cat->code === 'RAWAT')
                                    <i class="ri-hotel-bed-line"></i>
                                @elseif ($cat->code === 'LAB')
                                    <i class="ri-microscope-line"></i>
                                @else
                                    <i class="ri-computer-line"></i>
                                @endif
                                <span>{{ $shortCategoryNames[$cat->code] ?? $cat->name }}</span>
                                <span class="ml-0.5 px-1.5 py-0.5 rounded-full text-[10px] font-mono {{ $filterCategory == $cat->id ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $cat->assets_count }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Tombol Reset Cepat Bila Filter Aktif -->
                    @if ($search || $filterCategory || $filterLocation || $filterCondition || $filterStatus)
                        <button 
                            wire:click="resetFilters" 
                            class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200/60 inline-flex items-center gap-1 transition-all shrink-0 ml-auto"
                            title="Reset Semua Filter"
                        >
                            <i class="ri-refresh-line"></i>
                            <span>Reset Filter</span>
                        </button>
                    @endif
                </div>

                <!-- Baris 2: Pencarian Lebar, Filter Ruangan & Kondisi, serta Tambah Aset -->
                <div class="p-3.5 flex flex-col md:flex-row md:items-center gap-3">
                    <!-- Search Input Fleksibel (Mengisi Area Kosong Secara Proporsional) -->
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="ri-search-line"></i>
                        </span>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Cari kode aset, nama alat medis, merk, nomor seri..." 
                            class="w-full pl-9 pr-9 py-2 text-xs bg-slate-50 hover:bg-slate-100/60 focus:bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-700 transition-all font-medium text-slate-800 placeholder:text-slate-400"
                        />
                        @if ($search)
                            <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="ri-close-line"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Filter Dropdowns & Tombol Aksi Tambah Aset -->
                    <div class="flex items-center gap-2.5 shrink-0 flex-wrap sm:flex-nowrap">
                        <select wire:model.live="filterLocation" class="px-3 py-2 text-xs bg-slate-50 hover:bg-slate-100/60 focus:bg-white border border-slate-200 rounded-lg text-slate-700 font-medium focus:outline-none focus:border-emerald-700 transition-colors cursor-pointer min-w-[135px]">
                            <option value="">Semua Ruangan</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="filterCondition" class="px-3 py-2 text-xs bg-slate-50 hover:bg-slate-100/60 focus:bg-white border border-slate-200 rounded-lg text-slate-700 font-medium focus:outline-none focus:border-emerald-700 transition-colors cursor-pointer min-w-[130px]">
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Kondisi Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>

                        <!-- Tombol Tambah Barang (Hijau Emerald Khas Alkamart) -->
                        <button wire:click="openCreateModal" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 shadow-xs hover:shadow transition-all shrink-0">
                            <i class="ri-add-circle-line text-sm"></i>
                            <span>Tambah Aset</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Custom Data Table (Identik .custom-table Alkamart) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <!-- Table Header Hijau Lembut (#E9FAF3) Khas Alkamart -->
                        <thead class="bg-[#E9FAF3] border-b border-[#c7f0db] text-xs font-bold text-[#064E3B] uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-4 cursor-pointer hover:text-emerald-950" wire:click="sortBy('asset_code')">
                                    <div class="flex items-center gap-1">
                                        <span>Kode Aset</span>
                                        @if ($sortField === 'asset_code')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="py-3.5 px-4 cursor-pointer hover:text-emerald-950" wire:click="sortBy('name')">
                                    <div class="flex items-center gap-1">
                                        <span>Nama Alat Medis</span>
                                        @if ($sortField === 'name')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Ruangan RS</th>
                                <th class="py-3.5 px-4">Kondisi</th>
                                <th class="py-3.5 px-4 cursor-pointer hover:text-emerald-950" wire:click="sortBy('purchase_cost')">
                                    <div class="flex items-center gap-1">
                                        <span>Harga Perolehan</span>
                                        @if ($sortField === 'purchase_cost')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="py-3.5 px-4 text-right no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @forelse ($assets as $asset)
                                <tr class="hover:bg-[#F8FAF8] transition-colors">
                                    <!-- Kode Aset -->
                                    <td class="py-3.5 px-4 font-mono font-semibold whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ $asset->asset_code }}
                                        </span>
                                    </td>

                                    <!-- Nama Alat & Spesifikasi -->
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-900 text-[13px]">{{ $asset->name }}</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">
                                            {{ $asset->brand ?? '-' }} {{ $asset->model_type ? '· ' . $asset->model_type : '' }}
                                            @if ($asset->serial_number)
                                                <span class="font-mono text-slate-400">· SN: {{ $asset->serial_number }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Kategori -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700">
                                            {{ $asset->category->name ?? '-' }}
                                        </span>
                                    </td>

                                    <!-- Ruangan Penempatan -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="font-semibold text-slate-800">{{ $asset->location->name ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $asset->location->building ?? '' }} · {{ $asset->location->floor ?? '' }}</div>
                                    </td>

                                    <!-- Kondisi -->
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if ($asset->condition === 'baik')
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1">
                                                <span class="size-1.5 rounded-full bg-emerald-600"></span> Baik
                                            </span>
                                        @elseif ($asset->condition === 'rusak_ringan')
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1">
                                                <span class="size-1.5 rounded-full bg-amber-600"></span> Rusak Ringan
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-800 border border-rose-200 inline-flex items-center gap-1">
                                                <span class="size-1.5 rounded-full bg-rose-600"></span> Rusak Berat
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Harga Perolehan -->
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($asset->purchase_cost, 0, ',', '.') }}
                                    </td>

                                    <!-- Aksi Tombol -->
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap no-print">
                                        <div class="inline-flex items-center gap-1">
                                            <!-- Detail Button -->
                                            <button 
                                                wire:click="openDetailModal({{ $asset->id }})" 
                                                class="size-8 rounded-lg text-slate-600 hover:text-emerald-700 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 transition-colors inline-flex items-center justify-center"
                                                title="Lihat Detail & Histori Kalibrasi"
                                            >
                                                <i class="ri-eye-line text-sm"></i>
                                            </button>

                                            <!-- Cetak Label QR Button -->
                                            <button 
                                                wire:click="openPrintLabelModal({{ $asset->id }})" 
                                                class="size-8 rounded-lg text-slate-600 hover:text-blue-700 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 transition-colors inline-flex items-center justify-center"
                                                title="Cetak Stiker Label QR Aset"
                                            >
                                                <i class="ri-qr-code-line text-sm"></i>
                                            </button>

                                            <!-- Sunting Button -->
                                            <button 
                                                wire:click="openEditModal({{ $asset->id }})" 
                                                class="size-8 rounded-lg text-slate-600 hover:text-amber-700 hover:bg-amber-50 border border-slate-200 hover:border-amber-200 transition-colors inline-flex items-center justify-center"
                                                title="Sunting Data Aset"
                                            >
                                                <i class="ri-edit-line text-sm"></i>
                                            </button>

                                            <!-- Hapus Button -->
                                            <button 
                                                wire:click="confirmDelete({{ $asset->id }})" 
                                                class="size-8 rounded-lg text-slate-600 hover:text-rose-700 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 transition-colors inline-flex items-center justify-center"
                                                title="Hapus Aset"
                                            >
                                                <i class="ri-delete-bin-line text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 px-4 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="ri-inbox-line text-4xl text-slate-300 mb-2"></i>
                                            <p class="text-sm font-semibold text-slate-600">Tidak ada aset rumah sakit yang ditemukan.</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Silakan sesuaikan kata kunci atau bersihkan filter.</p>
                                            <button wire:click="resetFilters" class="mt-3 text-xs font-bold text-emerald-700 hover:underline">
                                                Bersihkan Filter
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Pagination Footer -->
                <div class="p-4 border-t border-slate-100 bg-slate-50/50 no-print">
                    {{ $assets->links() }}
                </div>
            </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: Rekapitulasi Nilai Aset (Financial Overview ala Alkamart Reports) -->
        <!-- ========================================================================= -->
        @elseif ($activeTab === 'tabReports')
            <div class="space-y-6">
                <!-- Action Row -->
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Rekapitulasi Nilai Aset & Beban Pemeliharaan RS</h2>
                        <p class="text-xs text-slate-500">Ringkasan finansial inventaris dan kapitalisasi alat kesehatan RS Ummi.</p>
                    </div>
                    <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 shadow-xs">
                        <i class="ri-printer-line text-sm"></i>
                        <span>Cetak Laporan Rekapitulasi</span>
                    </button>
                </div>

                <!-- ================= 4 REPORT CARDS HIJAU EMERALD (PERSIS GAMBAR 2 ALKAMART) ================= -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Card 1: Total Saldo / Total Inventaris Aset -->
                    <div class="report-card green">
                        <div class="report-icon">
                            <i class="ri-wallet-3-line"></i>
                        </div>
                        <div class="report-info">
                            <span class="report-label">Total Inventaris Aset RS</span>
                            <h3 class="report-amount">{{ $stats['total'] }} <span style="font-size: 13px; font-weight: 500; color: #64748B;">Unit</span></h3>
                            <div class="saldo-breakdown-tags">
                                <span class="tag-cash"><i class="ri-money-dollar-circle-fill"></i> Baik: <strong>{{ $stats['good'] }}</strong></span>
                                <span class="tag-qris"><i class="ri-qr-code-fill"></i> Servis: <strong>{{ $stats['need_attention'] }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Omzet / Aset Siap Operasional -->
                    <div class="report-card green">
                        <div class="report-icon">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div class="report-info">
                            <span class="report-label">Siap Operasional (Baik)</span>
                            <h3 class="report-amount" style="color: #047857;">{{ $stats['good'] }} <span style="font-size: 13px; font-weight: 500; color: #64748B;">Alat</span></h3>
                            <span class="report-sublabel">{{ $stats['total'] > 0 ? round(($stats['good'] / $stats['total']) * 100, 1) : 0 }}% alat laik pakai & prima</span>
                        </div>
                    </div>

                    <!-- Card 3: Laba Bersih / Perlu Kalibrasi -->
                    <div class="report-card green">
                        <div class="report-icon">
                            <i class="ri-funds-line"></i>
                        </div>
                        <div class="report-info">
                            <span class="report-label">Perlu Servis / Rusak</span>
                            <h3 class="report-amount" style="color: #d97706;">{{ $stats['need_attention'] }} <span style="font-size: 13px; font-weight: 500; color: #64748B;">Unit</span></h3>
                            <span class="report-sublabel">Butuh perhatian teknisi medis</span>
                        </div>
                    </div>

                    <!-- Card 4: Total Tarik Uang / Nilai Kapitalisasi Aset -->
                    <div class="report-card green">
                        <div class="report-icon">
                            <i class="ri-hand-coin-line"></i>
                        </div>
                        <div class="report-info">
                            <span class="report-label">Estimasi Nilai Inventaris</span>
                            <h3 class="report-amount" style="font-size: 19px;">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</h3>
                            <span class="report-sublabel">Kapitalisasi aset RS Ummi</span>
                        </div>
                    </div>
                </div>

                <!-- Rekap Data per Ruangan & Kategori -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tabel Sebaran per Kategori -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ri-pie-chart-line text-emerald-700"></i>
                            <span>Distribusi Aset Menurut Kategori</span>
                        </h4>
                        <div class="space-y-3">
                            @foreach ($categories as $c)
                                <div class="flex items-center justify-between text-xs border-b border-slate-100 pb-2">
                                    <span class="font-medium text-slate-700">{{ $c->name }}</span>
                                    <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-md">{{ $c->assets_count }} Unit</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tabel Sebaran per Ruangan RS -->
                    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="ri-hospital-line text-emerald-700"></i>
                            <span>Sebaran Alat di Ruangan RS Ummi</span>
                        </h4>
                        <div class="space-y-3">
                            @foreach ($locations as $l)
                                <div class="flex items-center justify-between text-xs border-b border-slate-100 pb-2">
                                    <div>
                                        <span class="font-medium text-slate-700 block">{{ $l->name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $l->building }}</span>
                                    </div>
                                    <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-md">{{ $l->assets_count }} Unit</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        <!-- ========================================================================= -->
        <!-- TAB 4: Log Pemeliharaan & Kalibrasi (Histori Servis Medis) -->
        <!-- ========================================================================= -->
        @elseif ($activeTab === 'tabLogs')
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Histori Lengkap Pemeliharaan & Kalibrasi Alat Medis</h2>
                        <p class="text-xs text-slate-500">Catatan riwayat uji fungsi berkala, kalibrasi BPFK, dan perbaikan perangkat.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-[#E9FAF3] border-b border-[#c7f0db] text-xs font-bold text-[#064E3B] uppercase">
                            <tr>
                                <th class="py-3.5 px-4">Tanggal Servis</th>
                                <th class="py-3.5 px-4">Alat & Kode Aset</th>
                                <th class="py-3.5 px-4">Ruangan</th>
                                <th class="py-3.5 px-4">Jenis Tindakan</th>
                                <th class="py-3.5 px-4">Teknisi / Vendor</th>
                                <th class="py-3.5 px-4">Biaya</th>
                                <th class="py-3.5 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-[#F8FAF8]">
                                    <td class="py-3 px-4 font-mono font-medium">{{ $log->maintenance_date->format('d M Y') }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900">{{ $log->asset->name }}</div>
                                        <span class="font-mono text-[11px] text-slate-500">{{ $log->asset->asset_code }}</span>
                                    </td>
                                    <td class="py-3 px-4">{{ $log->asset->location->name ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="font-semibold capitalize">{{ str_replace('_', ' ', $log->type) }}</span>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $log->notes }}</p>
                                    </td>
                                    <td class="py-3 px-4">{{ $log->vendor_technician ?? 'Internal RS' }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-slate-900">Rp {{ number_format($log->cost, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $log->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">Belum ada riwayat servis yang tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Kolom Tanda Tangan Cetak (Hanya Muncul Saat Print / window.print()) -->
    <div class="print-only mt-12 p-8 border-t border-slate-300">
        <div class="flex justify-between text-xs text-slate-800">
            <div class="text-center w-64">
                <p>Mengetahui,</p>
                <p class="font-bold mt-1">Direktur RS Ummi Bogor</p>
                <div class="h-20"></div>
                <p class="font-bold underline">( dr. H. Sugandi, Sp.OG )</p>
                <p class="text-[10px] text-slate-500">NIP. 19780512 200501 1 004</p>
            </div>
            <div class="text-center w-64">
                <p>Kota Bogor, {{ now()->format('d F Y') }}</p>
                <p class="font-bold mt-1">Kepala Bagian IPSRS & Inventaris</p>
                <div class="h-20"></div>
                <p class="font-bold underline">( Ir. Hendra Saputra, M.T. )</p>
                <p class="text-[10px] text-slate-500">NIP. 19840915 201001 1 012</p>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: Form Tambah / Sunting Aset (Create & Update) -->
    <!-- ========================================================================= -->
    @if ($isFormOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full overflow-hidden">
                <!-- Modal Header Hijau Emerald -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-[#E9FAF3]">
                    <div class="flex items-center gap-2.5">
                        <div class="size-8 rounded-lg bg-emerald-700 text-white flex items-center justify-center text-base">
                            <i class="{{ $selectedAssetId ? 'ri-edit-line' : 'ri-add-circle-line' }}"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#064E3B]">
                                {{ $selectedAssetId ? 'Sunting Data Aset Rumah Sakit' : 'Pendaftaran Aset Rumah Sakit Baru' }}
                            </h3>
                            <p class="text-[11px] text-emerald-800">Isi formulir kelengkapan inventaris aset RS Ummi dengan valid.</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <!-- Form Body -->
                <form wire:submit.prevent="save" class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kode Aset -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kode Aset RS <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="asset_code" placeholder="Contoh: MED-IGD-001" class="w-full px-3 py-2 border rounded-lg font-mono uppercase focus:outline-none focus:border-emerald-700 {{ $errors->has('asset_code') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}" />
                            @error('asset_code') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Aset -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Alat / Aset <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="name" placeholder="Contoh: Defibrillator Mindray D3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-emerald-700 {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}" />
                            @error('name') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kategori Aset <span class="text-rose-500">*</span></label>
                            <select wire:model="category_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-emerald-700 {{ $errors->has('category_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">
                                <option value="">Pilih Kategori...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Ruangan Penempatan -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Ruangan Penempatan RS <span class="text-rose-500">*</span></label>
                            <select wire:model="location_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-emerald-700 {{ $errors->has('location_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">
                                <option value="">Pilih Ruangan...</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->building }})</option>
                                @endforeach
                            </select>
                            @error('location_id') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Merk -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Merk / Brand</label>
                            <input type="text" wire:model="brand" placeholder="Contoh: Mindray, GE, Siemens" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-700" />
                        </div>

                        <!-- Tipe / Model -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tipe / Model</label>
                            <input type="text" wire:model="model_type" placeholder="Contoh: BeneHeart D3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-700" />
                        </div>

                        <!-- Serial Number -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nomor Seri (Serial Number)</label>
                            <input type="text" wire:model="serial_number" placeholder="Contoh: MR-DF-2023-091" class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono focus:outline-none focus:border-emerald-700" />
                        </div>

                        <!-- Tanggal Pengadaan -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tanggal Pengadaan</label>
                            <input type="date" wire:model="purchase_date" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-700" />
                        </div>

                        <!-- Harga Perolehan -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Harga Perolehan (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" wire:model="purchase_cost" class="w-full px-3 py-2 border rounded-lg font-mono focus:outline-none focus:border-emerald-700 {{ $errors->has('purchase_cost') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}" />
                            @error('purchase_cost') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kondisi -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kondisi Fisik & Fungsi <span class="text-rose-500">*</span></label>
                            <select wire:model="condition" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-700">
                                <option value="baik">Baik (Siap Operasional)</option>
                                <option value="rusak_ringan">Rusak Ringan (Perlu Kalibrasi/Perbaikan)</option>
                                <option value="rusak_berat">Rusak Berat (Tidak Berfungsi)</option>
                            </select>
                        </div>

                        <!-- Status Operasional -->
                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Status Operasional <span class="text-rose-500">*</span></label>
                            <select wire:model="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-700">
                                <option value="aktif">Aktif Digunakan</option>
                                <option value="pemeliharaan">Dalam Perbaikan / Kalibrasi</option>
                                <option value="afkir">Afkir / Dihapuskan</option>
                            </select>
                        </div>

                        <!-- Catatan -->
                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 mb-1">Catatan Tambahan Spesifikasi</label>
                            <textarea wire:model="notes" rows="2" placeholder="Catatan teknis alat atau petunjuk khusus..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-700"></textarea>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg shadow-xs">
                            <i class="ri-save-line text-sm"></i>
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-3xl w-full overflow-hidden">
                <!-- Detail Header Hijau Emerald -->
                <div class="px-6 py-4 border-b border-slate-200 bg-[#E9FAF3] flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold px-2.5 py-0.5 rounded-md bg-emerald-800 text-white">
                                {{ $selectedAsset->asset_code }}
                            </span>
                            @if ($selectedAsset->condition === 'baik')
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">Kondisi Baik</span>
                            @elseif ($selectedAsset->condition === 'rusak_ringan')
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">Rusak Ringan</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800">Rusak Berat</span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mt-1">{{ $selectedAsset->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $selectedAsset->brand ?? '-' }} · Model: {{ $selectedAsset->model_type ?? '-' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="openPrintLabelModal({{ $selectedAsset->id }})" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-800 bg-white border border-emerald-300 hover:bg-emerald-50 shadow-xs">
                            <i class="ri-qr-code-line text-sm"></i>
                            <span>Cetak Label Stiker</span>
                        </button>
                        <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1">
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Detail Body -->
                <div class="p-6 space-y-6 text-xs">
                    <!-- Grid Spesifikasi Lengkap -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <div>
                            <span class="text-slate-400 block font-medium">Kategori Aset</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $selectedAsset->category->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Nomor Seri (Serial Number)</span>
                            <span class="font-mono font-bold text-slate-800 mt-0.5 block">{{ $selectedAsset->serial_number ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Ruangan Penempatan</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $selectedAsset->location->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Gedung & Lantai</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $selectedAsset->location->building ?? '-' }} ({{ $selectedAsset->location->floor ?? '-' }})</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Penanggung Jawab (PIC)</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $selectedAsset->location->pic_name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Harga Perolehan</span>
                            <span class="font-mono font-bold text-slate-900 mt-0.5 block">Rp {{ number_format($selectedAsset->purchase_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if ($selectedAsset->notes)
                        <div class="p-3.5 rounded-xl bg-blue-50/60 border border-blue-200 text-blue-900">
                            <span class="font-bold block mb-1">Catatan Spesifikasi:</span>
                            <p>{{ $selectedAsset->notes }}</p>
                        </div>
                    @endif

                    <!-- Maintenance History Section -->
                    <div>
                        <div class="flex items-center justify-between mb-2.5">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="ri-history-line text-emerald-700 text-sm"></i>
                                <span>Histori Kalibrasi & Servis Terjadwal</span>
                            </h4>
                            <button wire:click="openAddLogModal({{ $selectedAsset->id }})" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-1">
                                <i class="ri-add-line"></i>
                                <span>+ Catat Servis / Kalibrasi</span>
                            </button>
                        </div>

                        @if ($selectedAsset->maintenanceLogs->isNotEmpty())
                            <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100">
                                @foreach ($selectedAsset->maintenanceLogs as $log)
                                    <div class="p-3 bg-white hover:bg-slate-50 flex items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-slate-900 capitalize">{{ str_replace('_', ' ', $log->type) }}</span>
                                                <span class="font-mono text-slate-400">· {{ $log->maintenance_date->format('d M Y') }}</span>
                                            </div>
                                            <p class="text-slate-600 mt-0.5">{{ $log->notes }}</p>
                                            <span class="text-[11px] text-slate-400">Teknisi: {{ $log->vendor_technician ?? 'Internal RS' }}</span>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="font-mono font-bold text-slate-900">Rp {{ number_format($log->cost, 0, ',', '.') }}</span>
                                            <span class="block text-[10px] font-bold uppercase {{ $log->status === 'selesai' ? 'text-emerald-700' : 'text-amber-700' }}">
                                                {{ $log->status }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 rounded-xl border border-dashed border-slate-200 text-center text-slate-400">
                                Belum ada riwayat kalibrasi atau pemeliharaan yang tercatat.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <button wire:click="closeModal" class="px-4 py-2 font-semibold text-slate-600 hover:bg-slate-200/60 rounded-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 4: Tambah Catatan Servis / Kalibrasi Baru (Inovasi) -->
    <!-- ========================================================================= -->
    @if ($isLogModalOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-md w-full overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-[#E9FAF3] flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-[#064E3B]">Catat Servis / Kalibrasi Baru</h3>
                        <p class="text-[11px] text-emerald-800">{{ $selectedAsset->name }} ({{ $selectedAsset->asset_code }})</p>
                    </div>
                    <button wire:click="$set('isLogModalOpen', false)" class="text-slate-400 hover:text-slate-600">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveLog" class="p-6 space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tanggal Tindakan</label>
                        <input type="date" wire:model="log_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-emerald-700 border-slate-300" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Jenis Tindakan</label>
                        <select wire:model="log_type" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-emerald-700 border-slate-300">
                            <option value="kalibrasi">Uji Kalibrasi Berkala</option>
                            <option value="pemeliharaan_rutin">Pemeliharaan Rutin / Servis Ringan</option>
                            <option value="perbaikan">Perbaikan Kerusakan Alat</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Biaya Pemeliharaan (Rp)</label>
                        <input type="number" wire:model="log_cost" class="w-full px-3 py-2 border rounded-lg font-mono focus:outline-none focus:border-emerald-700 border-slate-300" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Teknisi / Lembaga Penguji (BPFK)</label>
                        <input type="text" wire:model="log_technician" placeholder="Contoh: Balai Pengujian Fasilitas Kesehatan (BPFK)" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-emerald-700 border-slate-300" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Status Tindakan</label>
                        <select wire:model="log_status" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-emerald-700 border-slate-300">
                            <option value="selesai">Selesai (Laik Pakai)</option>
                            <option value="dalam_proses">Dalam Proses Tindakan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan Hasil Pemeriksaan</label>
                        <textarea wire:model="log_notes" rows="2" placeholder="Hasil pengujian toleransi atau suku cadang yang diganti..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-emerald-700 border-slate-300"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('isLogModalOpen', false)" class="px-3 py-1.5 font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-1.5 font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg shadow-xs">Simpan Catatan Servis</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 5: Cetak Stiker Label QR Code Aset (Inovasi Khas Alkamart) -->
    <!-- ========================================================================= -->
    @if ($isPrintLabelOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-sm w-full overflow-hidden text-center">
                <div class="px-6 py-4 border-b border-slate-200 bg-[#E9FAF3] flex items-center justify-between">
                    <h3 class="text-sm font-bold text-[#064E3B]">Stiker Label Inventaris RS</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Template Stiker Fisik RS Ummi -->
                    <div id="printableAssetTag" class="p-4 border-2 border-dashed border-emerald-800 rounded-xl bg-white text-left shadow-xs space-y-3">
                        <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                            <div class="size-8 rounded-md bg-emerald-800 text-white flex items-center justify-center text-lg">
                                <i class="ri-hospital-fill"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-extrabold text-emerald-900 leading-none">RS UMMI BOGOR</h4>
                                <span class="text-[9px] text-slate-500 uppercase tracking-wider">LABEL INVENTARIS RESMI</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- QR Code SVG Mockup -->
                            <div class="size-20 bg-slate-900 p-1.5 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-full h-full text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm10-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm14-2h4v4h-4v-4zm-4 0h2v2h-2v-2zm2 4h2v4h-2v-4zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                                </svg>
                            </div>
                            <div class="text-[11px] space-y-0.5">
                                <p class="font-mono font-bold text-slate-900 text-xs">{{ $selectedAsset->asset_code }}</p>
                                <p class="font-bold text-slate-800 line-clamp-1">{{ $selectedAsset->name }}</p>
                                <p class="text-slate-500 text-[10px]">Lokasi: <strong>{{ $selectedAsset->location->name ?? '-' }}</strong></p>
                                <p class="text-slate-500 text-[10px]">SN: {{ $selectedAsset->serial_number ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="pt-1 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400">
                            <span>Milik SIMRS RS Ummi</span>
                            <span>Jangan Dirusak / Dilepas</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2">
                    <button wire:click="closeModal" class="px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-200/60 rounded-lg">Tutup</button>
                    <button onclick="window.print()" class="inline-flex items-center gap-1 px-4 py-1.5 text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg shadow-xs">
                        <i class="ri-printer-line"></i>
                        <span>Cetak Stiker</span>
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-sm w-full p-6 text-center">
                <div class="size-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="ri-delete-bin-line"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Hapus Data Aset Ini?</h3>
                <p class="text-xs text-slate-500 mt-1.5">
                    Data alat medis <strong class="text-slate-800">{{ $selectedAsset->name }}</strong> ({{ $selectedAsset->asset_code }}) akan dihapus permanen dari inventaris.
                </p>
                <div class="mt-5 flex items-center justify-center gap-2">
                    <button wire:click="closeModal" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">
                        Batal
                    </button>
                    <button wire:click="delete" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-xs">
                        Ya, Hapus Data
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
