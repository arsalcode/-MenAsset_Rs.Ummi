<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RS Ummi') }} — Sistem Manajemen Aset Rumah Sakit</title>

    <!-- Inline Script to prevent Theme Flash / Kedip Putih -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Clean Vector Icons: Lucide Icons SVG (Bundled via Vite) -->

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --livewire-progress-bar-color: #047857;
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

        html.dark {
            --surface: #0F172A;         /* Slate 900 */
            --text-dark: #F8FAFC;       /* Slate 50 */
            --text-muted: #94A3B8;      /* Slate 400 */
            --border: #334155;          /* Slate 700 */
            --border-light: #1E293B;    /* Slate 800 */
            --primary-bg: #064E3B;      /* Emerald 900 */
            color-scheme: dark;
        }

        /* ================= SELECT & DROPDOWN STYLING ================= */
        select {
            color-scheme: light;
        }

        html.dark select {
            color-scheme: dark !important;
            background-color: #0F172A !important;
            color: #F1F5F9 !important;
        }

        html.dark select:focus,
        html.dark select:active {
            background-color: #0F172A !important;
            color: #FFFFFF !important;
        }

        select option {
            background-color: #FFFFFF;
            color: #0F172A;
        }

        html.dark select option {
            background-color: #0F172A !important;
            color: #F1F5F9 !important;
        }

        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* Hilangkan scrollbar horizontal jelek secara permanen */
        ::-webkit-scrollbar-horizontal {
            display: none !important;
            height: 0 !important;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #F8FAFC;
            color: var(--text-dark);
            font-size: 14px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.2s ease, color 0.2s ease;
            overflow-x: hidden;
        }

        html.dark body {
            background-color: #0B1120;
            color: #F8FAFC;
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

        .report-icon i,
        .report-icon svg {
            width: 28px;
            height: 28px;
            color: #FFFFFF !important;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        /* Lucide SVG Icons Alignment & Crisp Rendering */
        svg.lucide {
            display: inline-block;
            vertical-align: middle;
            stroke-width: 2px;
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

        /* ================= DARK MODE CUSTOM CSS OVERRIDES ================= */
        html.dark .admin-tab-nav {
            background: #0f172a;
            border-bottom-color: #1e293b;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }

        html.dark .admin-tab-btn {
            background: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }

        html.dark .admin-tab-btn:hover {
            border-color: #10b981;
            color: #34d399;
            background: rgba(6, 78, 59, 0.4);
        }

        html.dark .admin-tab-btn.active {
            background: #047857 !important;
            border-color: #047857 !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
        }

        html.dark .admin-tab-btn.active .badge-alert-count {
            background: #ffffff;
            color: #dc2626;
        }

        html.dark .report-card {
            background: #1e293b;
            border-color: #047857;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        html.dark .report-amount {
            color: #f8fafc !important;
        }

        html.dark .report-label {
            color: #e2e8f0;
            font-weight: 600;
        }

        html.dark .report-sublabel {
            color: #cbd5e1;
        }

        html.dark .tag-cash {
            background: rgba(22, 101, 52, 0.35);
            color: #86efac;
            border-color: #166534;
        }

        html.dark .tag-qris {
            background: rgba(4, 120, 87, 0.35);
            color: #6ee7b7;
            border-color: #047857;
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
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 flex flex-col transition-colors duration-200">

    <!-- ================= TOP NAVIGATION (Khas Alkamart) ================= -->
    <header class="relative h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-6 flex items-center justify-between sticky top-0 z-50 shadow-xs transition-colors duration-200">
        <!-- Nav Left: Brand & Title -->
        <div class="flex items-center gap-3.5 z-10">
            <div class="flex items-center justify-center size-11 rounded-lg bg-emerald-700 text-white shadow-md shadow-emerald-900/20">
                <i data-lucide="hospital" class="size-6 text-white"></i>
            </div>
            <div>
                <span class="text-lg font-extrabold text-emerald-800 dark:text-emerald-400 tracking-tight block">RS UMMI BOGOR</span>
                <span class="text-xs text-slate-500 dark:text-slate-400 block -mt-0.5">Manajemen Aset &amp; Peralatan Medis</span>
            </div>
        </div>

        <!-- Nav Center: Saklar Mode Terang / Gelap (Lampu di Tengah - Murni Ikon Tanpa Background) -->
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex items-center justify-center">
            <div class="relative group">
                <button 
                    type="button" 
                    id="themeToggleBtn"
                    onclick="toggleAppTheme()" 
                    class="no-print p-2 text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 flex items-center justify-center transition-colors cursor-pointer select-none"
                    title="Ganti Mode Tampilan (Terang / Gelap)"
                    aria-label="Ganti Mode Tampilan (Terang / Gelap)"
                >
                    <!-- SVG Sun & Moon Dual Icon Sesuai Gambar User (Warna Hijau Emerald) -->
                    <svg class="size-6 text-emerald-600 dark:text-emerald-400 transition-transform duration-300 group-hover:rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                <!-- Tooltip Floating Text yang Muncul Saat di-Hover -->
                <div class="pointer-events-none absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2.5 py-1 text-[11px] font-semibold text-white bg-slate-900 dark:bg-slate-800 rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 group-hover:translate-y-0.5 transition-all duration-200 z-50">
                    <span class="dark:hidden">Mode Gelap</span>
                    <span class="hidden dark:inline">Mode Terang</span>
                    <span class="absolute bottom-full left-1/2 -translate-x-1/2 border-4 border-transparent border-b-slate-900 dark:border-b-slate-800"></span>
                </div>
            </div>
        </div>

        <!-- Nav Right: Flag & Role Switcher (Sesuai Gambar Mockup User) -->
        <div class="flex items-center gap-3 z-10">
            <!-- Bendera Indonesia / Language Indicator Bulat (Sesuai Gambar User) -->
            <div class="flex items-center" title="Bahasa Indonesia (ID)">
                <div class="size-6 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 shadow-2xs flex flex-col shrink-0">
                    <svg viewBox="0 0 32 32" class="size-full">
                        <rect width="32" height="16" fill="#E70011"/>
                        <rect y="16" width="32" height="16" fill="#FFFFFF"/>
                    </svg>
                </div>
            </div>

            <!-- Tombol Beralih Role Khas Alkamart (Ganti ke Admin / Ganti ke Lapangan - Tanpa Background) -->
            @if (request()->routeIs('field'))
                <a 
                    href="{{ route('dashboard') }}" 
                    class="no-print h-9 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent text-slate-700 dark:text-slate-200 hover:border-emerald-600 hover:text-emerald-700 dark:hover:border-emerald-500 dark:hover:text-emerald-400 text-xs font-semibold inline-flex items-center gap-2 transition-colors"
                    title="Beralih ke Dashboard Admin"
                >
                    <i data-lucide="arrow-left-right" class="size-3.5 text-slate-500 dark:text-slate-400"></i>
                    <span>Ganti ke Admin</span>
                </a>
            @else
                <a 
                    href="{{ route('field') }}" 
                    class="no-print h-9 px-3.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-transparent text-slate-700 dark:text-slate-200 hover:border-emerald-600 hover:text-emerald-700 dark:hover:border-emerald-500 dark:hover:text-emerald-400 text-xs font-semibold inline-flex items-center gap-2 transition-colors"
                    title="Beralih ke Mode Lapangan"
                >
                    <i data-lucide="arrow-left-right" class="size-3.5 text-slate-500 dark:text-slate-400"></i>
                    <span>Ganti ke Lapangan</span>
                </a>
            @endif
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 w-full bg-slate-50/50 dark:bg-slate-950 transition-colors duration-200">
        {{ $slot }}
    </main>

    @livewireScripts

    <!-- Script Saklar Lampu / Dark Mode -->
    <script>
        function toggleAppTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark } }));
        }
    </script>
</body>
</html>
