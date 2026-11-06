<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RS Ummi') }} — Sistem Manajemen Aset Rumah Sakit</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="flex flex-col w-64 bg-slate-900 border-r border-slate-800 text-white shrink-0">
            <!-- Brand Header -->
            <div class="flex items-center gap-3.5 h-16 px-6 border-b border-slate-800/80 bg-slate-950/40">
                <div class="flex items-center justify-center size-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 text-white font-bold text-base shadow-sm shadow-emerald-500/20">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-sm text-white tracking-tight">RS UMMI BOGOR</h2>
                    <p class="text-[10px] font-semibold tracking-wider text-emerald-400 uppercase">Hospital Asset ERP</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <p class="px-3 pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Menu Utama</p>

                <a href="{{ url('/') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 shadow-xs transition-colors">
                    <svg class="size-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Dasbor & Data Aset</span>
                </a>

                <div class="pt-5 pb-2">
                    <p class="px-3 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Master Data Terhubung</p>
                </div>

                <div class="px-3.5 py-2 text-xs text-slate-400 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                        Ruangan & Gedung
                    </span>
                    <span class="font-mono text-[11px] text-slate-300 bg-slate-800 px-2 py-0.5 rounded-md">7 Lokasi</span>
                </div>

                <div class="px-3.5 py-2 text-xs text-slate-400 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                        Kategori Alat Medis
                    </span>
                    <span class="font-mono text-[11px] text-slate-300 bg-slate-800 px-2 py-0.5 rounded-md">4 Kategori</span>
                </div>

                <div class="px-3.5 py-2 text-xs text-slate-400 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <span class="size-1.5 rounded-full bg-slate-400"></span>
                        Log Pemeliharaan
                    </span>
                    <span class="font-mono text-[11px] text-emerald-400 bg-emerald-950/60 border border-emerald-800/40 px-2 py-0.5 rounded-md">Aktif</span>
                </div>
            </nav>

            <!-- Bottom User & Environment Info -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/30">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center size-9 rounded-full bg-slate-800 text-slate-300 font-semibold text-xs border border-slate-700">
                        AU
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-white truncate">Admin SIMRS</p>
                        <p class="text-[11px] text-slate-400 truncate">RS Ummi Kota Bogor</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Topbar Header -->
            <header class="h-16 border-b border-slate-200/80 bg-white px-8 flex items-center justify-between sticky top-0 z-20 shadow-xs">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-base font-bold text-slate-900">Sistem Manajemen Aset Rumah Sakit</h1>
                        <p class="text-xs text-slate-500">Monitoring & Inventarisasi Alat Medis, Ruangan, dan Pemeliharaan</p>
                    </div>
                </div>

                <!-- Status Badges -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/70 text-emerald-800 text-xs font-medium">
                        <span class="relative flex size-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full size-2 bg-emerald-500"></span>
                        </span>
                        <span>MySQL & Livewire Terhubung</span>
                    </div>

                    <div class="text-right hidden md:block">
                        <p class="text-xs font-semibold text-slate-700 font-mono">{{ now()->format('d M Y') }}</p>
                        <p class="text-[11px] text-slate-500">Versi MVP 1.0</p>
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
