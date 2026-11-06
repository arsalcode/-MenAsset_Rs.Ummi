<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RS Ummi') }} — Sistem Manajemen Aset Rumah Sakit</title>

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Modern Vector Icons: Remix Icon 4.5.0 (Identik dengan Alkamart) -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --primary: #047857;         /* Emerald 700 */
            --primary-light: #10B981;   /* Emerald 500 */
            --primary-dark: #064E3B;    /* Emerald 900 */
            --primary-bg: #ECFDF5;      /* Emerald 50 */
            --surface: #FFFFFF;
            --text-dark: #0F172A;       /* Slate 900 */
            --text-muted: #475569;      /* Slate 600 */
            --border: #E2E8F0;          /* Slate 200 */
            --border-light: #F1F5F9;    /* Slate 100 */
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #FFFFFF;
            color: var(--text-dark);
            font-size: 14px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Pulsing Dot Hijau Berdenyut (Identik Alkamart) */
        .status-dot {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }

        .status-dot.online {
            background: #10B981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 1.6s infinite ease-in-out;
        }

        @keyframes pulse-green {
            0% {
                transform: scale(0.9);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                transform: scale(1.15);
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(0.9);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Print Media Stylesheet */
        @media print {
            .no-print, header, .admin-tab-nav, .filter-bar, .admin-controls-wrapper, button {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .print-only {
                display: block !important;
            }
        }
        @media screen {
            .print-only {
                display: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-white flex flex-col">

    <!-- ================= TOP NAVIGATION (Khas Alkamart) ================= -->
    <header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between sticky top-0 z-50 shadow-xs">
        <!-- Nav Left: Brand & Badge -->
        <div class="flex items-center gap-3.5">
            <div class="flex items-center justify-center size-11 rounded-lg bg-emerald-700 text-white text-2xl shadow-md shadow-emerald-900/20">
                <i class="ri-hospital-fill"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-extrabold text-emerald-800 tracking-tight">RS UMMI BOGOR</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-700/20">
                        Admin SIMRS
                    </span>
                </div>
                <span class="text-xs text-slate-500 block -mt-0.5">Sistem Manajemen Aset & Peralatan Medis</span>
            </div>
        </div>

        <!-- Nav Right: Status Indicator & Info -->
        <div class="flex items-center gap-4">
            <!-- Pulsing Status Dot Badge -->
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-xs font-medium text-slate-700">
                <span class="status-dot online"></span>
                <span class="font-semibold text-slate-700">SIMRS Cloud & MySQL Terhubung</span>
            </div>

            <!-- Tanggal Hari Ini -->
            <div class="hidden md:flex items-center gap-2 text-xs text-slate-500 font-medium pl-3 border-l border-slate-200">
                <i class="ri-calendar-line text-emerald-700"></i>
                <span class="font-mono text-slate-700 font-semibold">{{ now()->format('d M Y') }}</span>
            </div>

            <!-- Tombol Cetak Cepat -->
            <button onclick="window.print()" class="no-print hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg border border-slate-200 transition-colors">
                <i class="ri-printer-line text-slate-600"></i>
                <span>Cetak Rekap</span>
            </button>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 w-full bg-white">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
