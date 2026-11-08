<div class="w-full">
    <!-- ================= KOP SURAT CETAK (Hanya Muncul Saat Print / window.print()) ================= -->
    <div class="print-only p-8 mb-6 border-b-2 border-slate-900">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="size-16 rounded-lg bg-emerald-800 text-white text-3xl font-bold flex items-center justify-center">
                    <i data-lucide="hospital" class="size-8"></i>
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
            <i data-lucide="package" class="size-[18px]"></i>
            <span class="tab-label">Kelola Aset</span>
        </button>

        <!-- Tab 2: Servis & Kalibrasi (Ada Badge Alert Count) -->
        <button 
            wire:click="setTab('tabMaintenance')" 
            class="admin-tab-btn {{ $activeTab === 'tabMaintenance' ? 'active' : '' }}" 
            title="Servis & Kalibrasi Alat Medis"
        >
            <i data-lucide="triangle-alert" class="size-[18px]"></i>
            <span class="tab-label">Servis & Kalibrasi</span>
            @if ($stats['need_attention'] > 0)
                <span class="badge-alert-count">{{ $stats['need_attention'] }}</span>
            @endif
        </button>

        <!-- Tab 3: Rekap Nilai Aset -->
        <button 
            wire:click="setTab('tabReports')" 
            class="admin-tab-btn {{ $activeTab === 'tabReports' ? 'active' : '' }}" 
            title="Rekapitulasi Nilai Aset"
        >
            <i data-lucide="bar-chart-3" class="size-[18px]"></i>
            <span class="tab-label">Rekap Nilai Aset</span>
        </button>

        <!-- Tab 4: Log Riwayat Servis -->
        <button 
            wire:click="setTab('tabLogs')" 
            class="admin-tab-btn {{ $activeTab === 'tabLogs' ? 'active' : '' }}" 
            title="Riwayat Servis & Kalibrasi"
        >
            <i data-lucide="history" class="size-[18px]"></i>
            <span class="tab-label">Riwayat Servis</span>
        </button>
    </div>

    <!-- Container Konten Utama (Responsif di HP) -->
    <div class="p-3 sm:p-5 md:p-6 space-y-4 sm:space-y-6">
        <!-- Flash Alert Notification -->
        @if ($feedbackMessage)
            <div class="p-4 rounded-xl flex items-center justify-between border shadow-xs transition-all {{ $feedbackType === 'danger' ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900/60 text-rose-800 dark:text-rose-200' : 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900/60 text-emerald-900 dark:text-emerald-200' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="{{ $feedbackType === 'danger' ? 'alert-circle' : 'check-circle-2' }}" class="size-5 {{ $feedbackType === 'danger' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}"></i>
                    <span class="text-sm font-semibold">{{ $feedbackMessage }}</span>
                </div>
                <button wire:click="$set('feedbackMessage', null)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                    <i data-lucide="x" class="size-4.5"></i>
                </button>
            </div>
        @endif



        <!-- ========================================================================= -->
        <!-- TAB 1 & TAB 2: Kelola Aset & Filter Alat Butuh Servis -->
        <!-- ========================================================================= -->
        @if ($activeTab === 'tabAssets' || $activeTab === 'tabMaintenance')

            @if ($activeTab === 'tabMaintenance')
                <!-- Alert Banner Khusus Tab Servis & Kalibrasi (Kompak & Elegan 1 Baris Slim) -->
                <div class="mb-3.5 px-3 py-1.5 rounded-lg bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/60 text-amber-900 dark:text-amber-200 flex items-center justify-between gap-2.5 text-[11px] shadow-2xs">
                    <div class="flex items-center gap-2">
                        <i data-lucide="triangle-alert" class="size-3.5 text-amber-600 dark:text-amber-400 shrink-0"></i>
                        <span><strong>Perhatian:</strong> Menampilkan alat medis butuh kalibrasi atau perbaikan segera.</span>
                    </div>
                    <span class="font-mono text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-200/60 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 shrink-0">
                        {{ $stats['need_attention'] }} Alat
                    </span>
                </div>
            @endif

            <!-- ================= ADMIN ACTION BAR (SEJAJAR 1 BARIS / RESPONSIF HP) ================= -->
            <div class="no-print mb-4 flex flex-col xl:flex-row xl:items-center gap-2 sm:gap-2.5">
                <!-- Group 1 (Kiri): 3 Dropdown Filter Modern (Custom Alpine.js Popover) -->
                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0 flex-wrap sm:flex-nowrap">
                    <!-- Dropdown Kategori -->
                    @php
                        $selectedCategoryName = $categories->firstWhere('id', $filterCategory)?->name ?? 'Semua Kategori';
                    @endphp
                    <div x-data="{ open: false }" class="relative" @click.outside="open = false" @keydown.escape.window="open = false">
                        <button 
                            type="button" 
                            @click="open = !open" 
                            :class="open ? 'ring-2 ring-emerald-500/50 border-emerald-500 dark:border-emerald-200' : ''"
                            class="h-8.5 sm:h-10 pl-3 sm:pl-3.5 pr-7 sm:pr-8 text-[11px] sm:text-xs font-semibold rounded-xl border transition-all cursor-pointer inline-flex items-center gap-1.5 sm:gap-2 select-none shadow-xs {{ $filterCategory ? 'bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-500/50 text-emerald-800 dark:text-emerald-300' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-200 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                        >
                            <span class="truncate max-w-[120px] sm:max-w-[150px]">{{ $selectedCategoryName }}</span>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 sm:pr-3 text-slate-400 dark:text-slate-400">
                                <span class="size-3.5 sm:size-4 inline-flex items-center justify-center transition-transform duration-200 origin-center" :class="open ? 'rotate-180' : ''">
                                    <i data-lucide="chevron-down" class="size-3.5 sm:size-4"></i>
                                </span>
                            </div>
                        </button>

                        <!-- Custom Popover Menu -->
                        <div 
                            x-show="open" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            class="absolute left-0 top-full mt-1.5 w-64 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-slate-950/60 p-1.5 z-50 space-y-0.5"
                        >
                            <button 
                                type="button" 
                                wire:click="$set('filterCategory', '')" 
                                @click="open = false" 
                                class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ $filterCategory === '' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                            >
                                <span>Semua Kategori</span>
                                @if ($filterCategory === '')
                                    <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                @endif
                            </button>

                            @foreach ($categories as $cat)
                                <button 
                                    type="button" 
                                    wire:click="$set('filterCategory', '{{ $cat->id }}')" 
                                    @click="open = false" 
                                    class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ (string)$filterCategory === (string)$cat->id ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                                >
                                    <span class="truncate pr-2">{{ $cat->name }}</span>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">{{ $cat->assets_count }} Unit</span>
                                        @if ((string)$filterCategory === (string)$cat->id)
                                            <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Dropdown Ruangan -->
                    @php
                        $selectedLocationName = $locations->firstWhere('id', $filterLocation)?->name ?? 'Semua Ruangan';
                    @endphp
                    <div x-data="{ open: false }" class="relative" @click.outside="open = false" @keydown.escape.window="open = false">
                        <button 
                            type="button" 
                            @click="open = !open" 
                            :class="open ? 'ring-2 ring-emerald-500/50 border-emerald-500 dark:border-emerald-200' : ''"
                            class="h-8.5 sm:h-10 pl-3 sm:pl-3.5 pr-7 sm:pr-8 text-[11px] sm:text-xs font-semibold rounded-xl border transition-all cursor-pointer inline-flex items-center gap-1.5 sm:gap-2 select-none shadow-xs {{ $filterLocation ? 'bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-500/50 text-emerald-800 dark:text-emerald-300' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-200 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                        >
                            <span class="truncate max-w-[120px] sm:max-w-[150px]">{{ $selectedLocationName }}</span>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 sm:pr-3 text-slate-400 dark:text-slate-400">
                                <span class="size-3.5 sm:size-4 inline-flex items-center justify-center transition-transform duration-200 origin-center" :class="open ? 'rotate-180' : ''">
                                    <i data-lucide="chevron-down" class="size-3.5 sm:size-4"></i>
                                </span>
                            </div>
                        </button>

                        <!-- Custom Popover Menu -->
                        <div 
                            x-show="open" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            class="absolute left-0 top-full mt-1.5 w-72 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-slate-950/60 p-1.5 z-50 space-y-0.5"
                        >
                            <button 
                                type="button" 
                                wire:click="$set('filterLocation', '')" 
                                @click="open = false" 
                                class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ $filterLocation === '' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                            >
                                <span>Semua Ruangan</span>
                                @if ($filterLocation === '')
                                    <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                @endif
                            </button>

                            @foreach ($locations as $loc)
                                <button 
                                    type="button" 
                                    wire:click="$set('filterLocation', '{{ $loc->id }}')" 
                                    @click="open = false" 
                                    class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ (string)$filterLocation === (string)$loc->id ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                                >
                                    <div class="pr-2 min-w-0">
                                        <span class="block truncate">{{ $loc->name }}</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 block -mt-0.5">{{ $loc->building }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">{{ $loc->assets_count }} Unit</span>
                                        @if ((string)$filterLocation === (string)$loc->id)
                                            <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Dropdown Kondisi -->
                    @php
                        $conditionOptions = [
                            '' => 'Semua Kondisi',
                            'baik' => 'Kondisi Baik',
                            'rusak_ringan' => 'Rusak Ringan',
                            'rusak_berat' => 'Rusak Berat'
                        ];
                        $selectedConditionLabel = $conditionOptions[$filterCondition] ?? 'Semua Kondisi';
                    @endphp
                    <div x-data="{ open: false }" class="relative" @click.outside="open = false" @keydown.escape.window="open = false">
                        <button 
                            type="button" 
                            @click="open = !open" 
                            :class="open ? 'ring-2 ring-emerald-500/50 border-emerald-500 dark:border-emerald-200' : ''"
                            class="h-8.5 sm:h-10 pl-3 sm:pl-3.5 pr-7 sm:pr-8 text-[11px] sm:text-xs font-semibold rounded-xl border transition-all cursor-pointer inline-flex items-center gap-1.5 sm:gap-2 select-none shadow-xs {{ $filterCondition ? 'bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-500/50 text-emerald-800 dark:text-emerald-300' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-200 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                        >
                            @if ($filterCondition === 'baik')
                                <span class="size-1.5 sm:size-2 rounded-full bg-emerald-500"></span>
                            @elseif ($filterCondition === 'rusak_ringan')
                                <span class="size-1.5 sm:size-2 rounded-full bg-amber-500"></span>
                            @elseif ($filterCondition === 'rusak_berat')
                                <span class="size-1.5 sm:size-2 rounded-full bg-rose-500"></span>
                            @endif
                            <span class="truncate max-w-[100px] sm:max-w-[130px]">{{ $selectedConditionLabel }}</span>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 sm:pr-3 text-slate-400 dark:text-slate-400">
                                <span class="size-3.5 sm:size-4 inline-flex items-center justify-center transition-transform duration-200 origin-center" :class="open ? 'rotate-180' : ''">
                                    <i data-lucide="chevron-down" class="size-3.5 sm:size-4"></i>
                                </span>
                            </div>
                        </button>

                        <!-- Custom Popover Menu -->
                        <div 
                            x-show="open" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            class="absolute left-0 top-full mt-1.5 w-52 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-slate-950/60 p-1.5 z-50 space-y-0.5"
                        >
                            <button 
                                type="button" 
                                wire:click="$set('filterCondition', '')" 
                                @click="open = false" 
                                class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ $filterCondition === '' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                            >
                                <span>Semua Kondisi</span>
                                @if ($filterCondition === '')
                                    <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                @endif
                            </button>

                            <button 
                                type="button" 
                                wire:click="$set('filterCondition', 'baik')" 
                                @click="open = false" 
                                class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ $filterCondition === 'baik' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                    <span>Kondisi Baik</span>
                                </div>
                                @if ($filterCondition === 'baik')
                                    <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                @endif
                            </button>

                            <button 
                                type="button" 
                                wire:click="$set('filterCondition', 'rusak_ringan')" 
                                @click="open = false" 
                                class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ $filterCondition === 'rusak_ringan' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full bg-amber-500"></span>
                                    <span>Rusak Ringan</span>
                                </div>
                                @if ($filterCondition === 'rusak_ringan')
                                    <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                @endif
                            </button>

                            <button 
                                type="button" 
                                wire:click="$set('filterCondition', 'rusak_berat')" 
                                @click="open = false" 
                                class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ $filterCondition === 'rusak_berat' ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="size-2 rounded-full bg-rose-500"></span>
                                    <span>Rusak Berat</span>
                                </div>
                                @if ($filterCondition === 'rusak_berat')
                                    <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Group 2 (Tengah & Kanan): Search Input Fleksibel + Tombol Aksi Icon Murni (Responsif di HP) -->
                <div class="flex items-center gap-1.5 sm:gap-2 flex-1 w-full xl:w-auto">
                    <!-- Search Input (Width & Height Mengecil di HP) -->
                    <div class="relative flex-1 min-w-0">
                        <span class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i data-lucide="search" class="size-3.5 sm:size-4"></i>
                        </span>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Cari barang atau alat medis..." 
                            class="w-full h-8.5 sm:h-10 pl-8 sm:pl-10 pr-7 sm:pr-9 py-1.5 sm:py-2 text-[11px] sm:text-xs bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 border border-slate-200 dark:border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-500 dark:focus:border-emerald-200 transition-all font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-xs"
                        />
                        @if ($search)
                            <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-2.5 sm:pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <i data-lucide="x" class="size-3.5 sm:size-4"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Tombol Aksi Icon Murni (Mengecil Proporsional di HP) -->
                    <div class="flex items-center gap-0.5 sm:gap-1 shrink-0">
                        <!-- Tombol Refresh / Segarkan Data -->
                        <div class="relative group z-20 hover:z-50">
                            <button 
                                wire:click="refreshData" 
                                class="size-8 sm:size-9 md:size-10 rounded-xl text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 inline-flex items-center justify-center transition-all hover:scale-110 active:scale-95 cursor-pointer shrink-0"
                                title="Segarkan Data"
                                aria-label="Segarkan Data"
                            >
                                <i data-lucide="rotate-cw" class="size-4 sm:size-4.5 md:size-5 transition-transform duration-500" wire:loading.class="animate-spin" wire:target="refreshData"></i>
                            </button>
                            <!-- Tooltip Text saat di-Hover (Mengarah ke Bawah) -->
                            <div class="pointer-events-none absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2.5 py-1 text-[11px] font-semibold text-white bg-slate-900 dark:bg-slate-800 rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 group-hover:translate-y-0.5 transition-all duration-200 z-50">
                                Segarkan Data
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 border-4 border-transparent border-b-slate-900 dark:border-b-slate-800"></span>
                            </div>
                        </div>

                        <!-- Tombol Tambah Barang -->
                        @if ($activeTab === 'tabAssets')
                            <div class="relative group z-20 hover:z-50">
                                <button 
                                    wire:click="openCreateModal" 
                                    class="size-8 sm:size-9 md:size-10 rounded-xl text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 inline-flex items-center justify-center transition-all hover:scale-110 active:scale-95 cursor-pointer shrink-0"
                                    title="Tambah Barang"
                                    aria-label="Tambah Barang"
                                >
                                    <i data-lucide="plus-circle" class="size-4 sm:size-4.5 md:size-5"></i>
                                </button>
                                <!-- Tooltip Text saat di-Hover -->
                                <div class="pointer-events-none absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2.5 py-1 text-[11px] font-semibold text-white bg-slate-900 dark:bg-slate-800 rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 group-hover:translate-y-0.5 transition-all duration-200 z-50">
                                    Tambah Barang
                                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 border-4 border-transparent border-b-slate-900 dark:border-b-slate-800"></span>
                                </div>
                            </div>
                        @endif

                        <!-- Tombol Hapus -->
                        <div class="relative group z-20 hover:z-50">
                            <button 
                                wire:click="handleClearButton" 
                                class="size-8 sm:size-9 md:size-10 rounded-xl text-rose-500 hover:text-rose-600 dark:text-rose-400 dark:hover:text-rose-300 inline-flex items-center justify-center transition-all hover:scale-110 active:scale-95 cursor-pointer shrink-0"
                                title="Hapus"
                                aria-label="Hapus"
                            >
                                <i data-lucide="trash-2" class="size-4 sm:size-4.5 md:size-5"></i>
                            </button>
                            <!-- Tooltip Text saat di-Hover -->
                            <div class="pointer-events-none absolute top-full right-0 mt-2 px-2.5 py-1 text-[11px] font-semibold text-white bg-slate-900 dark:bg-slate-800 rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 group-hover:translate-y-0.5 transition-all duration-200 z-50">
                                Hapus
                                <span class="absolute bottom-full right-3.5 border-4 border-transparent border-b-slate-900 dark:border-b-slate-800"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Data Table (Identik .custom-table Alkamart / Responsif di HP) -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] text-left border-collapse table-fixed">
                        <!-- Table Header Hijau Lembut (#E9FAF3) Khas Alkamart -->
                        <thead class="bg-[#E9FAF3] dark:bg-slate-800/90 border-b border-[#c7f0db] dark:border-slate-700 text-xs font-bold text-[#064E3B] dark:text-emerald-400 uppercase tracking-wider">
                            <tr>
                                <th class="w-[14%] min-w-[95px] py-2.5 px-3 sm:py-3.5 sm:px-4 cursor-pointer hover:text-emerald-950 dark:hover:text-emerald-300" wire:click="sortBy('asset_code')">
                                    <div class="flex items-center gap-1">
                                        <span>Kode Aset</span>
                                        @if ($sortField === 'asset_code')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="w-[28%] min-w-[170px] py-2.5 px-3 sm:py-3.5 sm:px-4 cursor-pointer hover:text-emerald-950 dark:hover:text-emerald-300" wire:click="sortBy('name')">
                                    <div class="flex items-center gap-1">
                                        <span>Nama Alat Medis</span>
                                        @if ($sortField === 'name')
                                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </div>
                                </th>
                                <th class="w-[20%] min-w-[130px] py-2.5 px-3 sm:py-3.5 sm:px-4">Ruangan RS</th>
                                <th class="w-[10%] min-w-[70px] py-2.5 px-3 sm:py-3.5 sm:px-4 text-center">Jumlah</th>
                                <th class="w-[12%] min-w-[85px] py-2.5 px-3 sm:py-3.5 sm:px-4">Kondisi</th>
                                <th class="w-[16%] min-w-[120px] py-2.5 px-3 sm:py-3.5 sm:px-4 text-right no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody wire:loading.class="opacity-60" class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300 transition-opacity duration-200">
                            @forelse ($assets as $asset)
                                <tr wire:key="asset-row-{{ $asset->id }}" class="h-[56px] sm:h-[64px] hover:bg-[#F8FAF8] dark:hover:bg-slate-800/60 transition-colors">
                                    <!-- Kode Aset (Bersih Tanpa Background & Border) -->
                                    <td class="py-2.5 px-3 sm:py-3 sm:px-4 font-mono font-semibold whitespace-nowrap text-xs text-slate-800 dark:text-slate-200">
                                        {{ $asset->asset_code }}
                                    </td>

                                    <!-- Nama Alat & Spesifikasi -->
                                    <td class="py-2.5 px-3 sm:py-3 sm:px-4">
                                        <div class="font-bold text-slate-900 dark:text-slate-100 text-xs sm:text-[13px] truncate">{{ $asset->name }}</div>
                                        <div class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                                            {{ $asset->brand ?? '-' }} {{ $asset->model_type ? '· ' . $asset->model_type : '' }}
                                            @if ($asset->serial_number)
                                                <span class="font-mono text-slate-400 dark:text-slate-500">· SN: {{ $asset->serial_number }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Ruangan Penempatan -->
                                    <td class="py-2.5 px-3 sm:py-3 sm:px-4">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200 truncate text-xs">{{ $asset->location->name ?? '-' }}</div>
                                        <div class="text-[10px] sm:text-[11px] text-slate-400 dark:text-slate-500 truncate">{{ $asset->location->building ?? '' }} · {{ $asset->location->floor ?? '' }}</div>
                                    </td>

                                    <!-- Jumlah Fisik (Bersih Tanpa Kotak Background & Border) -->
                                    <td class="py-2.5 px-3 sm:py-3 sm:px-4 whitespace-nowrap text-center font-mono text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        1 Unit
                                    </td>

                                    <!-- Kondisi (Titik Status Bersih Tanpa Kotak Background & Border) -->
                                    <td class="py-2.5 px-3 sm:py-3 sm:px-4 whitespace-nowrap">
                                        @if ($asset->condition === 'baik')
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                                <span class="size-1.5 rounded-full bg-emerald-500"></span> Baik
                                            </span>
                                        @elseif ($asset->condition === 'rusak_ringan')
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600 dark:text-amber-400">
                                                <span class="size-1.5 rounded-full bg-amber-500"></span> Rusak Ringan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">
                                                <span class="size-1.5 rounded-full bg-rose-500"></span> Rusak Berat
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi Tombol: Icon Murni dengan Ukuran Proporsional & Mikrointeraksi Hover (Tanpa Background) -->
                                    <td class="py-2.5 px-3 sm:py-3 sm:px-4 text-right whitespace-nowrap no-print">
                                        <div class="inline-flex items-center justify-end gap-1 sm:gap-1.5">
                                            <!-- Detail Button (Hijau Emerald RS Ummi) -->
                                            <button 
                                                wire:click="openDetailModal({{ $asset->id }})" 
                                                class="size-6.5 sm:size-7 rounded-lg text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-all hover:scale-115 active:scale-90 inline-flex items-center justify-center cursor-pointer"
                                                title="Lihat Detail & Histori Kalibrasi"
                                                aria-label="Lihat Detail & Histori Kalibrasi"
                                            >
                                                <i data-lucide="eye" class="size-3.5 sm:size-4"></i>
                                            </button>

                                            <!-- Cetak Label QR Button (Hijau Emerald RS Ummi) -->
                                            <button 
                                                wire:click="openPrintLabelModal({{ $asset->id }})" 
                                                class="size-6.5 sm:size-7 rounded-lg text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-all hover:scale-115 active:scale-90 inline-flex items-center justify-center cursor-pointer"
                                                title="Cetak Stiker Label QR Aset"
                                                aria-label="Cetak Stiker Label QR Aset"
                                            >
                                                <i data-lucide="qr-code" class="size-3.5 sm:size-4"></i>
                                            </button>

                                            <!-- Sunting Button (Hijau Emerald RS Ummi dengan Ikon Pensil Bersih) -->
                                            <button 
                                                wire:click="openEditModal({{ $asset->id }})" 
                                                class="size-6.5 sm:size-7 rounded-lg text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-all hover:scale-115 active:scale-90 inline-flex items-center justify-center cursor-pointer"
                                                title="Sunting Data Aset"
                                                aria-label="Sunting Data Aset"
                                            >
                                                <i data-lucide="pencil" class="size-3.5 sm:size-4"></i>
                                            </button>

                                            <!-- Hapus Button (Merah Rose Tegas) -->
                                            <button 
                                                wire:click="confirmDelete({{ $asset->id }})" 
                                                class="size-6.5 sm:size-7 rounded-lg text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 transition-all hover:scale-115 active:scale-90 inline-flex items-center justify-center cursor-pointer"
                                                title="Hapus Aset"
                                                aria-label="Hapus Aset"
                                            >
                                                <i data-lucide="trash-2" class="size-3.5 sm:size-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="h-[320px] py-12 px-4 text-center text-slate-400 dark:text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i data-lucide="inbox" class="size-12 text-slate-300 dark:text-slate-600 mb-2"></i>
                                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Tidak ada aset rumah sakit yang ditemukan.</p>
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Silakan sesuaikan kata kunci atau bersihkan filter.</p>
                                            <button wire:click="resetFilters" class="mt-3 text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline">
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
                <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900 no-print">
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
                        <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">Rekapitulasi Nilai Aset & Beban Pemeliharaan RS</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Ringkasan finansial inventaris dan kapitalisasi alat kesehatan RS Ummi.</p>
                    </div>
                    <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 shadow-xs">
                        <i data-lucide="printer" class="size-4"></i>
                        <span>Cetak Laporan Rekapitulasi</span>
                    </button>
                </div>

                <!-- ================= 4 REPORT CARDS HIJAU EMERALD (PERSIS GAMBAR 2 ALKAMART) ================= -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Card 1: Total Saldo / Total Inventaris Aset -->
                    <div class="report-card green">
                        <div class="report-icon">
                            <i data-lucide="wallet" class="size-7 text-white"></i>
                        </div>
                        <div class="report-info">
                            <span class="report-label">Total Inventaris Aset RS</span>
                            <h3 class="report-amount">{{ $stats['total'] }} <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Unit</span></h3>
                            <span class="report-sublabel">Seluruh aset & peralatan medis</span>
                        </div>
                    </div>

                    <!-- Card 2: Omzet / Aset Siap Operasional -->
                    <div class="report-card green">
                        <div class="report-icon">
                            <i data-lucide="calendar-check-2" class="size-7 text-white"></i>
                        </div>
                        <div class="report-info">
                            <span class="report-label">Siap Operasional (Baik)</span>
                            <h3 class="report-amount text-emerald-700 dark:text-emerald-400">{{ $stats['good'] }} <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Alat</span></h3>
                            <span class="report-sublabel">{{ $stats['total'] > 0 ? round(($stats['good'] / $stats['total']) * 100, 1) : 0 }}% alat laik pakai & prima</span>
                        </div>
                    </div>

                    <!-- Card 3: Laba Bersih / Perlu Kalibrasi -->
                    <div class="report-card green">
                        <div class="report-icon">
                            <i data-lucide="activity" class="size-7 text-white"></i>
                        </div>
                        <div class="report-info">
                            <span class="report-label">Perlu Servis / Rusak</span>
                            <h3 class="report-amount text-amber-600 dark:text-amber-400">{{ $stats['need_attention'] }} <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Unit</span></h3>
                            <span class="report-sublabel">Butuh perhatian teknisi medis</span>
                        </div>
                    </div>

                    <!-- Card 4: Total Tarik Uang / Nilai Kapitalisasi Aset -->
                    <div class="report-card green">
                        <div class="report-icon">
                            <i data-lucide="coins" class="size-7 text-white"></i>
                        </div>
                        <div class="report-info">
                            <span class="report-label">Estimasi Nilai Inventaris</span>
                            <h3 class="report-amount text-[19px]">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</h3>
                            <span class="report-sublabel">Kapitalisasi aset RS Ummi</span>
                        </div>
                    </div>
                </div>

                <!-- Rekap Data per Ruangan & Kategori dengan Visual Grafik Interaktif (Chart.js + Alpine) -->
                <div 
                    x-data="{
                        categoryChart: null,
                        locationChart: null,
                        initCharts() {
                            this.$nextTick(() => {
                                if (typeof Chart === 'undefined') return;

                                const isDark = document.documentElement.classList.contains('dark');
                                const textColor = isDark ? '#F1F5F9' : '#0F172A';
                                const subTextColor = isDark ? '#CBD5E1' : '#475569';
                                const gridColor = isDark ? 'rgba(51, 65, 85, 0.45)' : 'rgba(226, 232, 240, 0.8)';
                                const borderColor = isDark ? '#0F172A' : '#FFFFFF';

                                // 1. Donut Chart: Distribusi Kategori
                                const catCanvas = document.getElementById('categoryDonutChart');
                                if (catCanvas) {
                                    if (this.categoryChart) {
                                        this.categoryChart.destroy();
                                    }
                                    this.categoryChart = new Chart(catCanvas, {
                                        type: 'doughnut',
                                        data: {
                                            labels: {{ Js::from($categories->pluck('name')) }},
                                            datasets: [{
                                                data: {{ Js::from($categories->pluck('assets_count')) }},
                                                backgroundColor: [
                                                    '#10B981',
                                                    '#0EA5E9',
                                                    '#F59E0B',
                                                    '#8B5CF6',
                                                    '#EC4899',
                                                    '#14B8A6',
                                                ],
                                                borderWidth: 2,
                                                borderColor: borderColor,
                                                hoverOffset: 6
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: { display: false },
                                                tooltip: {
                                                    backgroundColor: isDark ? '#1E293B' : '#0F172A',
                                                    titleColor: '#FFFFFF',
                                                    bodyColor: '#E2E8F0',
                                                    padding: 10,
                                                    cornerRadius: 8,
                                                    callbacks: {
                                                        label: function(context) {
                                                            const val = context.raw || 0;
                                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                            const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                                            return ` ${context.label}: ${val} Unit (${pct}%)`;
                                                        }
                                                    }
                                                }
                                            },
                                            cutout: '72%'
                                        }
                                    });
                                }

                                // 2. Horizontal Bar Chart: Sebaran Ruangan
                                const locCanvas = document.getElementById('locationBarChart');
                                if (locCanvas) {
                                    if (this.locationChart) {
                                        this.locationChart.destroy();
                                    }
                                    const maxVal = Math.max(...{{ Js::from($locations->pluck('assets_count')) }}, 1);
                                    this.locationChart = new Chart(locCanvas, {
                                        type: 'bar',
                                        data: {
                                            labels: {{ Js::from($locations->pluck('name')) }},
                                            datasets: [{
                                                label: 'Jumlah Unit',
                                                data: {{ Js::from($locations->pluck('assets_count')) }},
                                                backgroundColor: isDark ? 'rgba(16, 185, 129, 0.95)' : 'rgba(4, 120, 87, 0.9)',
                                                hoverBackgroundColor: '#34D399',
                                                borderRadius: 6,
                                                borderSkipped: false,
                                                barThickness: 16
                                            }]
                                        },
                                        options: {
                                            indexAxis: 'y',
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            layout: {
                                                padding: { right: 50 }
                                            },
                                            plugins: {
                                                legend: { display: false },
                                                tooltip: {
                                                    backgroundColor: isDark ? '#1E293B' : '#0F172A',
                                                    titleColor: '#FFFFFF',
                                                    bodyColor: '#E2E8F0',
                                                    padding: 10,
                                                    cornerRadius: 8,
                                                    callbacks: {
                                                        label: function(context) {
                                                            return ` ${context.raw} Unit Alat Medis`;
                                                        }
                                                    }
                                                }
                                            },
                                            scales: {
                                                x: {
                                                    beginAtZero: true,
                                                    suggestedMax: maxVal + 0.6,
                                                    ticks: {
                                                        stepSize: 1,
                                                        color: subTextColor,
                                                        font: { size: 11, family: 'Inter', weight: '600' }
                                                    },
                                                    grid: { color: gridColor }
                                                },
                                                y: {
                                                    ticks: {
                                                        color: textColor,
                                                        font: { size: 12, family: 'Inter', weight: '600' }
                                                    },
                                                    grid: { display: false }
                                                }
                                            }
                                        },
                                        plugins: [{
                                            id: 'barValueLabels',
                                            afterDatasetsDraw(chart) {
                                                const { ctx, data } = chart;
                                                ctx.save();
                                                ctx.font = 'bold 11px Inter, sans-serif';
                                                ctx.fillStyle = isDark ? '#34D399' : '#047857';
                                                ctx.textAlign = 'left';
                                                ctx.textBaseline = 'middle';
                                                chart.getDatasetMeta(0).data.forEach((bar, index) => {
                                                    const value = data.datasets[0].data[index];
                                                    ctx.fillText(`${value} Unit`, bar.x + 8, bar.y);
                                                });
                                                ctx.restore();
                                            }
                                        }]
                                    });
                                }
                            });
                        }
                    }"
                    x-init="initCharts()"
                    @theme-changed.window="initCharts()"
                    class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch"
                >
                    <!-- Card 1: Distribusi Aset Menurut Kategori (Donut Chart + List Progress) -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="pie-chart" class="size-4 text-emerald-600 dark:text-emerald-400"></i>
                                    <span>Distribusi Aset Menurut Kategori</span>
                                </h4>
                                <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-full border border-slate-200 dark:border-slate-700">
                                    {{ $stats['total'] }} Total Unit
                                </span>
                            </div>

                            <!-- Visual Donut Chart + Center Label -->
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center my-2">
                                <div class="sm:col-span-5 relative flex items-center justify-center h-48">
                                    <canvas id="categoryDonutChart"></canvas>
                                    <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
                                        <span class="text-3xl font-extrabold text-slate-900 dark:text-white leading-none">{{ $stats['total'] }}</span>
                                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300 mt-1 uppercase tracking-wider">Unit Aset</span>
                                    </div>
                                </div>
                                <div class="sm:col-span-7 space-y-3">
                                    @php
                                        $catColors = ['#10B981', '#0EA5E9', '#F59E0B', '#8B5CF6', '#EC4899', '#14B8A6'];
                                    @endphp
                                    @foreach ($categories as $index => $c)
                                        @php
                                            $pct = $stats['total'] > 0 ? round(($c->assets_count / $stats['total']) * 100, 1) : 0;
                                            $color = $catColors[$index % count($catColors)];
                                        @endphp
                                        <div>
                                            <div class="flex items-center justify-between text-xs mb-1.5">
                                                <div class="flex items-center gap-2 min-w-0 pr-2">
                                                    <span class="size-2.5 rounded-full shrink-0 shadow-2xs" style="background-color: {{ $color }};"></span>
                                                    <span class="font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $c->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $c->assets_count }} Unit</span>
                                                    <span class="text-xs font-bold font-mono text-emerald-600 dark:text-emerald-400 w-12 text-right">({{ $pct }}%)</span>
                                                </div>
                                            </div>
                                            <!-- Progress Bar Proporsional -->
                                            <div class="w-full h-2 rounded-full bg-slate-200/80 dark:bg-slate-800 overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $pct }}%; background-color: {{ $color }};"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 mt-2 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300 flex items-center justify-between font-medium">
                            <span>Dominasi Kategori Terbesar:</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $categories->sortByDesc('assets_count')->first()->name ?? '-' }} ({{ $categories->sortByDesc('assets_count')->first()->assets_count ?? 0 }} Unit)
                            </span>
                        </div>
                    </div>

                    <!-- Card 2: Sebaran Alat di Ruangan RS Ummi (Horizontal Bar Chart + Ringkasan) -->
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                                    <i data-lucide="hospital" class="size-4 text-emerald-600 dark:text-emerald-400"></i>
                                    <span>Sebaran Alat di Ruangan RS Ummi</span>
                                </h4>
                                <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 rounded-full border border-slate-200 dark:border-slate-700">
                                    {{ $locations->count() }} Ruangan Aktif
                                </span>
                            </div>

                            <!-- Visual Horizontal Bar Chart -->
                            <div class="h-60 relative w-full">
                                <canvas id="locationBarChart"></canvas>
                            </div>
                        </div>

                        <div class="pt-4 mt-2 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300 flex items-center justify-between font-medium">
                            <span>Kepadatan Aset Tertinggi:</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $locations->sortByDesc('assets_count')->first()->name ?? '-' }} ({{ $locations->sortByDesc('assets_count')->first()->assets_count ?? 0 }} Unit)
                            </span>
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
                        <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">Histori Lengkap Pemeliharaan & Kalibrasi Alat Medis</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Catatan riwayat uji fungsi berkala, kalibrasi BPFK, dan perbaikan perangkat.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-left border-collapse text-xs">
                            <thead class="bg-[#E9FAF3] dark:bg-slate-800 border-b border-[#c7f0db] dark:border-slate-700 text-xs font-bold text-[#064E3B] dark:text-emerald-400 uppercase">
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
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-[#F8FAF8] dark:hover:bg-slate-800/60">
                                    <td class="py-3 px-4 font-mono font-medium">{{ $log->maintenance_date->format('d M Y') }}</td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-900 dark:text-slate-100">{{ $log->asset->name }}</div>
                                        <span class="font-mono text-[11px] text-slate-500 dark:text-slate-400">{{ $log->asset->asset_code }}</span>
                                    </td>
                                    <td class="py-3 px-4">{{ $log->asset->location->name ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="font-semibold capitalize">{{ str_replace('_', ' ', $log->type) }}</span>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $log->notes }}</p>
                                    </td>
                                    <td class="py-3 px-4">{{ $log->vendor_technician ?? 'Internal RS' }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-slate-900 dark:text-slate-100">Rp {{ number_format($log->cost, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold capitalize {{ $log->status === 'selesai' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                            <span class="size-1.5 rounded-full {{ $log->status === 'selesai' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">Belum ada riwayat servis yang tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-2xl w-full overflow-hidden">
                <!-- Modal Header Hijau Emerald -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-[#E9FAF3] dark:bg-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="size-8 rounded-lg bg-emerald-700 text-white flex items-center justify-center">
                            <i data-lucide="{{ $selectedAssetId ? 'pencil' : 'plus-circle' }}" class="size-4 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#064E3B] dark:text-emerald-400">
                                {{ $selectedAssetId ? 'Sunting Data Aset Rumah Sakit' : 'Pendaftaran Aset Rumah Sakit Baru' }}
                            </h3>
                            <p class="text-[11px] text-emerald-800 dark:text-slate-400">Isi formulir kelengkapan inventaris aset RS Ummi dengan valid.</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- Form Body -->
                <form wire:submit.prevent="save" class="p-6 space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kode Aset -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kode Aset RS <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="asset_code" placeholder="Contoh: MED-001" class="w-full px-3 py-2 border rounded-lg font-mono uppercase bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 {{ $errors->has('asset_code') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 dark:border-slate-700' }}" />
                            @error('asset_code') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Aset -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Alat / Aset <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="name" placeholder="Contoh: Defibrillator Mindray D3" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 dark:border-slate-700' }}" />
                            @error('name') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Aset <span class="text-rose-500">*</span></label>
                            <select wire:model="category_id" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 {{ $errors->has('category_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 dark:border-slate-700' }}">
                                <option value="">Pilih Kategori...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Ruangan Penempatan -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Ruangan Penempatan RS <span class="text-rose-500">*</span></label>
                            <select wire:model="location_id" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 {{ $errors->has('location_id') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 dark:border-slate-700' }}">
                                <option value="">Pilih Ruangan...</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->building }})</option>
                                @endforeach
                            </select>
                            @error('location_id') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Merk -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Merk / Brand</label>
                            <input type="text" wire:model="brand" placeholder="Contoh: Mindray, GE, Siemens" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500" />
                        </div>

                        <!-- Tipe / Model -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tipe / Model</label>
                            <input type="text" wire:model="model_type" placeholder="Contoh: BeneHeart D3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500" />
                        </div>

                        <!-- Serial Number -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Seri (Serial Number)</label>
                            <input type="text" wire:model="serial_number" placeholder="Contoh: MR-DF-2023-091" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg font-mono focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500" />
                        </div>

                        <!-- Tanggal Pengadaan -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Pengadaan</label>
                            <input type="date" wire:model="purchase_date" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500" />
                        </div>

                        <!-- Harga Perolehan -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Harga Perolehan (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" wire:model="purchase_cost" class="w-full px-3 py-2 border rounded-lg font-mono bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 {{ $errors->has('purchase_cost') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300 dark:border-slate-700' }}" />
                            @error('purchase_cost') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kondisi -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kondisi Fisik & Fungsi <span class="text-rose-500">*</span></label>
                            <select wire:model="condition" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500">
                                <option value="baik">Baik (Siap Operasional)</option>
                                <option value="rusak_ringan">Rusak Ringan (Perlu Kalibrasi/Perbaikan)</option>
                                <option value="rusak_berat">Rusak Berat (Tidak Berfungsi)</option>
                            </select>
                        </div>

                        <!-- Status Operasional -->
                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Operasional <span class="text-rose-500">*</span></label>
                            <select wire:model="status" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500">
                                <option value="aktif">Aktif Digunakan</option>
                                <option value="pemeliharaan">Dalam Perbaikan / Kalibrasi</option>
                                <option value="afkir">Afkir / Dihapuskan</option>
                            </select>
                        </div>

                        <!-- Foto Fisik Alat Medis -->
                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Foto Fisik Alat Medis</label>
                            <div class="flex items-center gap-3">
                                <input type="file" wire:model="image" accept="image/*" class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 dark:file:bg-emerald-950/60 file:text-emerald-700 dark:file:text-emerald-300 hover:file:bg-emerald-100 border border-slate-300 dark:border-slate-700 rounded-lg p-1 bg-white dark:bg-slate-800" />
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="size-12 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shrink-0 shadow-2xs" />
                                @elseif ($image_path)
                                    <img src="{{ asset($image_path) }}" class="size-12 rounded-lg object-cover border border-slate-200 dark:border-slate-700 shrink-0 shadow-2xs" />
                                @endif
                            </div>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 block">Format: JPG, PNG, WEBP (Maksimal 5MB). Foto akan tampil pada detail aset.</span>
                            @error('image') <span class="text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="sm:col-span-2">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Catatan Tambahan Spesifikasi</label>
                            <textarea wire:model="notes" rows="2" placeholder="Catatan teknis alat atau petunjuk khusus..." class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500"></textarea>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg shadow-xs">
                            <i data-lucide="save" class="size-4"></i>
                            <span wire:loading.remove wire:target="save">Simpan Data Aset</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 2: Popup Ringkas Detail Alat Medis (Compact Pop-up Card - No Scroll) -->
    <!-- ========================================================================= -->
    @if ($isDetailOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-lg w-full overflow-hidden">
                <!-- Popup Header: Kompak & Elegan -->
                <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-[#E9FAF3] dark:bg-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-xs font-bold px-2.5 py-0.5 rounded-md bg-emerald-800 text-white shadow-2xs">
                            {{ $selectedAsset->asset_code }}
                        </span>
                        @if ($selectedAsset->condition === 'baik')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300">
                                Kondisi Baik
                            </span>
                        @elseif ($selectedAsset->condition === 'rusak_ringan')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300">
                                Rusak Ringan
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 dark:bg-rose-950/70 text-rose-800 dark:text-rose-300">
                                Rusak Berat
                            </span>
                        @endif
                    </div>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg transition-colors">
                        <i data-lucide="x" class="size-4.5"></i>
                    </button>
                </div>

                <!-- Popup Body: Pas Satu Tampilan (Tidak Perlu Scroll) -->
                <div class="p-5 space-y-3.5 text-xs">
                    <!-- Baris 1: Foto Fisik Kiri + Info Identitas Kanan -->
                    <div class="flex gap-4 items-stretch">
                        <!-- Foto Fisik Alat Medis (Kompak & Proporsional) -->
                        <div class="relative w-44 h-36 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 shadow-2xs">
                            @if ($selectedAsset->image_url)
                                <img 
                                    src="{{ $selectedAsset->image_url }}" 
                                    alt="{{ $selectedAsset->name }}" 
                                    class="w-full h-full object-cover"
                                />
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 p-2 text-center">
                                    <i data-lucide="image" class="size-8 mb-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-[10px] font-semibold">Foto Belum Tersedia</span>
                                </div>
                            @endif
                            <div class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded bg-slate-900/80 backdrop-blur-xs text-[9px] text-white font-medium flex items-center gap-1 shadow-2xs">
                                <i data-lucide="camera" class="size-3 text-emerald-400"></i>
                                <span>Foto Fisik Alat</span>
                            </div>
                        </div>

                        <!-- Data Identitas Alat Medis -->
                        <div class="flex-1 flex flex-col justify-between min-w-0">
                            <div>
                                <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide block truncate">
                                    {{ $selectedAsset->category->name ?? '-' }}
                                </span>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-snug line-clamp-2 mt-0.5">
                                    {{ $selectedAsset->name }}
                                </h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 truncate">
                                    {{ $selectedAsset->brand ?? '-' }} {{ $selectedAsset->model_type ? '· ' . $selectedAsset->model_type : '' }}
                                </p>
                                <p class="text-[11px] font-mono text-slate-400 dark:text-slate-500 mt-0.5 truncate">
                                    SN: {{ $selectedAsset->serial_number ?? '-' }}
                                </p>
                            </div>

                            <!-- Status Operasional Badge -->
                            <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[11px]">
                                <span class="text-slate-400 dark:text-slate-500">Status Operasional:</span>
                                <span class="font-bold capitalize {{ $selectedAsset->status === 'aktif' ? 'text-emerald-700 dark:text-emerald-400' : ($selectedAsset->status === 'pemeliharaan' ? 'text-amber-700 dark:text-amber-400' : 'text-rose-700 dark:text-rose-400') }}">
                                    {{ $selectedAsset->status === 'aktif' ? 'Aktif Digunakan' : ($selectedAsset->status === 'pemeliharaan' ? 'Pemeliharaan' : 'Afkir') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2: Parameter Penempatan, PIC, Kuantitas, & Harga -->
                    <div class="grid grid-cols-2 gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-[11px]">
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block text-[10px] uppercase font-medium">Ruangan & Gedung</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">{{ $selectedAsset->location->name ?? '-' }}</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 block truncate">{{ $selectedAsset->location->building ?? '' }} ({{ $selectedAsset->location->floor ?? '-' }})</span>
                        </div>
                        <div>
                            <span class="text-slate-400 dark:text-slate-500 block text-[10px] uppercase font-medium">Penanggung Jawab (PIC)</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">{{ $selectedAsset->location->pic_name ?? '-' }}</span>
                            <span class="text-[10px] text-emerald-700 dark:text-emerald-400 block font-medium">IPSRS RS Ummi</span>
                        </div>
                        <div class="pt-1.5 border-t border-slate-200/60 dark:border-slate-700/60">
                            <span class="text-slate-400 dark:text-slate-500 block text-[10px] uppercase font-medium">Jumlah Fisik</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200">1 Unit (Terserialisasi)</span>
                        </div>
                        <div class="pt-1.5 border-t border-slate-200/60 dark:border-slate-700/60">
                            <span class="text-slate-400 dark:text-slate-500 block text-[10px] uppercase font-medium">Harga Perolehan</span>
                            <span class="font-mono font-bold text-slate-900 dark:text-slate-100">Rp {{ number_format($selectedAsset->purchase_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Baris 3: Ringkasan Histori Kalibrasi / Servis (1 Baris Ringkas) -->
                    @if ($selectedAsset->maintenanceLogs->isNotEmpty())
                        @php $latestLog = $selectedAsset->maintenanceLogs->first(); @endphp
                        <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-200/60 dark:border-emerald-800/40 text-[11px]">
                            <div class="flex items-center gap-1.5 truncate">
                                <i data-lucide="history" class="size-3.5 text-emerald-700 dark:text-emerald-400"></i>
                                <span class="text-slate-600 dark:text-slate-300 truncate">
                                    Histori Kalibrasi Terakhir: <strong class="capitalize">{{ str_replace('_', ' ', $latestLog->type) }}</strong> ({{ $latestLog->maintenance_date->format('d M Y') }})
                                </span>
                            </div>
                            <span class="font-mono font-bold text-slate-700 dark:text-slate-300 shrink-0 ml-2">
                                {{ $selectedAsset->maintenanceLogs->count() }}x Servis
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Popup Footer Action -->
                <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900 flex items-center justify-between">
                    <button wire:click="openPrintLabelModal({{ $selectedAsset->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-800 dark:text-emerald-300 bg-white dark:bg-slate-800 border border-emerald-300 dark:border-emerald-700 hover:bg-emerald-50 dark:hover:bg-slate-700 transition-colors shadow-2xs">
                        <i data-lucide="qr-code" class="size-4"></i>
                        <span>Cetak Label QR</span>
                    </button>
                    <button wire:click="closeModal" class="px-4 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-lg transition-colors">
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-md w-full overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-[#E9FAF3] dark:bg-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-[#064E3B] dark:text-emerald-400">Catat Servis / Kalibrasi Baru</h3>
                        <p class="text-[11px] text-emerald-800 dark:text-slate-400">{{ $selectedAsset->name }} ({{ $selectedAsset->asset_code }})</p>
                    </div>
                    <button wire:click="$set('isLogModalOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="size-4.5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveLog" class="p-6 space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tanggal Tindakan</label>
                        <input type="date" wire:model="log_date" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 border-slate-300 dark:border-slate-700" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Jenis Tindakan</label>
                        <select wire:model="log_type" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 border-slate-300 dark:border-slate-700">
                            <option value="kalibrasi">Uji Kalibrasi Berkala</option>
                            <option value="pemeliharaan_rutin">Pemeliharaan Rutin / Servis Ringan</option>
                            <option value="perbaikan">Perbaikan Kerusakan Alat</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Biaya Pemeliharaan (Rp)</label>
                        <input type="number" wire:model="log_cost" class="w-full px-3 py-2 border rounded-lg font-mono bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 border-slate-300 dark:border-slate-700" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Teknisi / Lembaga Penguji (BPFK)</label>
                        <input type="text" wire:model="log_technician" placeholder="Contoh: Balai Pengujian Fasilitas Kesehatan (BPFK)" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 border-slate-300 dark:border-slate-700" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Status Tindakan</label>
                        <select wire:model="log_status" class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 border-slate-300 dark:border-slate-700">
                            <option value="selesai">Selesai (Laik Pakai)</option>
                            <option value="dalam_proses">Dalam Proses Tindakan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Catatan Hasil Pemeriksaan</label>
                        <textarea wire:model="log_notes" rows="2" placeholder="Hasil pengujian toleransi atau suku cadang yang diganti..." class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-700 dark:focus:border-emerald-500 border-slate-300 dark:border-slate-700"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isLogModalOpen', false)" class="px-3 py-1.5 font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">Batal</button>
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-sm w-full overflow-hidden text-center">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-[#E9FAF3] dark:bg-slate-800 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-[#064E3B] dark:text-emerald-400">Stiker Label Inventaris RS</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="size-4.5"></i>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Template Stiker Fisik RS Ummi (Selalu Background Putih agar Siap Cetak Stiker) -->
                    <div id="printableAssetTag" class="p-4 border-2 border-dashed border-emerald-800 rounded-xl bg-white text-left shadow-xs space-y-3">
                        <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                            <div class="size-8 rounded-md bg-emerald-800 text-white flex items-center justify-center">
                                <i data-lucide="hospital" class="size-5 text-white"></i>
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

                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 flex items-center justify-end gap-2">
                    <button wire:click="closeModal" class="px-3 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 rounded-lg">Tutup</button>
                    <button onclick="window.print()" class="inline-flex items-center gap-1 px-4 py-1.5 text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-lg shadow-xs">
                        <i data-lucide="printer" class="size-4"></i>
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
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-sm w-full p-6 text-center">
                <div class="size-12 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="trash-2" class="size-6"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Hapus Data Aset Ini?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">
                    Data alat medis <strong class="text-slate-800 dark:text-slate-200">{{ $selectedAsset->name }}</strong> ({{ $selectedAsset->asset_code }}) akan dihapus permanen dari inventaris.
                </p>
                <div class="mt-5 flex items-center justify-center gap-2">
                    <button wire:click="closeModal" class="px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                        Batal
                    </button>
                    <button wire:click="delete" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-xs">
                        Ya, Hapus Data
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL 5: Konfirmasi Kosongkan Semua Produk / Aset (Clear All Confirmation) -->
    <!-- ========================================================================= -->
    @if ($isClearAllOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-sm w-full p-6 text-center">
                <div class="size-12 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="trash-2" class="size-6"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Kosongkan Semua Produk?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                    Apakah Anda yakin ingin menghapus semua data inventaris? Katalog akan di-reset menjadi 0 barang agar Anda dapat memasukkan data dari awal.
                </p>
                <div class="mt-5 flex flex-col gap-2">
                    <div class="flex items-center justify-center gap-2">
                        <button wire:click="closeModal" class="flex-1 px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                            Batal
                        </button>
                        <button wire:click="clearAllProducts" class="flex-1 px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-xs">
                            Ya, Kosongkan
                        </button>
                    </div>
                    <button wire:click="reseedDefaultAssets" class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 hover:underline pt-1">
                        Atau Muat Ulang Data Bawaan RS Ummi
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
