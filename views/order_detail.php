<?php
$userId = (int) ($_SESSION['user_id'] ?? 0);
$txId = (int) ($_GET['id'] ?? 0); // Mengambil ID transaksi/invoice dari URL

// Proteksi jika user belum login
if ($userId === 0) {
    setFlash('error', 'Silakan login terlebih dahulu.');
    redirect('?page=login');
}

// Mengambil detail transaksi, data kamar, dan data user (jika diperlukan)
$stmt = $pdo->prepare('
    SELECT t.*, r.name as room_name, r.facilities, r.price_per_month, r.image_path
    FROM transactions t
    JOIN rooms r ON t.room_id = r.id
    WHERE t.id = ? AND t.user_id = ?
');
$stmt->execute([$txId, $userId]);
$order = $stmt->fetch();

// Jika transaksi tidak ditemukan atau bukan milik user tersebut
if (!$order) {
    setFlash('error', 'Detail pesanan tidak ditemukan.');
    redirect('?page=orders');
}
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="?page=orders" class="text-sm text-gray-500 hover:text-gray-900 flex items-center gap-2 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Pesanan Saya
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Nomor Invoice</span>
                <h1 class="text-lg font-bold text-gray-900">#<?= e($order['id']) ?></h1>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Status</span>
                <div class="mt-0.5">
                    <?php if ($order['status'] === 'Draft' || $order['status'] === 'Pending'): ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-800 border border-yellow-200">Menunggu Pembayaran</span>
                    <?php elseif ($order['status'] === 'Success' || $order['status'] === 'Paid'): ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-800 border border-green-200">Selesai / Aktif</span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-800 border border-red-200"><?= e($order['status']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <div class="flex flex-col sm:flex-row gap-4 border-b border-gray-100 pb-6">
                <?php if ($order['image_path'] && file_exists(__DIR__ . '/../' . $order['image_path'])): ?>
                    <img src="<?= e($order['image_path']) ?>" alt="Kamar" class="w-full sm:w-32 h-24 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                <?php else: ?>
                    <div class="w-full sm:w-32 h-24 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                <?php endif; ?>
                <div>
                    <h2 class="text-base font-bold text-gray-900"><?= e($order['room_name']) ?></h2>
                    <p class="text-xs text-gray-500 mt-1">Fasilitas: <?= e($order['facilities'] ?: 'Fasilitas standar') ?></p>
                    <p class="text-xs text-gray-400 mt-2">Waktu Transaksi: <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl text-sm border border-gray-100">
                <div>
                    <span class="text-gray-500 block text-xs">Durasi Sewa</span>
                    <strong class="text-gray-900 font-semibold"><?= (int)$order['duration_months'] ?> Bulan</strong>
                </div>
                <div>
                    <span class="text-gray-500 block text-xs">Harga per Bulan</span>
                    <strong class="text-gray-900 font-semibold"><?= formatRupiah((float)$order['price_per_month']) ?></strong>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-3">Rincian Pembayaran</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Total Harga Sewa (<?= (int)$order['duration_months'] ?> bulan)</span>
                        <span><?= formatRupiah((float)($order['price_per_month'] * $order['duration_months'])) ?></span>
                    </div>
                    <div class="flex justify-between border-t border-gray-100 pt-3 text-base text-gray-900 font-bold">
                        <span>Total Bayar</span>
                        <span class="text-brand"><?= formatRupiah((float)($order['total_amount'] ?? 0)) ?></span>
                    </div>
                </div>
            </div>

            <?php if ($order['status'] === 'Draft' || $order['status'] === 'Pending'): ?>
                <div class="pt-4 border-t border-gray-100">
                    <a href="?page=checkout&t=<?= $order['id'] ?>" 
                       class="block w-full text-center bg-brand hover:bg-emerald-600 text-white font-semibold py-2.5 rounded-lg text-sm transition shadow-sm">
                        Lanjutkan Pembayaran Sekarang
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>