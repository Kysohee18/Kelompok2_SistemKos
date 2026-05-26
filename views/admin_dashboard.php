<?php
if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ?page=catalog');
    exit;
}

$tab = $_GET['tab'] ?? 'overview';
$allowedTabs = ['overview', 'rooms', 'transactions'];
if (!in_array($tab, $allowedTabs)) {
    $tab = 'overview';
}

// Fetch stats for overview tab
$totalRooms = 0;
$availableRooms = 0;
$pendingTx = 0;
$activeBookings = 0;
if ($tab === 'overview') {
    $totalRooms = (int) $pdo->query('SELECT COUNT(*) FROM rooms')->fetchColumn();
    $availableRooms = (int) $pdo->query("SELECT COUNT(*) FROM rooms WHERE status = 'Available'")->fetchColumn();
    $pendingTx = (int) $pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'WAITING_VALIDATION'")->fetchColumn();
    $activeBookings = (int) $pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'ACTIVE'")->fetchColumn();
}
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — KosBooking Pro</title>
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
                        brand: { DEFAULT: '#10b981', 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b' },
                        kos: { purple: '#8b5cf6' },
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased">
<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
        <div class="p-5 border-b border-gray-700">
            <a href="?page=admin_dashboard" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span class="text-lg font-bold">KosBooking</span>
            </a>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="?page=admin_dashboard&tab=overview"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= $tab === 'overview' ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Overview
            </a>
            <a href="?page=admin_dashboard&tab=rooms"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= $tab === 'rooms' ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Kelola Kamar
            </a>
            <a href="?page=admin_dashboard&tab=transactions"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= $tab === 'transactions' ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Verifikasi Transaksi
            </a>
        </nav>
        <div class="p-4 border-t border-gray-700">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-brand rounded-full flex items-center justify-center text-white text-xs font-bold uppercase"><?= e(substr($_SESSION['username'] ?? 'A', 0, 1)) ?></div>
                <div class="text-sm">
                    <p class="font-medium text-white"><?= e($_SESSION['username'] ?? 'Admin') ?></p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
            <a href="?page=logout" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition px-1 py-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </a>
            <a href="?page=catalog" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition px-1 py-1.5 mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Situs
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- TOP BAR -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">
                <?php
                $titleMap = ['overview' => 'Dashboard Overview', 'rooms' => 'Kelola Kamar', 'transactions' => 'Verifikasi Transaksi'];
                echo $titleMap[$tab] ?? 'Dashboard';
                ?>
            </h1>
            <div class="text-sm text-gray-400"><?= date('l, d F Y') ?></div>
        </header>

        <!-- FLASH MESSAGES -->
        <?php
        $_flash_success = $_SESSION['_flash']['success'] ?? null;
        $_flash_error   = $_SESSION['_flash']['error'] ?? null;
        unset($_SESSION['_flash']['success'], $_SESSION['_flash']['error']);
        ?>
        <?php if ($_flash_success): ?>
        <div class="px-6 pt-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?= e($_flash_success) ?></span>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($_flash_error): ?>
        <div class="px-6 pt-4">
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2 text-sm">
                <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?= e($_flash_error) ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- DYNAMIC CONTENT -->
        <main class="flex-1 overflow-y-auto p-6">

            <?php if ($tab === 'overview'): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-medium text-gray-500">Total Kamar</p>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg></div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900"><?= $totalRooms ?></p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-medium text-gray-500">Tersedia</p>
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center"><svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900"><?= $availableRooms ?></p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-medium text-gray-500">Menunggu Verifikasi</p>
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center"><svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900"><?= $pendingTx ?></p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-sm font-medium text-gray-500">Penyewa Aktif</p>
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center"><svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900"><?= $activeBookings ?></p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 text-center">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Selamat Datang, <?= e($_SESSION['username'] ?? 'Admin') ?>!</h2>
                <p class="text-gray-500">Gunakan menu di sidebar untuk mengelola kamar dan memverifikasi transaksi.</p>
            </div>

            <?php elseif ($tab === 'rooms'): ?>
                <?php require __DIR__ . '/admin_rooms_ui.php'; ?>
            <?php elseif ($tab === 'transactions'): ?>
                <?php require __DIR__ . '/admin_transactions_ui.php'; ?>
            <?php endif; ?>

        </main>
    </div>
</div>
</body>
</html>
