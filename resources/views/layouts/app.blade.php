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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
            --danger: #DC2626;
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

        /* ================= SUB-NAV ADMIN TABS ALKAMART ================= */
        .admin-tab-nav {
            display: flex !important;
            align-items: center;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 10px 24px;
            gap: 10px;
            overflow-x: auto;
            scrollbar-width: none;
            position: sticky;
            top: 64px;
            z-index: 40;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .admin-tab-btn {
            height: 38px;
            padding: 0 10px;
            border-radius: 9999px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            user-select: none;
            outline: none;
        }

        .admin-tab-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: #F0F6F3;
        }

        .admin-tab-btn.active {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #FFFFFF !important;
            font-weight: 600;
            padding: 0 16px;
            box-shadow: 0 3px 8px rgba(2, 69, 45, 0.22);
        }

        .admin-tab-btn:not(.active) {
            min-width: 38px;
            padding: 0 10px;
        }

        .admin-tab-btn:not(.active) .tab-label {
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            display: inline-block;
            white-space: nowrap;
            transition: max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
        }

        .admin-tab-btn.active .tab-label {
            max-width: 200px;
            opacity: 1;
            display: inline-block;
            white-space: nowrap;
            transition: max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease 0.05s;
        }

        .badge-alert-count {
            background: var(--danger);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 1px 6px;
            min-width: 18px;
            height: 18px;
            line-height: 16px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 2px;
        }

        .admin-tab-btn.active .badge-alert-count {
            background: #FFFFFF;
            color: var(--danger);
            font-weight: 800;
        }

        /* ================= KARTU REPORT-CARD ALKAMART (PERSIS GAMBAR 2) ================= */
        .report-card {
            position: relative;
            background: #FFFFFF;
            border-radius: 12px;
            padding: 18px;
            min-height: 110px;
            border: 1.5px solid var(--primary);
            display: flex;
            align-items: flex-start;
            gap: 14px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(4, 120, 87, 0.12);
        }

        .report-icon {
            position: relative;
            margin-top: -19px;
            margin-left: -19px;
            width: 72px;
            height: 68px;
            border-top-left-radius: 11px;
            border-top-right-radius: 0;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            flex-shrink: 0;
            z-index: 1;
        }

        .report-icon i {
            font-size: 26px;
            color: #FFFFFF !important;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        .report-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .report-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            display: block;
            margin-bottom: 2px;
        }

        .report-amount {
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
            font-family: 'JetBrains Mono', monospace;
            line-height: 1.2;
        }

        .report-sublabel {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            display: block;
        }

        .saldo-breakdown-tags {
            display: flex;
            gap: 6px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .tag-cash,
        .tag-qris {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .tag-cash {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .tag-qris {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
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
