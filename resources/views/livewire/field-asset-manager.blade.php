<div class="w-full min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 flex flex-col">
    <!-- ================= TOP HEADER PORTAL LAPANGAN ================= -->
    <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-6 flex items-center justify-between sticky top-0 z-50 shadow-xs transition-colors duration-200">
        <!-- Brand & Subtitle -->
        <div class="flex items-center gap-3.5">
            <div class="flex items-center justify-center size-11 rounded-lg bg-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i data-lucide="hospital" class="size-6 text-white"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-extrabold text-emerald-800 dark:text-emerald-400 tracking-tight block">RS UMMI BOGOR</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-600/20 dark:border-emerald-700/40">
                        Portal Lapangan & Teknisi
                    </span>
                </div>
                <span class="text-xs text-slate-500 dark:text-slate-400 block -mt-0.5">Sistem Inventarisasi, Pemindaian QR & Pemeriksaan Fisik Alat Medis</span>
            </div>
        </div>

        <!-- Header Actions: Link ke Dashboard Admin & Saklar Tema -->
        <div class="flex items-center gap-2.5">
            <a 
                href="{{ route('dashboard') }}" 
                class="no-print h-9 px-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 inline-flex items-center gap-1.5 transition-colors shadow-2xs"
                title="Beralih ke Dashboard Utama SIMRS"
            >
                <i data-lucide="layout-dashboard" class="size-3.5 text-emerald-600 dark:text-emerald-400"></i>
                <span class="hidden sm:inline">Dashboard Admin SIMRS</span>
            </a>

            <!-- Saklar Mode Terang / Gelap (Hijau Emerald) -->
            <button 
                type="button" 
                onclick="toggleAppTheme()" 
                class="no-print p-2 text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 flex items-center justify-center transition-colors cursor-pointer select-none"
                title="Ganti Mode Tampilan (Terang / Gelap)"
                aria-label="Ganti Mode Tampilan (Terang / Gelap)"
            >
                <svg class="size-6 text-emerald-600 dark:text-emerald-400 transition-transform duration-300 hover:rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    <circle cx="12" cy="12" r="6" stroke-width="2"></circle>
                    <path d="M12 6a6 6 0 0 0 6 6 6 6 0 0 1-6-6z" fill="currentColor" stroke="none"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- ================= SUB-NAV TABS PORTAL LAPANGAN (WEB STYLE) ================= -->
    <div class="no-print admin-tab-nav">
        <!-- Tab 1: Pemindai QR & Cek Alat -->
        <button 
            type="button" 
            wire:click="setTab('scan')" 
            class="admin-tab-btn {{ $activeTab === 'scan' ? 'active' : '' }}" 
            title="Scan Stiker QR & Verifikasi Fisik"
        >
            <i data-lucide="qr-code" class="size-[18px]"></i>
            <span class="tab-label">Scan QR & Cek Alat</span>
        </button>

        <!-- Tab 2: Katalog Alat per Ruangan -->
        <button 
            type="button" 
            wire:click="setTab('list')" 
            class="admin-tab-btn {{ $activeTab === 'list' ? 'active' : '' }}" 
            title="Katalog & Status Alat per Ruangan"
        >
            <i data-lucide="package" class="size-[18px]"></i>
            <span class="tab-label">Katalog Alat Ruangan</span>
        </button>

        <!-- Tab 3: Pendaftaran Alat Baru Lapangan -->
        <button 
            type="button" 
            wire:click="setTab('create')" 
            class="admin-tab-btn {{ $activeTab === 'create' ? 'active' : '' }}" 
            title="Input & Daftarkan Alat Baru"
        >
            <i data-lucide="plus-circle" class="size-[18px]"></i>
            <span class="tab-label">Input Alat Baru</span>
        </button>
    </div>

    <!-- Container Konten Luas (Web Layout Responsif) -->
    <main class="flex-1 w-full max-w-7xl mx-auto p-6 space-y-6">
        <!-- Flash Alert Notification -->
        @if ($feedbackMessage)
            <div class="p-4 rounded-xl flex items-center justify-between border shadow-xs transition-all {{ $feedbackType === 'danger' ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900/60 text-rose-800 dark:text-rose-200' : ($feedbackType === 'warning' ? 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-900/60 text-amber-900 dark:text-amber-200' : 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900/60 text-emerald-900 dark:text-emerald-200') }}">
                <div class="flex items-center gap-3 text-xs sm:text-sm">
                    <i data-lucide="{{ $feedbackType === 'danger' ? 'alert-circle' : ($feedbackType === 'warning' ? 'triangle-alert' : 'check-circle-2') }}" class="size-5 shrink-0"></i>
                    <span class="font-semibold">{{ $feedbackMessage }}</span>
                </div>
                <button wire:click="$set('feedbackMessage', null)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                    <i data-lucide="x" class="size-4.5"></i>
                </button>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- TAB 1: PEMINDAI KAMERA QR CODE & CEK KONDISI (WEB SPLIT LAYOUT) -->
        <!-- ========================================================================= -->
        @if ($activeTab === 'scan')
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Kolom Kiri (5 Kolom): Live Camera Viewport -->
                <div 
                    x-data="{
                        html5QrCode: null,
                        scanning: false,
                        scanError: '',
                        initScanner() {
                            if (typeof Html5Qrcode === 'undefined') {
                                this.scanError = 'Library pemindai QR sedang dimuat...';
                                return;
                            }
                            this.html5QrCode = new Html5Qrcode('qr-reader-web');
                        },
                        startCamera() {
                            this.scanError = '';
                            if (!this.html5QrCode) this.initScanner();

                            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                            this.html5QrCode.start(
                                { facingMode: 'environment' },
                                config,
                                (decodedText) => {
                                    this.stopCamera();
                                    $wire.handleScannedCode(decodedText);
                                },
                                (errorMessage) => {}
                            ).then(() => {
                                this.scanning = true;
                            }).catch(err => {
                                this.scanError = 'Akses kamera tidak diizinkan atau tidak terdeteksi. Silakan aktifkan izin kamera atau gunakan pencarian kode manual.';
                                this.scanning = false;
                            });
                        },
                        stopCamera() {
                            if (this.html5QrCode && this.scanning) {
                                this.html5QrCode.stop().then(() => {
                                    this.scanning = false;
                                }).catch(err => console.error(err));
                            }
                        }
                    }"
                    x-init="initScanner()"
                    @tab-changed.window="if ($event.detail.tab !== 'scan') stopCamera()"
                    class="lg:col-span-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs text-center space-y-4"
                >
                    <div>
                        <div class="flex items-center justify-center gap-2 mb-1">
                            <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h3 class="text-base font-bold text-slate-900 dark:text-slate-100">Kamera Pemindai QR Stiker</h3>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Arahkan kamera ke stiker QR fisik yang tertempel pada bodi alat medis.</p>
                    </div>

                    <!-- Area Kamera -->
                    <div class="relative w-full aspect-square max-w-[320px] mx-auto rounded-2xl overflow-hidden bg-slate-950 border-2 border-dashed border-emerald-500 flex flex-col items-center justify-center shadow-inner">
                        <div id="qr-reader-web" class="w-full h-full"></div>

                        <div x-show="!scanning" class="p-6 text-center text-slate-400 space-y-3">
                            <div class="size-16 rounded-2xl bg-slate-900 border border-slate-800 text-emerald-400 mx-auto flex items-center justify-center shadow-lg">
                                <i data-lucide="camera" class="size-8"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-200">Kamera Standby</p>
                                <p class="text-xs text-slate-500 mt-0.5">Tekan tombol di bawah untuk mengaktifkan pemindai.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    <div x-show="scanError" x-cloak class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 text-xs text-left flex items-start gap-2">
                        <i data-lucide="alert-circle" class="size-4 shrink-0 mt-0.5"></i>
                        <span x-text="scanError"></span>
                    </div>

                    <!-- Tombol Kontrol Kamera -->
                    <div class="flex gap-2.5">
                        <button 
                            x-show="!scanning" 
                            @click="startCamera()" 
                            type="button" 
                            class="flex-1 py-3 px-4 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 active:scale-98 transition-all shadow-md shadow-emerald-900/20 flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="camera" class="size-4"></i>
                            <span>Nyalakan Kamera Pemindai</span>
                        </button>
                        <button 
                            x-show="scanning" 
                            @click="stopCamera()" 
                            type="button" 
                            class="flex-1 py-3 px-4 rounded-xl font-bold text-xs text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="camera-off" class="size-4"></i>
                            <span>Matikan Kamera</span>
                        </button>
                    </div>
                </div>

                <!-- Kolom Kanan (7 Kolom): Input Manual & Petunjuk Lapangan -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- Form Cek Manual Cepat -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                <i data-lucide="search" class="size-4 text-emerald-600"></i>
                                <span>Cek Kode Aset / Nomor Seri Manual</span>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Gunakan opsi ini jika stiker QR tergores, kotor, atau kamera tidak dapat digunakan.</p>
                        </div>

                        <form 
                            x-data="{ manualInput: '' }" 
                            @submit.prevent="if (manualInput.trim()) { $wire.handleScannedCode(manualInput); manualInput = ''; }" 
                            class="flex flex-col sm:flex-row gap-2.5"
                        >
                            <input 
                                type="text" 
                                x-model="manualInput" 
                                placeholder="Contoh: MED-001 atau nomor seri SN-88..." 
                                class="flex-1 h-11 px-4 text-xs font-mono font-bold uppercase bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600"
                            />
                            <button 
                                type="submit" 
                                class="h-11 px-6 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 transition-colors shrink-0 flex items-center justify-center gap-2 cursor-pointer shadow-xs"
                            >
                                <i data-lucide="search" class="size-4"></i>
                                <span>Periksa Alat</span>
                            </button>
                        </form>
                    </div>

                    <!-- Panduan Operasional Petugas Ruangan -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-3.5">
                        <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="info" class="size-4 text-emerald-600"></i>
                            <span>Panduan Alur Operasional Lapangan:</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600 dark:text-slate-300">
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-1">
                                <div class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <span class="size-5 rounded-full bg-emerald-700 text-white text-[10px] flex items-center justify-center font-bold">1</span>
                                    <span>Pindai / Scan</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Dekatkan kamera ke stiker QR bodi alat medis RS Ummi.</p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-1">
                                <div class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <span class="size-5 rounded-full bg-emerald-700 text-white text-[10px] flex items-center justify-center font-bold">2</span>
                                    <span>Verifikasi Status</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Pastikan alat berstatus laik pakai dan kalibrasi BPFK masih berlaku.</p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-1">
                                <div class="font-bold text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                    <span class="size-5 rounded-full bg-emerald-700 text-white text-[10px] flex items-center justify-center font-bold">3</span>
                                    <span>Lapor / Update</span>
                                </div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Bila ada kendala, tekan tombol Lapor Kerusakan ke tim teknisi IPSRS.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: KATALOG ALAT RUANGAN (RESPONSIVE WEB GRID) -->
        <!-- ========================================================================= -->
        @elseif ($activeTab === 'list')
            <div class="space-y-4">
                <!-- Action Filter Bar Web -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
                    <!-- Ruangan & Kondisi Filters -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Dropdown Ruangan -->
                        <select 
                            wire:model.live="filterLocation" 
                            class="h-10 px-3.5 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:border-emerald-600 shadow-xs"
                        >
                            <option value="">Semua Ruangan RS Ummi</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->assets_count }} Unit)</option>
                            @endforeach
                        </select>

                        <!-- Dropdown Kondisi -->
                        <select 
                            wire:model.live="filterCondition" 
                            class="h-10 px-3.5 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:border-emerald-600 shadow-xs"
                        >
                            <option value="">Semua Kondisi</option>
                            <option value="baik">Kondisi Baik (Laik Pakai)</option>
                            <option value="rusak_ringan">Rusak Ringan (Perlu Kalibrasi)</option>
                            <option value="rusak_berat">Rusak Berat (Tidak Berfungsi)</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="relative min-w-[260px] md:w-80">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="search" class="size-4"></i>
                        </span>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Cari kode, nama alat, merk, SN..." 
                            class="w-full h-10 pl-10 pr-9 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:border-emerald-600"
                        />
                        @if ($search)
                            <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="size-4"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Grid Kartu Alat Medis Web (3-4 Kolom pada Desktop) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @forelse ($assets as $asset)
                        <div 
                            wire:key="web-field-asset-{{ $asset->id }}"
                            wire:click="openDetail({{ $asset->id }})" 
                            class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-xs hover:border-emerald-500 hover:shadow-md transition-all cursor-pointer flex flex-col justify-between group"
                        >
                            <!-- Top: Foto Fisik Thumbnail -->
                            <div class="relative h-40 bg-slate-100 dark:bg-slate-800 overflow-hidden flex items-center justify-center border-b border-slate-100 dark:border-slate-800">
                                @if ($asset->image_url)
                                    <img src="{{ $asset->image_url }}" alt="{{ $asset->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                @else
                                    <div class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-600">
                                        <i data-lucide="stethoscope" class="size-10 mb-1"></i>
                                        <span class="text-[10px] font-semibold">Foto Belum Ada</span>
                                    </div>
                                @endif

                                <!-- Badge Kode Aset Pojok Kiri Atas -->
                                <span class="absolute top-2.5 left-2.5 font-mono text-[11px] font-bold px-2 py-0.5 rounded-md bg-slate-900/85 backdrop-blur-xs text-white shadow-xs">
                                    {{ $asset->asset_code }}
                                </span>

                                <!-- Badge Status Kondisi Pojok Kanan Atas -->
                                <div class="absolute top-2.5 right-2.5">
                                    @if ($asset->condition === 'baik')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500 text-white shadow-xs">
                                            Baik
                                        </span>
                                    @elseif ($asset->condition === 'rusak_ringan')
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500 text-white shadow-xs">
                                            Rusak Ringan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-500 text-white shadow-xs">
                                            Rusak Berat
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Body Card: Identitas & Ruangan -->
                            <div class="p-4 flex-1 flex flex-col justify-between space-y-2.5">
                                <div>
                                    <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide block truncate">
                                        {{ $asset->category->name ?? '-' }}
                                    </span>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 line-clamp-2 mt-0.5 leading-snug">
                                        {{ $asset->name }}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 truncate">
                                        {{ $asset->brand ?? '-' }} {{ $asset->model_type ? '· ' . $asset->model_type : '' }}
                                    </p>
                                </div>

                                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-[11px] flex items-center justify-between">
                                    <span class="text-slate-500 truncate">{{ $asset->location->name ?? '-' }}</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400 shrink-0 group-hover:translate-x-0.5 transition-transform flex items-center gap-0.5">
                                        <span>Periksa</span>
                                        <i data-lucide="chevron-right" class="size-3.5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-12 text-center bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-3">
                            <i data-lucide="inbox" class="size-12 text-slate-300 dark:text-slate-600 mx-auto"></i>
                            <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Tidak ada alat medis yang sesuai dengan kriteria</h4>
                            <p class="text-xs text-slate-500">Silakan ganti kata kunci pencarian atau bersihkan filter ruangan.</p>
                            <button 
                                wire:click="$set('search', ''); $set('filterLocation', ''); $set('filterCondition', '')" 
                                class="px-4 py-2 text-xs font-bold text-white bg-emerald-700 hover:bg-emerald-800 rounded-xl transition-colors cursor-pointer"
                            >
                                Bersihkan Filter Pencarian
                            </button>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination Footer -->
                <div class="pt-2">
                    {{ $assets->links() }}
                </div>
            </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: PENDAFTARAN ALAT BARU LAPANGAN (WEB 2-COLUMN FORM) -->
        <!-- ========================================================================= -->
        @elseif ($activeTab === 'create')
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs max-w-4xl mx-auto space-y-6">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                            <i data-lucide="plus-circle" class="size-5 text-emerald-600"></i>
                            <span>Formulir Pendaftaran Alat Medis Lapangan</span>
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan inventaris baru langsung dari ruang tindakan atau kamar pasien.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveAsset" class="space-y-4 text-xs">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Foto Fisik Alat (Dukungan Kamera) -->
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Foto Fisik Alat Medis</label>
                            <div class="flex items-center gap-4">
                                <label class="flex-1 cursor-pointer">
                                    <div class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-emerald-500/60 bg-emerald-50/40 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-300 text-xs font-bold flex items-center justify-center gap-2 hover:bg-emerald-100/50 transition-colors text-center">
                                        <i data-lucide="camera" class="size-4"></i>
                                        <span>Ambil Foto Langsung dari Kamera / Pilih File</span>
                                    </div>
                                    <input type="file" wire:model="image" accept="image/*" capture="environment" class="hidden" />
                                </label>

                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="size-14 rounded-xl object-cover border-2 border-emerald-500 shrink-0 shadow-xs" />
                                @endif
                            </div>
                            <span class="text-[11px] text-slate-400 block mt-1">Mendukung kamera HP langsung atau unggah foto JPG/PNG.</span>
                            @error('image') <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kategori Alat -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Aset <span class="text-rose-500">*</span></label>
                            <select wire:model.live="category_id" class="w-full h-10 px-3 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:border-emerald-600">
                                <option value="">Pilih Kategori...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Kode Aset & Tombol Generate -->
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-semibold text-slate-700 dark:text-slate-300">Kode Aset RS <span class="text-rose-500">*</span></label>
                                <button type="button" wire:click="generateAssetCode" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                    Auto-Generate Kode
                                </button>
                            </div>
                            <input type="text" wire:model="asset_code" placeholder="Contoh: MED-016" class="w-full h-10 px-3 font-mono uppercase bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                            @error('asset_code') <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nama Alat -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Alat Medis <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="name" placeholder="Contoh: Infusion Pump Terumo LM-700" class="w-full h-10 px-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                            @error('name') <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Ruangan Penempatan -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Ruangan Penempatan RS <span class="text-rose-500">*</span></label>
                            <select wire:model="location_id" class="w-full h-10 px-3 border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:border-emerald-600">
                                <option value="">Pilih Ruangan...</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->building }})</option>
                                @endforeach
                            </select>
                            @error('location_id') <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Merk -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Merk / Brand</label>
                            <input type="text" wire:model="brand" placeholder="Contoh: GE Healthcare, Mindray, Siemens" class="w-full h-10 px-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                        </div>

                        <!-- Tipe / Model -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tipe / Model</label>
                            <input type="text" wire:model="model_type" placeholder="Contoh: BeneHeart D3" class="w-full h-10 px-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                        </div>

                        <!-- Nomor Seri -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Seri (Serial Number)</label>
                            <input type="text" wire:model="serial_number" placeholder="Contoh: SN-DF-99210-XC" class="w-full h-10 px-3 font-mono bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                        </div>

                        <!-- Kondisi Awal -->
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kondisi Awal Alat <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-3 gap-2">
                                <button 
                                    type="button" 
                                    wire:click="$set('condition', 'baik')" 
                                    class="py-2.5 rounded-xl border text-center font-bold text-xs transition-colors {{ $condition === 'baik' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}"
                                >
                                    Baik
                                </button>
                                <button 
                                    type="button" 
                                    wire:click="$set('condition', 'rusak_ringan')" 
                                    class="py-2.5 rounded-xl border text-center font-bold text-xs transition-colors {{ $condition === 'rusak_ringan' ? 'bg-amber-500 text-white border-amber-500 shadow-xs' : 'bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}"
                                >
                                    Rusak Ringan
                                </button>
                                <button 
                                    type="button" 
                                    wire:click="$set('condition', 'rusak_berat')" 
                                    class="py-2.5 rounded-xl border text-center font-bold text-xs transition-colors {{ $condition === 'rusak_berat' ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}"
                                >
                                    Rusak Berat
                                </button>
                            </div>
                        </div>

                        <!-- Catatan Lapangan -->
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Catatan Tambahan Spesifikasi / Kondisi Fisik</label>
                            <textarea wire:model="notes" rows="2" placeholder="Catatan khusus teknis atau nomor inventaris ruangan..." class="w-full p-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600"></textarea>
                        </div>
                    </div>

                    <!-- Tombol Simpan Aset -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto px-8 py-3 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 active:scale-98 transition-all shadow-md shadow-emerald-900/20 flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="save" class="size-4"></i>
                            <span wire:loading.remove wire:target="saveAsset">Simpan Alat Medis ke SIMRS</span>
                            <span wire:loading wire:target="saveAsset">Menyimpan Data...</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </main>

    <!-- ========================================================================= -->
    <!-- MODAL DETAIL / PEMERIKSAAN ALAT MEDIS -->
    <!-- ========================================================================= -->
    @if ($isDetailOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-lg w-full overflow-hidden">
                <!-- Header Modal -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-[#E9FAF3] dark:bg-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="font-mono text-xs font-bold px-2.5 py-0.5 rounded-md bg-emerald-800 text-white shadow-2xs">
                            {{ $selectedAsset->asset_code }}
                        </span>
                        @if ($selectedAsset->condition === 'baik')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300">
                                Kondisi Baik
                            </span>
                        @elseif ($selectedAsset->condition === 'rusak_ringan')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 dark:bg-amber-950/70 text-amber-800 dark:text-amber-300">
                                Butuh Kalibrasi / Rusak Ringan
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 dark:bg-rose-950/70 text-rose-800 dark:text-rose-300">
                                Rusak Berat (Mogok)
                            </span>
                        @endif
                    </div>
                    <button wire:click="closeDetail" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg">
                        <i data-lucide="x" class="size-4.5"></i>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="p-6 space-y-4 text-xs">
                    <!-- Foto & Identitas -->
                    <div class="flex gap-4 items-start">
                        <div class="size-24 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                            @if ($selectedAsset->image_url)
                                <img src="{{ $selectedAsset->image_url }}" alt="{{ $selectedAsset->name }}" class="w-full h-full object-cover" />
                            @else
                                <i data-lucide="stethoscope" class="size-10 text-slate-300 dark:text-slate-600"></i>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">
                                {{ $selectedAsset->category->name ?? '-' }}
                            </span>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-snug mt-0.5">
                                {{ $selectedAsset->name }}
                            </h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 truncate">
                                {{ $selectedAsset->brand ?? '-' }} {{ $selectedAsset->model_type ? '· ' . $selectedAsset->model_type : '' }}
                            </p>
                            <p class="text-[11px] font-mono text-slate-400 mt-0.5 truncate">
                                SN: {{ $selectedAsset->serial_number ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Parameter Ruangan & PIC -->
                    <div class="grid grid-cols-2 gap-2.5 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-[11px]">
                        <div>
                            <span class="text-[10px] text-slate-400 block uppercase font-medium">Ruangan & Gedung</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">{{ $selectedAsset->location->name ?? '-' }}</span>
                            <span class="text-[10px] text-slate-400 block truncate">{{ $selectedAsset->location->building ?? '' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block uppercase font-medium">Penanggung Jawab (PIC)</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">{{ $selectedAsset->location->pic_name ?? '-' }}</span>
                            <span class="text-[10px] text-emerald-600 block font-medium">IPSRS RS Ummi</span>
                        </div>
                    </div>

                    <!-- Tindakan Cepat Lapangan -->
                    <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block">Tindakan Cepat Lapangan:</span>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <!-- Tombol Lapor Rusak -->
                            <button 
                                wire:click="openReportModal" 
                                type="button" 
                                class="py-2.5 px-3 rounded-xl font-bold text-xs text-amber-900 dark:text-amber-200 bg-amber-100 hover:bg-amber-200 dark:bg-amber-950/70 border border-amber-300 dark:border-amber-800 flex items-center justify-center gap-1.5 cursor-pointer transition-colors"
                            >
                                <i data-lucide="triangle-alert" class="size-4 text-amber-600"></i>
                                <span>Lapor Kerusakan Alat</span>
                            </button>

                            <!-- Tombol Nyatakan Baik -->
                            <button 
                                wire:click="quickSetCondition('baik')" 
                                type="button" 
                                class="py-2.5 px-3 rounded-xl font-bold text-xs text-emerald-800 dark:text-emerald-200 bg-emerald-100 hover:bg-emerald-200 dark:bg-emerald-950/70 border border-emerald-300 dark:border-emerald-800 flex items-center justify-center gap-1.5 cursor-pointer transition-colors"
                            >
                                <i data-lucide="check-circle-2" class="size-4 text-emerald-600"></i>
                                <span>Tandai Laik Pakai (Baik)</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="px-6 py-3 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                    <button 
                        wire:click="closeDetail" 
                        class="px-4 py-2 rounded-xl font-bold text-xs text-slate-700 dark:text-slate-200 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL LAPOR KERUSAKAN KE TIM IPSRS -->
    <!-- ========================================================================= -->
    @if ($isReportModalOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-md w-full p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Kirim Laporan Kerusakan Alat</h3>
                        <p class="text-[11px] text-slate-500">{{ $selectedAsset->name }} ({{ $selectedAsset->asset_code }})</p>
                    </div>
                    <button wire:click="closeReportModal" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>

                <form wire:submit.prevent="submitDefectReport" class="space-y-3.5 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tingkat Kerusakan Alat</label>
                        <select wire:model="report_condition" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100">
                            <option value="rusak_ringan">Rusak Ringan (Alat Masih Nyala / Butuh Kalibrasi)</option>
                            <option value="rusak_berat">Rusak Berat (Alat Mati Total / Tidak Berfungsi)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Gejala Kerusakan / Catatan Temuan <span class="text-rose-500">*</span></label>
                        <textarea wire:model="report_notes" rows="3" placeholder="Contoh: Tekanan pompa tidak stabil, kabel sensor putus, atau layar LCD kedip-kedip..." class="w-full p-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600"></textarea>
                        @error('report_notes') <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Petugas Pelapor di Ruangan</label>
                        <input type="text" wire:model="report_technician" placeholder="Nama perawat atau teknisi penemu kerusakan" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100" />
                    </div>

                    <div class="flex items-center gap-2.5 pt-2">
                        <button type="button" wire:click="closeReportModal" class="flex-1 py-2.5 rounded-xl font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                            <i data-lucide="send" class="size-3.5"></i>
                            <span>Kirim Laporan ke IPSRS</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
