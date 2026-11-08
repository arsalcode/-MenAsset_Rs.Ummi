<div class="w-full">
    <!-- ========================================================================= -->
    <!-- SUB-NAVIGASI TAB WEB KHUSUS LAPANGAN -->
    <!-- ========================================================================= -->
    <div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-6 py-2 sticky top-16 z-40">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-1 overflow-x-auto no-scrollbar py-0.5">
                <!-- Tab 1: Scan QR & Cek Alat -->
                <button 
                    type="button" 
                    wire:click="setTab('scan')" 
                    class="admin-tab-btn {{ $activeTab === 'scan' ? 'active' : '' }}" 
                    title="Scan Stiker QR & Verifikasi Fisik"
                >
                    <i data-lucide="scan" class="size-4"></i>
                    <span class="tab-label">Scan QR & Cek Alat</span>
                </button>

                <!-- Tab 2: Ceklis Ruangan -->
                <button 
                    type="button" 
                    wire:click="setTab('room')" 
                    class="admin-tab-btn {{ $activeTab === 'room' ? 'active' : '' }}" 
                    title="Ceklis & Inventaris Ruangan"
                >
                    <i data-lucide="door-open" class="size-4"></i>
                    <span class="tab-label">Ceklis Ruangan</span>
                </button>

                <!-- Tab 3: Tiket Laporan Lapangan -->
                <button 
                    type="button" 
                    wire:click="setTab('reports')" 
                    class="admin-tab-btn {{ $activeTab === 'reports' ? 'active' : '' }}" 
                    title="Status Tiket Laporan Kerusakan"
                >
                    <i data-lucide="clipboard-list" class="size-4"></i>
                    <span class="tab-label">Tiket Laporan Lapangan</span>
                    @if (count($recentReports) > 0)
                        <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300">
                            {{ count($recentReports) }}
                        </span>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- KONTEN UTAMA HALAMAN LAPANGAN -->
    <!-- ========================================================================= -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 space-y-6">

        <!-- Notifikasi Pesan Flash -->
        @if ($feedbackMessage)
            <div class="p-3.5 rounded-xl border flex items-center justify-between gap-3 text-xs font-medium transition-all shadow-xs {{ $feedbackType === 'success' ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200' : ($feedbackType === 'warning' ? 'bg-amber-50 dark:bg-amber-950/40 border-amber-300 dark:border-amber-800 text-amber-800 dark:text-amber-200' : 'bg-rose-50 dark:bg-rose-950/40 border-rose-300 dark:border-rose-800 text-rose-800 dark:text-rose-200') }}">
                <div class="flex items-center gap-2">
                    <i data-lucide="{{ $feedbackType === 'success' ? 'check-circle' : ($feedbackType === 'warning' ? 'alert-triangle' : 'alert-circle') }}" class="size-4 shrink-0"></i>
                    <span>{{ $feedbackMessage }}</span>
                </div>
                <button wire:click="$set('feedbackMessage', null)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 cursor-pointer">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- TAB 1: SCAN QR & CEK ALAT MEDIS LANGSUNG (SPLIT VIEW) -->
        <!-- ========================================================================= -->
        @if ($activeTab === 'scan')
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Kolom Kiri (5 Kolom): Live Camera QR Scanner Viewport -->
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
                                this.scanError = 'Akses kamera tidak diizinkan atau tidak terdeteksi. Silakan aktifkan izin kamera atau gunakan pencarian kode manual di sisi kanan.';
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

                    <!-- Area Kamera Scanner -->
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

                    <!-- Scan Error Alert -->
                    <div x-show="scanError" x-cloak class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 text-xs text-left flex items-start gap-2">
                        <i data-lucide="alert-triangle" class="size-4 shrink-0 mt-0.5"></i>
                        <span x-text="scanError"></span>
                    </div>

                    <!-- Tombol Kontrol Kamera -->
                    <div class="flex justify-center gap-2 pt-2">
                        <button 
                            type="button" 
                            x-show="!scanning" 
                            @click="startCamera()" 
                            class="px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 active:scale-98 transition-all shadow-md shadow-emerald-900/20 flex items-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="video" class="size-4"></i>
                            <span>Nyalakan Kamera</span>
                        </button>

                        <button 
                            type="button" 
                            x-show="scanning" 
                            @click="stopCamera()" 
                            class="px-6 py-2.5 rounded-xl font-bold text-xs text-white bg-rose-600 hover:bg-rose-700 active:scale-98 transition-all shadow-md shadow-rose-900/20 flex items-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="video-off" class="size-4"></i>
                            <span>Matikan Kamera</span>
                        </button>
                    </div>
                </div>

                <!-- Kolom Kanan (7 Kolom): Input Manual & Hasil Informasi Alat -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- Form Input Manual Kode Aset -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 sm:p-5 shadow-xs">
                        <form wire:submit.prevent="handleScannedCode" class="flex gap-2">
                            <div class="relative flex-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <i data-lucide="search" class="size-4"></i>
                                </div>
                                <input 
                                    type="text" 
                                    wire:model="manualCode" 
                                    placeholder="Contoh: MED-001..." 
                                    class="w-full h-10 pl-10 pr-3.5 py-2 text-xs bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 border border-slate-200 dark:border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-500 dark:focus:border-emerald-200 transition-all font-mono uppercase text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-xs"
                                />
                            </div>
                            <button 
                                type="submit" 
                                class="px-5 h-10 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 active:scale-98 transition-all shrink-0 flex items-center gap-1.5 cursor-pointer shadow-xs"
                            >
                                <i data-lucide="arrow-right" class="size-4"></i>
                                <span>Periksa</span>
                            </button>
                        </form>

                        <!-- Tombol Demo Kode Cepat -->
                        <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 flex-wrap">
                            <span class="text-[11px] text-slate-400 font-medium">Contoh Kode:</span>
                            @foreach (['MED-001', 'MED-002', 'MED-003', 'NON-001'] as $demoCode)
                                <button 
                                    type="button" 
                                    wire:click="handleScannedCode('{{ $demoCode }}')" 
                                    class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-emerald-100 hover:text-emerald-800 dark:hover:bg-emerald-950 dark:hover:text-emerald-300 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer"
                                >
                                    {{ $demoCode }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kartu Hasil Pemindaian Alat Medis -->
                    @if ($selectedAsset)
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border-2 border-emerald-500/80 p-5 shadow-sm space-y-4">
                            <div class="flex items-start justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-600/30">
                                            {{ $selectedAsset->asset_code }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ $selectedAsset->condition === 'baik' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300' }}">
                                            {{ strtoupper(str_replace('_', ' ', $selectedAsset->condition)) }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mt-1.5">{{ $selectedAsset->name }}</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-2">
                                        <span><i data-lucide="map-pin" class="size-3.5 inline text-emerald-600"></i> {{ $selectedAsset->location->name ?? 'Belum ada ruangan' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $selectedAsset->category->name ?? 'Kategori Umum' }}</span>
                                    </p>
                                </div>

                                @if ($selectedAsset->image_path)
                                    <img src="{{ asset('storage/' . $selectedAsset->image_path) }}" alt="{{ $selectedAsset->name }}" class="size-16 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-xs shrink-0" />
                                @endif
                            </div>

                            <!-- Ringkasan Spesifikasi & Kalibrasi -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700/60">
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Merek / Brand</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $selectedAsset->brand ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700/60">
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Nomor Seri (SN)</span>
                                    <span class="font-mono font-bold text-slate-800 dark:text-slate-200">{{ $selectedAsset->serial_number ?: '-' }}</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-800/60 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700/60 col-span-2 sm:col-span-1">
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Status Operasional</span>
                                    <span class="font-bold {{ $selectedAsset->status === 'aktif' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ $selectedAsset->status === 'aktif' ? 'Siap Pakai' : 'Dalam Pemeliharaan' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Tombol Tindakan Langsung untuk Petugas Lapangan -->
                            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-2.5 border-t border-slate-100 dark:border-slate-800">
                                <button 
                                    type="button" 
                                    wire:click="confirmNormalCondition({{ $selectedAsset->id }})" 
                                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 transition-colors flex items-center justify-center gap-1.5 cursor-pointer shadow-xs"
                                    title="Konfirmasi alat telah dicek secara fisik dan berfungsi normal"
                                >
                                    <i data-lucide="check-circle" class="size-4"></i>
                                    <span>Konfirmasi Fisik: Kondisi Normal</span>
                                </button>

                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <button 
                                        type="button" 
                                        wire:click="openReportModal({{ $selectedAsset->id }})" 
                                        class="flex-1 sm:flex-initial px-4 py-2.5 rounded-xl font-bold text-xs text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                                        title="Lapor kerusakan ke Admin IPSRS"
                                    >
                                        <i data-lucide="alert-triangle" class="size-4"></i>
                                        <span>Lapor Rusak</span>
                                    </button>

                                    <button 
                                        type="button" 
                                        wire:click="openDetail({{ $selectedAsset->id }})" 
                                        class="px-3 py-2.5 rounded-xl font-bold text-xs text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition-colors flex items-center justify-center gap-1 cursor-pointer"
                                        title="Lihat riwayat servis & kalibrasi lengkap"
                                    >
                                        <i data-lucide="file-text" class="size-4"></i>
                                        <span>Detail</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Tampilan Petunjuk Standby -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 p-8 text-center text-slate-400 space-y-2">
                            <i data-lucide="scan-line" class="size-10 mx-auto text-slate-300 dark:text-slate-700"></i>
                            <h4 class="text-sm font-bold text-slate-600 dark:text-slate-300">Belum Ada Alat yang Dipindai</h4>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto">Nyalakan kamera di sebelah kiri untuk scan stiker QR pada alat medis, atau ketik kode manual di kotak atas.</p>
                        </div>
                    @endif
                </div>
            </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: CEKLIS & INVENTARIS RUANGAN -->
        <!-- ========================================================================= -->
        @elseif ($activeTab === 'room')
            <div class="space-y-4">
                <!-- Action Bar Ruangan (Sejajar 1 Baris Persis Admin) -->
                <div class="no-print flex flex-col xl:flex-row xl:items-center gap-2.5">
                    <!-- Group 1 (Kiri): Dropdown Ruangan & Dropdown Kondisi -->
                    <div class="flex items-center gap-2 shrink-0 flex-wrap sm:flex-nowrap">
                        <!-- Dropdown Pilihan Ruangan (Custom Alpine.js Popover matching Admin) -->
                        @php
                            $currentSelectedLocation = $locations->firstWhere('id', $selectedLocationId);
                            $selectedLocationName = $currentSelectedLocation ? $currentSelectedLocation->name . ' (' . $currentSelectedLocation->assets_count . ' alat)' : 'Pilih Ruangan RS';
                        @endphp
                        <div x-data="{ open: false }" class="relative shrink-0" @click.outside="open = false" @keydown.escape.window="open = false">
                            <button 
                                type="button" 
                                @click="open = !open" 
                                :class="open ? 'ring-2 ring-emerald-500/50 border-emerald-500 dark:border-emerald-200' : ''"
                                class="h-10 pl-3.5 pr-8 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-200 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all cursor-pointer inline-flex items-center gap-2 select-none shadow-xs"
                            >
                                <span class="truncate max-w-[200px]">{{ $selectedLocationName }}</span>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-400">
                                    <span class="size-4 inline-flex items-center justify-center transition-transform duration-200 origin-center" :class="open ? 'rotate-180' : ''">
                                        <i data-lucide="chevron-down" class="size-4"></i>
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
                                class="absolute left-0 top-full mt-1.5 w-72 max-h-72 overflow-y-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-slate-950/60 p-1.5 z-50 space-y-0.5"
                            >
                                @foreach ($locations as $loc)
                                    <button 
                                        type="button" 
                                        wire:click="$set('selectedLocationId', '{{ $loc->id }}')" 
                                        @click="open = false" 
                                        class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ (string)$selectedLocationId === (string)$loc->id ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                                    >
                                        <div class="pr-2 min-w-0">
                                            <span class="block truncate">{{ $loc->name }}</span>
                                            @if ($loc->building)
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 block -mt-0.5">{{ $loc->building }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">{{ $loc->assets_count }} Unit</span>
                                            @if ((string)$selectedLocationId === (string)$loc->id)
                                                <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Dropdown Kondisi (Custom Alpine.js Popover matching Admin) -->
                        @php
                            $conditionOptions = [
                                '' => 'Semua Kondisi',
                                'baik' => 'Kondisi Baik',
                                'rusak_ringan' => 'Rusak Ringan',
                                'rusak_berat' => 'Rusak Berat'
                            ];
                            $selectedConditionLabel = $conditionOptions[$filterCondition] ?? 'Semua Kondisi';
                        @endphp
                        <div x-data="{ open: false }" class="relative shrink-0" @click.outside="open = false" @keydown.escape.window="open = false">
                            <button 
                                type="button" 
                                @click="open = !open" 
                                :class="open ? 'ring-2 ring-emerald-500/50 border-emerald-500 dark:border-emerald-200' : ''"
                                class="h-10 pl-3.5 pr-8 text-xs font-semibold rounded-xl border transition-all cursor-pointer inline-flex items-center gap-2 select-none shadow-xs {{ $filterCondition ? 'bg-emerald-50/80 dark:bg-emerald-950/40 border-emerald-500/50 text-emerald-800 dark:text-emerald-300' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-200 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800' }}"
                            >
                                <span class="truncate">{{ $selectedConditionLabel }}</span>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 dark:text-slate-400">
                                    <span class="size-4 inline-flex items-center justify-center transition-transform duration-200 origin-center" :class="open ? 'rotate-180' : ''">
                                        <i data-lucide="chevron-down" class="size-4"></i>
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
                                class="absolute left-0 top-full mt-1.5 w-48 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl shadow-slate-900/10 dark:shadow-slate-950/60 p-1.5 z-50 space-y-0.5"
                            >
                                @foreach ($conditionOptions as $value => $label)
                                    <button 
                                        type="button" 
                                        wire:click="$set('filterCondition', '{{ $value }}')" 
                                        @click="open = false" 
                                        class="w-full px-3 py-2 text-xs font-medium rounded-xl text-left flex items-center justify-between transition-colors {{ (string)$filterCondition === (string)$value ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}"
                                    >
                                        <span>{{ $label }}</span>
                                        @if ((string)$filterCondition === (string)$value)
                                            <i data-lucide="check" class="size-4 text-emerald-600 dark:text-emerald-400 font-bold"></i>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Group 2 (Tengah): Search Input Fleksibel Sejajar -->
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i data-lucide="search" class="size-4"></i>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Cari nama alat, merek, atau nomor seri di ruangan ini..." 
                            class="w-full h-10 pl-10 pr-9 py-2 text-xs bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 border border-slate-200 dark:border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-500 dark:focus:border-emerald-200 transition-all font-medium text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 shadow-xs"
                        />
                        @if ($search)
                            <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                                <i data-lucide="x" class="size-4"></i>
                            </button>
                        @endif
                    </div>

                    <!-- Group 3 (Kanan): Tombol Refresh Segarkan Data (Tanpa Kotak Border Persis Admin) -->
                    <div class="flex items-center gap-1 shrink-0">
                        <div class="relative group z-20 hover:z-50">
                            <button 
                                type="button" 
                                wire:click="refreshData" 
                                class="size-10 rounded-xl text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 inline-flex items-center justify-center transition-all hover:scale-110 active:scale-95 cursor-pointer shrink-0"
                                title="Segarkan Data"
                                aria-label="Segarkan Data"
                            >
                                <i data-lucide="rotate-cw" class="size-5 transition-transform duration-500" wire:loading.class="animate-spin" wire:target="refreshData"></i>
                            </button>
                            <div class="pointer-events-none absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2.5 py-1 text-[11px] font-semibold text-white bg-slate-900 dark:bg-slate-800 rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 group-hover:translate-y-0.5 transition-all duration-200 z-50">
                                Segarkan Data
                                <span class="absolute bottom-full left-1/2 -translate-x-1/2 border-4 border-transparent border-b-slate-900 dark:border-b-slate-800"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Statistik Ruangan Terpilih (Persis Kartu Admin Alkamart) -->
                @if ($selectedLocationId)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Card 1: Total Alat di Ruangan -->
                        <div class="report-card green">
                            <div class="report-icon">
                                <i data-lucide="package" class="size-7 text-white"></i>
                            </div>
                            <div class="report-info">
                                <span class="report-label">Total Alat di Ruangan</span>
                                <h3 class="report-amount">{{ $roomStats['total'] }} <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Unit</span></h3>
                                <span class="report-sublabel">Terdata di {{ $currentSelectedLocation?->name ?? 'Ruangan' }}</span>
                            </div>
                        </div>

                        <!-- Card 2: Kondisi Baik & Siap Pakai -->
                        <div class="report-card green">
                            <div class="report-icon">
                                <i data-lucide="check-circle-2" class="size-7 text-white"></i>
                            </div>
                            <div class="report-info">
                                <span class="report-label">Kondisi Baik & Siap Pakai</span>
                                <h3 class="report-amount text-emerald-700 dark:text-emerald-400">{{ $roomStats['ready'] }} <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Unit</span></h3>
                                <span class="report-sublabel">{{ $roomStats['total'] > 0 ? round(($roomStats['ready'] / $roomStats['total']) * 100, 1) : 0 }}% alat laik pakai & prima</span>
                            </div>
                        </div>

                        <!-- Card 3: Perlu Perbaikan / Servis -->
                        <div class="report-card green">
                            <div class="report-icon">
                                <i data-lucide="wrench" class="size-7 text-white"></i>
                            </div>
                            <div class="report-info">
                                <span class="report-label">Perlu Perbaikan / Servis</span>
                                <h3 class="report-amount text-rose-600 dark:text-rose-400">{{ $roomStats['maintenance'] }} <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Unit</span></h3>
                                <span class="report-sublabel">Butuh perhatian teknisi IPSRS</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Daftar Kartu Alat Medis Ruangan (4 Kolom Grid) -->
                @if ($roomAssets && count($roomAssets) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach ($roomAssets as $asset)
                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-xs hover:border-emerald-500/60 transition-all flex flex-col justify-between space-y-3">
                                <div>
                                    <!-- Header Kartu: Kode & Badge Status -->
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            {{ $asset->asset_code }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $asset->condition === 'baik' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300' }}">
                                            {{ strtoupper(str_replace('_', ' ', $asset->condition)) }}
                                        </span>
                                    </div>

                                    <!-- Nama & Merek Alat -->
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 mt-2 line-clamp-2" title="{{ $asset->name }}">
                                        {{ $asset->name }}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        {{ $asset->brand ?: 'Tanpa Merek' }} {{ $asset->model_type ? '• ' . $asset->model_type : '' }}
                                    </p>

                                    @if ($asset->serial_number)
                                        <p class="text-[10px] font-mono text-slate-400 mt-1">
                                            SN: {{ $asset->serial_number }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Aksi Cepat Lapangan -->
                                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-1.5">
                                    <button 
                                        type="button" 
                                        wire:click="confirmNormalCondition({{ $asset->id }})" 
                                        class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 transition-colors flex items-center gap-1 cursor-pointer"
                                        title="Verifikasi fisik alat normal & siap pakai"
                                    >
                                        <i data-lucide="check" class="size-3.5"></i>
                                        <span>Ceklis OK</span>
                                    </button>

                                    <div class="flex items-center gap-1">
                                        <button 
                                            type="button" 
                                            wire:click="openReportModal({{ $asset->id }})" 
                                            class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/60 hover:bg-rose-100 transition-colors flex items-center gap-1 cursor-pointer"
                                            title="Lapor kerusakan"
                                        >
                                            <i data-lucide="alert-triangle" class="size-3.5"></i>
                                            <span>Lapor</span>
                                        </button>

                                        <button 
                                            type="button" 
                                            wire:click="openDetail({{ $asset->id }})" 
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                                            title="Detail alat"
                                        >
                                            <i data-lucide="info" class="size-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-2">
                        {{ $roomAssets->links() }}
                    </div>
                @elseif ($selectedLocationId)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-8 text-center text-slate-400 space-y-2">
                        <i data-lucide="inbox" class="size-10 mx-auto text-slate-300 dark:text-slate-700"></i>
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Tidak ada alat medis yang sesuai dengan kriteria di ruangan ini.</p>
                    </div>
                @else
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-300 dark:border-slate-800 p-8 text-center text-slate-400 space-y-2">
                        <i data-lucide="door-open" class="size-10 mx-auto text-slate-300 dark:text-slate-700"></i>
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Silakan pilih ruangan terlebih dahulu pada dropdown di atas.</p>
                    </div>
                @endif
            </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: STATUS & RIWAYAT TIKET LAPORAN LAPANGAN -->
        <!-- ========================================================================= -->
        @elseif ($activeTab === 'reports')
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-xs space-y-6">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                            <i data-lucide="clipboard-list" class="size-5 text-emerald-600"></i>
                            <span>Pusat Pantau Tiket Laporan Kerusakan</span>
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantau status penanganan alat medis yang dilaporkan rusak oleh tim lapangan ke teknisi IPSRS.</p>
                    </div>

                    <button 
                        type="button" 
                        wire:click="setTab('scan')" 
                        class="px-4 py-2 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 transition-colors flex items-center gap-1.5 cursor-pointer self-start sm:self-auto shadow-xs"
                    >
                        <i data-lucide="plus" class="size-4"></i>
                        <span>Buat Laporan Baru (Scan QR)</span>
                    </button>
                </div>

                <!-- Daftar Tiket Laporan -->
                @if (count($recentReports) > 0)
                    <div class="space-y-3">
                        @foreach ($recentReports as $report)
                            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 transition-colors bg-slate-50/50 dark:bg-slate-800/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                            {{ $report->asset->asset_code ?? 'ASET' }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-900 dark:text-slate-100">
                                            {{ $report->asset->name ?? 'Alat Medis' }}
                                        </span>
                                        <span class="text-[11px] text-slate-400">&bull;</span>
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                            <i data-lucide="map-pin" class="size-3 text-emerald-600"></i>
                                            {{ $report->asset->location->name ?? 'Ruangan' }}
                                        </span>
                                    </div>

                                    <!-- Catatan Gejala Kerusakan -->
                                    <p class="text-xs text-slate-700 dark:text-slate-300 font-medium">
                                        {{ $report->notes }}
                                    </p>

                                    <!-- Pelapor & Waktu -->
                                    <div class="text-[11px] text-slate-400 flex items-center gap-3">
                                        <span>Dilaporkan oleh: <strong class="text-slate-600 dark:text-slate-300">{{ $report->vendor_technician }}</strong></span>
                                        <span>&bull;</span>
                                        <span>{{ \Carbon\Carbon::parse($report->maintenance_date)->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                </div>

                                <!-- Status Tiket -->
                                <div class="shrink-0 flex items-center gap-2">
                                    @if ($report->status === 'dalam_proses')
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-300 dark:border-amber-800 flex items-center gap-1.5">
                                            <span class="size-2 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Sedang Ditangani IPSRS</span>
                                        </span>
                                    @elseif ($report->status === 'selesai')
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 flex items-center gap-1.5">
                                            <i data-lucide="check" class="size-3.5"></i>
                                            <span>Selesai - Siap Pakai</span>
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-slate-400 space-y-2">
                        <i data-lucide="check-circle-2" class="size-10 mx-auto text-emerald-500"></i>
                        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Tidak Ada Laporan Kerusakan</h4>
                        <p class="text-xs text-slate-400">Semua alat medis saat ini dalam kondisi baik atau laporan sebelumnya telah selesai ditangani.</p>
                    </div>
                @endif
            </div>
        @endif

    </main>

    <!-- ========================================================================= -->
    <!-- MODAL DETAIL / PEMERIKSAAN ALAT MEDIS -->
    <!-- ========================================================================= -->
    @if ($isDetailOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-5 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-start justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-600/30">
                            {{ $selectedAsset->asset_code }}
                        </span>
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $selectedAsset->name }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $selectedAsset->location->name ?? 'Tanpa Ruangan' }}</p>
                    </div>
                    <button wire:click="closeDetail" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 cursor-pointer">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Kategori</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $selectedAsset->category->name ?? '-' }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Kondisi Fisik</span>
                        <span class="font-bold {{ $selectedAsset->condition === 'baik' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ strtoupper(str_replace('_', ' ', $selectedAsset->condition)) }}
                        </span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Merek / Seri</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $selectedAsset->brand ?: '-' }} ({{ $selectedAsset->serial_number ?: 'SN -' }})</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200 dark:border-slate-700/60">
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Status Operasional</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ ucfirst($selectedAsset->status) }}</span>
                    </div>
                </div>

                <!-- Riwayat Servis / Kalibrasi Terakhir -->
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">Riwayat Pemeriksaan Terakhir</h5>
                    @if ($selectedAsset->maintenanceLogs && count($selectedAsset->maintenanceLogs) > 0)
                        <div class="max-h-36 overflow-y-auto space-y-2 pr-1">
                            @foreach ($selectedAsset->maintenanceLogs->take(3) as $log)
                                <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 text-[11px]">
                                    <div class="flex items-center justify-between font-semibold">
                                        <span class="capitalize text-slate-800 dark:text-slate-200">{{ str_replace('_', ' ', $log->type) }}</span>
                                        <span class="text-slate-400">{{ \Carbon\Carbon::parse($log->maintenance_date)->format('d/m/Y') }}</span>
                                    </div>
                                    <p class="text-slate-600 dark:text-slate-400 mt-0.5">{{ $log->notes }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic">Belum ada catatan riwayat pemeliharaan.</p>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button 
                        type="button" 
                        wire:click="openReportModal({{ $selectedAsset->id }})" 
                        class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 transition-colors flex items-center gap-1.5 cursor-pointer"
                    >
                        <i data-lucide="alert-triangle" class="size-4"></i>
                        <span>Lapor Rusak</span>
                    </button>
                    <button 
                        type="button" 
                        wire:click="closeDetail" 
                        class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 transition-colors cursor-pointer"
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-start justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="size-9 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                            <i data-lucide="alert-triangle" class="size-5"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Lapor Kerusakan Alat Medis</h3>
                            <p class="text-[11px] text-slate-500">Tiket diteruskan ke Tim Teknisi IPSRS</p>
                        </div>
                    </div>
                    <button wire:click="closeReportModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 cursor-pointer">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- Info Alat Medis -->
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-800 text-xs space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-emerald-700 dark:text-emerald-400">{{ $selectedAsset->asset_code }}</span>
                        <span class="text-slate-500">{{ $selectedAsset->location->name ?? 'Ruangan' }}</span>
                    </div>
                    <div class="font-bold text-slate-800 dark:text-slate-200">{{ $selectedAsset->name }}</div>
                </div>

                <form wire:submit.prevent="submitDefectReport" class="space-y-3.5 text-xs">
                    <!-- Tingkat Kerusakan -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tingkat Kendala / Kerusakan <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer {{ $report_condition === 'rusak_ringan' ? 'border-amber-500 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-200 font-bold' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400' }}">
                                <input type="radio" wire:model.live="report_condition" value="rusak_ringan" class="text-amber-600" />
                                <span>Rusak Ringan</span>
                            </label>

                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer {{ $report_condition === 'rusak_berat' ? 'border-rose-500 bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-200 font-bold' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400' }}">
                                <input type="radio" wire:model.live="report_condition" value="rusak_berat" class="text-rose-600" />
                                <span>Rusak Berat</span>
                            </label>
                        </div>
                    </div>

                    <!-- Gejala Kerusakan -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Gejala Kerusakan / Masalah Fisik <span class="text-rose-500">*</span></label>
                        <textarea 
                            wire:model="report_notes" 
                            rows="3" 
                            placeholder="Contoh: Layar monitor kedip-kedip, kabel sensor oksigen korslet, baterai tidak mau mengisi..." 
                            class="w-full p-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-rose-600"
                        ></textarea>
                        @error('report_notes') <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Pelapor -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Petugas / Perawat Pelapor <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            wire:model="report_reporter" 
                            placeholder="Contoh: Ns. Rahma (ICU)" 
                            class="w-full h-10 px-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-rose-600"
                        />
                        @error('report_reporter') <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100 dark:border-slate-800">
                        <button 
                            type="button" 
                            wire:click="closeReportModal" 
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-98 transition-all flex items-center gap-1.5 cursor-pointer shadow-md shadow-rose-900/20"
                        >
                            <i data-lucide="send" class="size-4"></i>
                            <span wire:loading.remove wire:target="submitDefectReport">Kirim Laporan ke IPSRS</span>
                            <span wire:loading wire:target="submitDefectReport">Mengirim...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL POP-UP PROFESIONAL: KONFIRMASI TIKET KERUSAKAN RESMI TERBIT -->
    <!-- ========================================================================= -->
    @if ($isSuccessModalOpen && $submittedTicket)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl space-y-5 animate-in fade-in zoom-in-95 duration-200 relative overflow-hidden">
                <!-- Header Sukses dengan Icon & Animasi -->
                <div class="flex flex-col items-center text-center">
                    <div class="size-16 rounded-2xl bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-lg shadow-emerald-900/10 mb-3 ring-8 ring-emerald-50 dark:ring-emerald-950/40">
                        <i data-lucide="check-circle-2" class="size-9 stroke-[2.5]"></i>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-500/30">
                        Tiket Resmi Terbit
                    </span>
                    <h3 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-slate-100 mt-2">
                        Laporan Kerusakan Berhasil Dikirim!
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Tiket telah diteruskan ke antrean kerja Tim Teknisi IPSRS RS Ummi Bogor.
                    </p>
                </div>

                <!-- Kartu Bukti / Struk Tiket Digital -->
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/70 space-y-3 text-xs">
                    <!-- Baris Nomor Tiket & Badge Status -->
                    <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-700/80 pb-2.5">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">No. Tiket Pengaduan</span>
                            <span class="font-mono font-extrabold text-emerald-700 dark:text-emerald-400 text-sm tracking-wide">
                                {{ $submittedTicket['ticket_id'] }}
                            </span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-300 dark:border-amber-700/60 flex items-center gap-1.5">
                            <span class="size-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Menunggu Tindakan</span>
                        </span>
                    </div>

                    <!-- Info Alat & Ruangan -->
                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Alat Medis</span>
                            <strong class="text-slate-800 dark:text-slate-200 block truncate">{{ $submittedTicket['asset_name'] }}</strong>
                            <span class="font-mono text-[10px] text-slate-500">{{ $submittedTicket['asset_code'] }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Ruangan & Waktu</span>
                            <strong class="text-slate-800 dark:text-slate-200 block truncate">{{ $submittedTicket['location'] }}</strong>
                            <span class="text-[10px] text-slate-400">{{ $submittedTicket['time'] }}</span>
                        </div>
                    </div>

                    <!-- Tingkat Kerusakan & Gejala Masalah -->
                    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-700/80 space-y-1">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-400">Tingkat Kerusakan:</span>
                            <span class="font-bold {{ $submittedTicket['condition'] === 'rusak_berat' ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ strtoupper(str_replace('_', ' ', $submittedTicket['condition'])) }}
                            </span>
                        </div>
                        <div class="bg-white dark:bg-slate-900/80 p-2.5 rounded-xl border border-slate-200/70 dark:border-slate-800 text-[11px] text-slate-700 dark:text-slate-300 italic">
                            "{{ $submittedTicket['notes'] }}"
                        </div>
                    </div>

                    <!-- Nama Pelapor -->
                    <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 pt-1 border-t border-slate-200/80 dark:border-slate-700/80">
                        <span>Petugas Pelapor:</span>
                        <strong class="text-slate-700 dark:text-slate-300">{{ $submittedTicket['reporter'] }}</strong>
                    </div>
                </div>

                <!-- Alert Pengamanan Pasien -->
                <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 flex items-start gap-2 text-[11px] text-amber-900 dark:text-amber-200">
                    <i data-lucide="shield-alert" class="size-4 shrink-0 text-amber-600 dark:text-amber-400 mt-0.5"></i>
                    <span>Status fisik alat telah otomatis dikunci menjadi <strong>Dalam Pemeliharaan</strong> agar tidak digunakan pada pasien hingga selesai diperbaiki.</span>
                </div>

                <!-- Tombol Tindakan Pop-up -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
                    <button 
                        type="button" 
                        wire:click="viewSubmittedTicket" 
                        class="w-full py-2.5 px-4 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 active:scale-98 transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-md shadow-emerald-900/20"
                    >
                        <i data-lucide="clipboard-list" class="size-4"></i>
                        <span>Pantau Tiket Saya</span>
                    </button>

                    <button 
                        type="button" 
                        wire:click="closeSuccessModal" 
                        class="w-full py-2.5 px-4 rounded-xl font-bold text-xs text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-1.5 cursor-pointer"
                    >
                        <span>Selesai & Tutup</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
