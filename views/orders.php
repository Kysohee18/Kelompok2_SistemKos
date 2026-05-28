<?php
$userId = (int) ($_SESSION['user_id'] ?? 0);

if ($userId === 0) {
    setFlash('error', 'Silakan login terlebih dahulu untuk melihat riwayat pesanan.');
    redirect('?page=login');
}

// OPTIMASI: Tambahkan LEFT JOIN ke tabel room_reviews untuk mengecek status review
$stmt = $pdo->prepare('
    SELECT t.*, r.name as room_name, r.price_per_month,
           (CASE WHEN rev.id IS NOT NULL THEN 1 ELSE 0 END) as is_reviewed
    FROM transactions t
    JOIN rooms r ON t.room_id = r.id
    LEFT JOIN room_reviews rev ON t.id = rev.transaction_id
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
');
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau status pembayaran dan riwayat sewa kos Anda di sini.</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Pesanan</h3>
            <p class="text-sm text-gray-500 mb-6">Anda belum pernah melakukan pemesanan kamar kos.</p>
            <a href="?page=catalog" class="inline-flex items-center justify-center bg-brand hover:bg-emerald-600 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                Cari Kamar Sekarang
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                    <div class="bg-gray-50 px-5 py-3 border-b border-gray-100 flex flex-wrap justify-between items-center gap-2 text-xs text-gray-500">
                        <div>
                            <span>Tanggal: <strong><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></strong></span>
                            <span class="mx-2">•</span>
                            <span>Invoice: <strong>#<?= e($order['id']) ?></strong></span>
                        </div>

                        <div>
                            <?php if ($order['status'] === 'Draft' || $order['status'] === 'Pending'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-800 border border-yellow-200">Menunggu Pembayaran</span>
                            <?php elseif ($order['status'] === 'Success' || $order['status'] === 'Paid'): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-800 border border-green-200">Selesai / Aktif</span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-800 border border-red-200"><?= e($order['status']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex gap-4 items-start">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900"><?= e($order['room_name']) ?></h3>
                                <p class="text-xs text-gray-500 mt-0.5">Durasi Sewa: <strong><?= (int)$order['duration_months'] ?> Bulan</strong></p>
                                <p class="text-xs text-gray-400 mt-1">Total Bayar: <span class="text-gray-900 font-semibold"><?= formatRupiah((float) ($order['total_amount'] ?? 0)) ?></span></p>
                            </div>
                        </div>

                        <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center justify-end gap-2 text-right">

                            <?php
                            // Mengubah status ke huruf besar semua agar aman dari perbedaan huruf besar/kecil di database
                            $status = strtoupper($order['status']);
                            ?>

                            <?php if ($status === 'DRAFT' || $status === 'PENDING'): ?>
                                <a href="?page=checkout&t=<?= $order['id'] ?>"
                                    class="inline-block w-full sm:w-auto text-center bg-brand hover:bg-emerald-600 text-white font-semibold px-4 py-2 rounded-lg text-xs transition shadow-sm">
                                    Bayar Sekarang
                                </a>
                            <?php else: ?>
                                <a href="?page=order_detail&id=<?= $order['id'] ?>"
                                    class="inline-block w-full sm:w-auto text-center bg-white hover:bg-gray-50 text-gray-700 font-semibold px-4 py-2 border border-gray-300 rounded-lg text-xs transition shadow-sm">
                                    Lihat Detail
                                </a>

                                <?php if (in_array($status, ['SUCCESS', 'PAID', 'ACTIVE']) && $order['is_reviewed'] == 0): ?>
                                    <a href="?page=review&id=<?= $order['id'] ?>"
                                        class="inline-block w-full sm:w-auto text-center bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg text-xs transition shadow-sm flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Tulis Review
                                    </a>
                                <?php elseif ($order['is_reviewed'] == 1): ?>
                                    <span class="inline-block w-full sm:w-auto text-center bg-gray-100 text-gray-500 font-medium px-3 py-2 rounded-lg text-xs border border-gray-200">
                                        Sudah Direview
                                    </span>
                                <?php endif; ?>

                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>