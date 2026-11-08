<div class="min-h-screen bg-slate-50 dark:bg-slate-950 pb-24">
    <!-- ================= TOP HEADER LAPANGAN ================= -->
    <div class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-4 py-3 shadow-xs">
        <div class="max-w-md mx-auto flex items-center justify-between gap-2">
            <!-- Brand & Subtitle -->
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="size-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-900/20">
                    <i data-lucide="hospital" class="size-5"></i>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-extrabold text-emerald-800 dark:text-emerald-400 tracking-tight truncate">RS UMMI BOGOR</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300">
                            Lapangan
                        </span>
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400 block truncate">Portal Teknisi & Petugas Ruangan</span>
                </div>
            </div>

            <!-- Header Action: Saklar Tema & Kembali ke Dashboard Admin -->
            <div class="flex items-center gap-1.5 shrink-0">
                <button 
                    type="button" 
                    onclick="toggleAppTheme()" 
                    class="p-2 text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 flex items-center justify-center transition-colors cursor-pointer select-none"
                    title="Ganti Mode Tampilan (Terang / Gelap)"
                >
                    <svg class="size-5 text-emerald-600 dark:text-emerald-400 transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                </button>
                <a 
                    href="{{ route('dashboard') }}" 
                    class="h-9 px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-semibold inline-flex items-center gap-1.5 transition-colors shadow-2xs"
                    title="Beralih ke Dashboard Admin SIMRS"
                >
                    <i data-lucide="layout-dashboard" class="size-3.5"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Container Konten Utama (Mobile Width Max-w-md agar nyaman di HP) -->
    <div class="max-w-md mx-auto p-4 space-y-4">
        <!-- Flash Alert Notification -->
        @if ($feedbackMessage)
            <div class="p-3.5 rounded-xl flex items-start justify-between gap-3 border shadow-xs transition-all {{ $feedbackType === 'danger' ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900 text-rose-800 dark:text-rose-200' : ($feedbackType === 'warning' ? 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-900 text-amber-900 dark:text-amber-200' : 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900 text-emerald-900 dark:text-emerald-200') }}">
                <div class="flex items-center gap-2.5 text-xs">
                    <i data-lucide="{{ $feedbackType === 'danger' ? 'alert-circle' : ($feedbackType === 'warning' ? 'triangle-alert' : 'check-circle-2') }}" class="size-4 shrink-0"></i>
                    <span class="font-medium leading-relaxed">{{ $feedbackMessage }}</span>
                </div>
                <button wire:click="$set('feedbackMessage', null)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- TAB 1: PEMINDAI KAMERA QR CODE (SCANNER) -->
        <!-- ========================================================================= -->
        @if ($activeTab === 'scan')
            <div class="space-y-4">
                <!-- Card Petunjuk & Area Kamera -->
                <div 
                    x-data="{
                        html5QrCode: null,
                        scanning: false,
                        scanError: '',
                        initScanner() {
                            if (typeof Html5Qrcode === 'undefined') {
                                this.scanError = 'Library pemindai belum siap. Muat ulang halaman.';
                                return;
                            }
                            this.html5QrCode = new Html5Qrcode('qr-reader');
                        },
                        startCamera() {
                            this.scanError = '';
                            if (!this.html5QrCode) this.initScanner();

                            const config = { fps: 10, qrbox: { width: 220, height: 220 } };
                            this.html5QrCode.start(
                                { facingMode: 'environment' },
                                config,
                                (decodedText) => {
                                    // Berhasil scan!
                                    this.stopCamera();
                                    $wire.handleScannedCode(decodedText);
                                },
                                (errorMessage) => {
                                    // abaikan frame per frame error
                                }
                            ).then(() => {
                                this.scanning = true;
                            }).catch(err => {
                                this.scanError = 'Tidak dapat mengakses kamera. Pastikan izin kamera aktif atau gunakan pencarian manual.';
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
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-xs text-center space-y-4"
                >
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">Pemindai Stiker QR Alat Medis</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Arahkan kamera HP ke stiker QR pada alat medis untuk verifikasi & cek kalibrasi.</p>
                    </div>

                    <!-- Viewport Kamera Live -->
                    <div class="relative w-full aspect-square max-w-[280px] mx-auto rounded-2xl overflow-hidden bg-slate-950 border-2 border-dashed border-emerald-500 flex flex-col items-center justify-center shadow-inner">
                        <div id="qr-reader" class="w-full h-full"></div>

                        <div x-show="!scanning" class="p-6 text-center text-slate-400 space-y-2">
                            <div class="size-14 rounded-full bg-slate-900 text-emerald-400 mx-auto flex items-center justify-center">
                                <i data-lucide="camera" class="size-7"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-300">Kamera Belum Aktif</p>
                            <p class="text-[11px] text-slate-500">Tekan tombol di bawah untuk menyalakan kamera HP.</p>
                        </div>
                    </div>

                    <!-- Notifikasi Error Akses Kamera -->
                    <div x-show="scanError" x-cloak class="p-2.5 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 text-xs">
                        <span x-text="scanError"></span>
                    </div>

                    <!-- Tombol Nyalakan / Matikan Kamera -->
                    <div class="flex gap-2 justify-center">
                        <button 
                            x-show="!scanning" 
                            @click="startCamera()" 
                            type="button" 
                            class="w-full py-3 px-4 rounded-xl font-bold text-xs text-white bg-emerald-600 hover:bg-emerald-700 active:scale-98 transition-all shadow-md shadow-emerald-900/20 flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="camera" class="size-4"></i>
                            <span>Nyalakan Kamera HP</span>
                        </button>
                        <button 
                            x-show="scanning" 
                            @click="stopCamera()" 
                            type="button" 
                            class="w-full py-3 px-4 rounded-xl font-bold text-xs text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="camera-off" class="size-4"></i>
                            <span>Matikan Kamera</span>
                        </button>
                    </div>
                </div>

                <!-- Opsi Ketik Manual / Cepat -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-4 shadow-xs space-y-3">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block">Ketik Kode Aset Manual (Jika QR Rusak):</span>
                    <form 
                        x-data="{ manualCode: '' }" 
                        @submit.prevent="if (manualCode.trim()) { $wire.handleScannedCode(manualCode); manualCode = ''; }" 
                        class="flex gap-2"
                    >
                        <input 
                            type="text" 
                            x-model="manualCode" 
                            placeholder="Contoh: MED-001 atau SN" 
                            class="flex-1 h-10 px-3.5 text-xs font-mono font-semibold uppercase bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600"
                        />
                        <button 
                            type="submit" 
                            class="h-10 px-4 rounded-xl font-bold text-xs text-white bg-emerald-700 hover:bg-emerald-800 shrink-0 flex items-center gap-1.5"
                        >
                            <i data-lucide="search" class="size-3.5"></i>
                            <span>Cek</span>
                        </button>
                    </form>
                </div>
            </div>

        <!-- ========================================================================= -->
        <!-- TAB 2: KATALOG ALAT PER RUANGAN (LIST) -->
        <!-- ========================================================================= -->
        @elseif ($activeTab === 'list')
            <div class="space-y-3.5">
                <!-- Search Bar Lapangan -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="search" class="size-4"></i>
                    </span>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari kode, nama, atau serial number..." 
                        class="w-full h-11 pl-10 pr-9 py-2 text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:border-emerald-600 shadow-xs"
                    />
                    @if ($search)
                        <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400">
                            <i data-lucide="x" class="size-4"></i>
                        </button>
                    @endif
                </div>

                <!-- Filter Ruangan Cepat (Horizontal Scroll Pills) -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none [scrollbar-width:none]">
                    <button 
                        type="button" 
                        wire:click="$set('filterLocation', '')" 
                        class="px-3 py-1.5 rounded-full text-xs font-semibold shrink-0 transition-colors {{ $filterLocation === '' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800' }}"
                    >
                        Semua Ruangan
                    </button>
                    @foreach ($locations as $loc)
                        <button 
                            type="button" 
                            wire:click="$set('filterLocation', '{{ $loc->id }}')" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold shrink-0 transition-colors {{ (string)$filterLocation === (string)$loc->id ? 'bg-emerald-700 text-white shadow-xs' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-800' }}"
                        >
                            {{ $loc->name }}
                        </button>
                    @endforeach
                </div>

                <!-- Filter Kondisi Baris Cepat -->
                <div class="flex items-center gap-1.5 text-[11px]">
                    <button 
                        type="button" 
                        wire:click="$set('filterCondition', '')" 
                        class="flex-1 py-1 text-center font-bold rounded-lg border {{ $filterCondition === '' ? 'bg-slate-200 dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-100' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-500' }}"
                    >
                        Semua Kondisi
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('filterCondition', 'baik')" 
                        class="flex-1 py-1 text-center font-bold rounded-lg border {{ $filterCondition === 'baik' ? 'bg-emerald-100 dark:bg-emerald-950/80 border-emerald-300 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-500' }}"
                    >
                        ● Baik
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('filterCondition', 'rusak_ringan')" 
                        class="flex-1 py-1 text-center font-bold rounded-lg border {{ $filterCondition === 'rusak_ringan' ? 'bg-amber-100 dark:bg-amber-950/80 border-amber-300 dark:border-amber-700 text-amber-800 dark:text-amber-300' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-500' }}"
                    >
                        ● Rusak
                    </button>
                </div>

                <!-- List Kartu Alat Medis (Mobile Cards) -->
                <div class="space-y-2.5">
                    @forelse ($assets as $asset)
                        <div 
                            wire:click="openDetail({{ $asset->id }})" 
                            wire:key="field-asset-{{ $asset->id }}"
                            class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-3.5 shadow-xs flex items-center gap-3.5 hover:border-emerald-500 cursor-pointer transition-all active:scale-98"
                        >
                            <!-- Thumbnail Foto -->
                            <div class="size-16 rounded-xl bg-slate-100 dark:bg-slate-800 overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700 flex items-center justify-center">
                                @if ($asset->image_url)
                                    <img src="{{ $asset->image_url }}" alt="{{ $asset->name }}" class="w-full h-full object-cover" />
                                @else
                                    <i data-lucide="stethoscope" class="size-7 text-slate-300 dark:text-slate-600"></i>
                                @endif
                            </div>

                            <!-- Informasi Alat -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        {{ $asset->asset_code }}
                                    </span>
                                    @if ($asset->condition === 'baik')
                                        <span class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                            <span class="size-1.5 rounded-full bg-emerald-500"></span> Baik
                                        </span>
                                    @elseif ($asset->condition === 'rusak_ringan')
                                        <span class="text-[10px] font-semibold text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                            <span class="size-1.5 rounded-full bg-amber-500"></span> Rusak Ringan
                                        </span>
                                    @else
                                        <span class="text-[10px] font-semibold text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                            <span class="size-1.5 rounded-full bg-rose-500"></span> Rusak Berat
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate mt-1">
                                    {{ $asset->name }}
                                </h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                    {{ $asset->location->name ?? '-' }} · {{ $asset->brand ?? '-' }}
                                </p>
                            </div>

                            <i data-lucide="chevron-right" class="size-4 text-slate-400 shrink-0"></i>
                        </div>
                    @empty
                        <div class="p-8 text-center bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2">
                            <i data-lucide="inbox" class="size-10 text-slate-300 dark:text-slate-600 mx-auto"></i>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">Tidak ada alat medis yang cocok.</p>
                            <button wire:click="$set('search', ''); $set('filterLocation', ''); $set('filterCondition', '')" class="text-xs font-bold text-emerald-600 hover:underline">
                                Bersihkan Pencarian
                            </button>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="pt-2">
                    {{ $assets->links() }}
                </div>
            </div>

        <!-- ========================================================================= -->
        <!-- TAB 3: INPUT ALAT BARU DARI HP (CREATE) -->
        <!-- ========================================================================= -->
        @elseif ($activeTab === 'create')
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-xs space-y-4">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h2 class="text-base font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="size-5 text-emerald-600"></i>
                        <span>Input Alat Medis Baru</span>
                    </h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Daftarkan alat langsung dari ruangan via kamera & formulir ringkas.</p>
                </div>

                <form wire:submit.prevent="saveAsset" class="space-y-3.5 text-xs">
                    <!-- Foto Fisik Alat (Dukungan Langsung Kamera HP) -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Foto Fisik Alat Medis</label>
                        <div class="flex items-center gap-3">
                            <label class="flex-1 cursor-pointer">
                                <div class="w-full py-2.5 px-3 rounded-xl border border-dashed border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center justify-center gap-2 hover:bg-emerald-100/50 transition-colors text-center">
                                    <i data-lucide="camera" class="size-4"></i>
                                    <span>Ambil Foto / Pilih File</span>
                                </div>
                                <input type="file" wire:model="image" accept="image/*" capture="environment" class="hidden" />
                            </label>

                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="size-12 rounded-xl object-cover border border-emerald-500 shrink-0" />
                            @endif
                        </div>
                        @error('image') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kategori & Tombol Auto-Generate Kode -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Alat <span class="text-rose-500">*</span></label>
                        <select wire:model.live="category_id" class="w-full h-10 px-3 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:border-emerald-600">
                            <option value="">Pilih Kategori...</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kode Aset & Tombol Buat Otomatis -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="font-semibold text-slate-700 dark:text-slate-300">Kode Aset RS <span class="text-rose-500">*</span></label>
                            <button type="button" wire:click="generateAssetCode" class="text-[11px] font-bold text-emerald-600 hover:underline">
                                Auto-Generate Kode
                            </button>
                        </div>
                        <input type="text" wire:model="asset_code" placeholder="Contoh: MED-016" class="w-full h-10 px-3 font-mono uppercase bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                        @error('asset_code') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Alat -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Alat Medis <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="Contoh: Syringe Pump Terumo TE-331" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                        @error('name') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Ruangan Penempatan -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Ruangan Penempatan RS <span class="text-rose-500">*</span></label>
                        <select wire:model="location_id" class="w-full h-10 px-3 border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:border-emerald-600">
                            <option value="">Pilih Ruangan...</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->building }})</option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Merk & Tipe (2 Kolom) -->
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Merk / Brand</label>
                            <input type="text" wire:model="brand" placeholder="Contoh: Mindray" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tipe / Model</label>
                            <input type="text" wire:model="model_type" placeholder="Contoh: BeneHeart D3" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                        </div>
                    </div>

                    <!-- Nomor Seri (Serial Number) -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nomor Seri (Serial Number)</label>
                        <input type="text" wire:model="serial_number" placeholder="Contoh: SN-88912-BGF" class="w-full h-10 px-3 font-mono bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600" />
                    </div>

                    <!-- Kondisi Alat Saat Ini -->
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Kondisi Alat Saat Ini <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            <button 
                                type="button" 
                                wire:click="$set('condition', 'baik')" 
                                class="py-2.5 rounded-xl border text-center font-bold text-xs transition-colors {{ $condition === 'baik' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}"
                            >
                                Baik
                            </button>
                            <button 
                                type="button" 
                                wire:click="$set('condition', 'rusak_ringan')" 
                                class="py-2.5 rounded-xl border text-center font-bold text-xs transition-colors {{ $condition === 'rusak_ringan' ? 'bg-amber-500 text-white border-amber-500 shadow-xs' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}"
                            >
                                Rusak Ringan
                            </button>
                            <button 
                                type="button" 
                                wire:click="$set('condition', 'rusak_berat')" 
                                class="py-2.5 rounded-xl border text-center font-bold text-xs transition-colors {{ $condition === 'rusak_berat' ? 'bg-rose-600 text-white border-rose-600 shadow-xs' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}"
                            >
                                Rusak Berat
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Simpan Besar -->
                    <div class="pt-3">
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-4 rounded-xl font-bold text-sm text-white bg-emerald-700 hover:bg-emerald-800 active:scale-98 transition-all shadow-md shadow-emerald-900/20 flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <i data-lucide="save" class="size-4"></i>
                            <span wire:loading.remove wire:target="saveAsset">Simpan Alat Medis ke Inventaris</span>
                            <span wire:loading wire:target="saveAsset">Menyimpan Data...</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- BOTTOM SHEET / DETAIL POPUP ALAT MEDIS -->
    <!-- ========================================================================= -->
    @if ($isDetailOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="bg-white dark:bg-slate-900 rounded-t-3xl sm:rounded-2xl border-t sm:border border-slate-200 dark:border-slate-800 shadow-2xl max-w-md w-full max-h-[85vh] overflow-y-auto">
                <!-- Header Bottom Sheet -->
                <div class="sticky top-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between z-10">
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-emerald-800 text-white">
                            {{ $selectedAsset->asset_code }}
                        </span>
                        @if ($selectedAsset->condition === 'baik')
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300">
                                Laik Pakai (Baik)
                            </span>
                        @elseif ($selectedAsset->condition === 'rusak_ringan')
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300">
                                Butuh Kalibrasi / Rusak
                            </span>
                        @else
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300">
                                Rusak Berat (Mogok)
                            </span>
                        @endif
                    </div>
                    <button wire:click="closeDetail" class="p-1 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="x" class="size-5"></i>
                    </button>
                </div>

                <!-- Body Detail -->
                <div class="p-5 space-y-4 text-xs">
                    <!-- Foto & Identitas -->
                    <div class="flex gap-3.5 items-start">
                        <div class="size-20 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0">
                            @if ($selectedAsset->image_url)
                                <img src="{{ $selectedAsset->image_url }}" alt="{{ $selectedAsset->name }}" class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i data-lucide="stethoscope" class="size-8"></i>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">
                                {{ $selectedAsset->category->name ?? '-' }}
                            </span>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 leading-snug mt-0.5">
                                {{ $selectedAsset->name }}
                            </h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                {{ $selectedAsset->brand ?? '-' }} {{ $selectedAsset->model_type ? '· ' . $selectedAsset->model_type : '' }}
                            </p>
                            <p class="text-[11px] font-mono text-slate-400 mt-0.5">
                                SN: {{ $selectedAsset->serial_number ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Parameter Lokasi & PIC Ruangan -->
                    <div class="grid grid-cols-2 gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-[11px]">
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

                    <!-- Histori Kalibrasi Terakhir -->
                    @if ($selectedAsset->maintenanceLogs->isNotEmpty())
                        @php $latestLog = $selectedAsset->maintenanceLogs->first(); @endphp
                        <div class="p-3 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-800/60 text-[11px] space-y-1">
                            <div class="flex items-center justify-between font-bold text-emerald-900 dark:text-emerald-300">
                                <span>Histori Kalibrasi Terakhir:</span>
                                <span>{{ $latestLog->maintenance_date->format('d M Y') }}</span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-300">
                                Tindakan: <strong class="capitalize">{{ str_replace('_', ' ', $latestLog->type) }}</strong> oleh {{ $latestLog->vendor_technician ?? 'Internal' }}
                            </p>
                        </div>
                    @endif

                    <!-- Aksi Cepat Petugas Lapangan -->
                    <div class="space-y-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block">Tindakan Cepat di Tempat:</span>

                        <!-- Tombol Lapor Rusak -->
                        <button 
                            wire:click="openReportModal" 
                            type="button" 
                            class="w-full py-2.5 px-4 rounded-xl font-bold text-xs text-amber-900 dark:text-amber-200 bg-amber-100 hover:bg-amber-200 dark:bg-amber-950/70 dark:hover:bg-amber-900 border border-amber-300 dark:border-amber-800 flex items-center justify-center gap-2 cursor-pointer transition-colors"
                        >
                            <i data-lucide="triangle-alert" class="size-4 text-amber-600"></i>
                            <span>Lapor Kerusakan / Minta Servis</span>
                        </button>

                        <!-- Tombol Nyatakan Baik (Laik Pakai) -->
                        @if ($selectedAsset->condition !== 'baik')
                            <button 
                                wire:click="quickSetCondition('baik')" 
                                type="button" 
                                class="w-full py-2.5 px-4 rounded-xl font-bold text-xs text-emerald-800 dark:text-emerald-200 bg-emerald-100 hover:bg-emerald-200 dark:bg-emerald-950/70 dark:hover:bg-emerald-900 border border-emerald-300 dark:border-emerald-800 flex items-center justify-center gap-2 cursor-pointer transition-colors"
                            >
                                <i data-lucide="check-circle-2" class="size-4 text-emerald-600"></i>
                                <span>Ubah Status Menjadi Laik Pakai (Baik)</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Footer Bottom Sheet -->
                <div class="p-4 bg-slate-50 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800">
                    <button 
                        wire:click="closeDetail" 
                        class="w-full py-2.5 rounded-xl font-bold text-xs text-slate-700 dark:text-slate-200 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MODAL LAPOR KERUSAKAN LAPANGAN -->
    <!-- ========================================================================= -->
    @if ($isReportModalOpen && $selectedAsset)
        <div class="fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl max-w-sm w-full p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Lapor Kerusakan Alat</h3>
                        <p class="text-[11px] text-slate-500">{{ $selectedAsset->name }} ({{ $selectedAsset->asset_code }})</p>
                    </div>
                    <button wire:click="closeReportModal" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>

                <form wire:submit.prevent="submitDefectReport" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Tingkat Kerusakan</label>
                        <select wire:model="report_condition" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100">
                            <option value="rusak_ringan">Rusak Ringan (Alat Masih Nyala / Butuh Kalibrasi)</option>
                            <option value="rusak_berat">Rusak Berat (Alat Mati / Tidak Berfungsi Total)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Gejala Kerusakan / Catatan Lapangan <span class="text-rose-500">*</span></label>
                        <textarea wire:model="report_notes" rows="3" placeholder="Contoh: Baterai cepat habis, kabel probe lepas, atau layar LCD bergaris..." class="w-full p-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:border-emerald-600"></textarea>
                        @error('report_notes') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Petugas Pelapor</label>
                        <input type="text" wire:model="report_technician" placeholder="Nama perawat atau teknisi" class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100" />
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button type="button" wire:click="closeReportModal" class="flex-1 py-2.5 rounded-xl font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 rounded-xl font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-xs flex items-center justify-center gap-1.5">
                            <i data-lucide="send" class="size-3.5"></i>
                            <span>Kirim Laporan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- BOTTOM NAVIGATION DOCK (Khas Aplikasi Mobile) -->
    <!-- ========================================================================= -->
    <div class="fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 py-2 px-6 shadow-lg">
        <div class="max-w-md mx-auto grid grid-cols-3 gap-1">
            <!-- Nav 1: Scan QR Code -->
            <button 
                type="button" 
                wire:click="setTab('scan')" 
                class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition-all cursor-pointer {{ $activeTab === 'scan' ? 'text-emerald-700 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/50' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-medium' }}"
            >
                <i data-lucide="qr-code" class="size-5 mb-1"></i>
                <span class="text-[11px] leading-none">Scan QR</span>
            </button>

            <!-- Nav 2: Katalog Ruangan -->
            <button 
                type="button" 
                wire:click="setTab('list')" 
                class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition-all cursor-pointer {{ $activeTab === 'list' ? 'text-emerald-700 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/50' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-medium' }}"
            >
                <i data-lucide="package" class="size-5 mb-1"></i>
                <span class="text-[11px] leading-none">Alat Medis</span>
            </button>

            <!-- Nav 3: Input Baru -->
            <button 
                type="button" 
                wire:click="setTab('create')" 
                class="flex flex-col items-center justify-center py-1.5 px-2 rounded-xl transition-all cursor-pointer {{ $activeTab === 'create' ? 'text-emerald-700 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-950/50' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 font-medium' }}"
            >
                <i data-lucide="plus-circle" class="size-5 mb-1"></i>
                <span class="text-[11px] leading-none">Input Alat</span>
            </button>
        </div>
    </div>
</div>
