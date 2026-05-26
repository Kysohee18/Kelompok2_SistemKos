<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosBooking Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'Roboto', 'sans-serif'] },
                    colors: {
                        brand: {
                            DEFAULT: '#10b981',
                            50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0',
                            300: '#6ee7b7', 400: '#34d399', 500: '#10b981',
                            600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b',
                        },
                        kos: { purple: '#8b5cf6' },
                    },
                },
            },
        }
    </script>
    <style>
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans antialiased">

    <!-- TIER 1 — Top Utility Bar -->
    <div class="bg-gray-100 border-b border-gray-200 text-xs text-gray-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-8">
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-brand transition">Download App</a>
                <span class="text-gray-300">|</span>
                <a href="#" class="hover:text-brand transition">Sewa Kos</a>
                <span class="text-gray-300">|</span>
                <a href="?page=catalog" class="hover:text-brand transition">Cari Kost</a>
            </div>
            <div>
                <a href="#" class="hover:text-brand transition">Promosikan Iklan Anda</a>
            </div>
        </div>
    </div>

    <!-- TIER 2 — Main Navbar -->
    <nav class="sticky top-0 z-50 bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <a href="?page=catalog" class="flex items-center gap-2 flex-shrink-0">
                    <div class="w-9 h-9 bg-brand rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="hidden sm:inline text-xl font-bold text-gray-900">KosBooking</span>
                </a>

                <div class="flex-1 max-w-xl mx-auto">
                    <form action="?page=catalog" method="GET" class="flex">
                        <input type="hidden" name="page" value="catalog">
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" name="search" value="<?= e($_GET['search'] ?? '') ?>"
                                   placeholder="Cari kos (nama, fasilitas)..."
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand text-sm">
                        </div>
                        <button type="submit" class="bg-brand hover:bg-emerald-600 text-white font-medium px-5 py-2.5 rounded-r-lg transition text-sm">Cari</button>
                    </form>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <a href="#" class="hidden md:inline text-sm text-gray-600 hover:text-brand transition">Pusat Bantuan</a>
                    <a href="#" class="hidden lg:inline text-sm text-gray-600 hover:text-brand transition">Syarat & Ketentuan</a>
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                            <a href="?page=admin_dashboard" class="text-sm text-gray-600 hover:text-brand transition">Admin</a>
                        <?php endif; ?>
                        <a href="?page=logout" class="text-sm font-medium text-red-500 hover:text-red-600 transition">Logout</a>
                    <?php else: ?>
                        <a href="?page=login" class="inline-block border-2 border-brand text-brand hover:bg-brand hover:text-white font-medium text-sm px-5 py-2 rounded-lg transition">Masuk</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- FLASH MESSAGES -->
    <?php
    $_flash_success = $_SESSION['_flash']['success'] ?? null;
    $_flash_error   = $_SESSION['_flash']['error']   ?? null;
    unset($_SESSION['_flash']['success'], $_SESSION['_flash']['error']);
    ?>
    <?php if ($_flash_success): ?>
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><?= e($_flash_success) ?></span>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($_flash_error): ?>
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><?= e($_flash_error) ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 w-full">
