<?php
$roomId = (int) ($_GET['room'] ?? 0);
$userId = (int) ($_SESSION['user_id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = ?');
$stmt->execute([$roomId]);
$room = $stmt->fetch();

if (!$room) { setFlash('error', 'Kamar tidak ditemukan.'); redirect('?page=catalog'); }
if ($room['status'] !== 'Available') { setFlash('error', 'Kamar sedang tidak tersedia.'); redirect('?page=catalog'); }
if (isRoomLocked($pdo, $roomId)) { setFlash('error', 'Kamar sedang diproses oleh pengguna lain.'); redirect('?page=catalog'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $duration = (int) ($_POST['duration_months'] ?? 0);
    if ($duration < 1 || $duration > 24) {
        $error = 'Durasi sewa harus antara 1 - 24 bulan.';
    } else {
        try {
            $txId = createDraftTransaction($pdo, $userId, $roomId, $duration);
            setFlash('success', 'Kamar berhasil dipesan! Silakan lanjutkan ke pembayaran.');
            redirect('?page=checkout&t=' . $txId);
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        }
    }
}
?><?php require __DIR__ . '/../includes/layout.php'; ?>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-brand to-emerald-600 px-6 py-5">
                <h1 class="text-xl font-bold text-white">Pesan Kamar</h1>
                <p class="text-emerald-100 text-sm mt-1">Isi durasi sewa untuk melanjutkan</p>
            </div>
            <div class="p-6">
                <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm"><?= e($error) ?></div>
                <?php endif; ?>

                <div class="flex gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-brand-100 to-brand-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900"><?= e($room['name']) ?></h2>
                        <p class="text-sm text-gray-500 mt-0.5"><?= e($room['facilities'] ?: 'Fasilitas tidak tersedia') ?></p>
                        <p class="text-brand font-bold mt-1"><?= formatRupiah((float) $room['price_per_month']) ?>/bln</p>
                    </div>
                </div>

                <form method="POST" class="space-y-5">
                    <div>
                        <label for="duration_months" class="block text-sm font-medium text-gray-700 mb-1">Durasi Sewa</label>
                        <div class="flex items-center gap-3">
                            <input type="number" id="duration_months" name="duration_months" value="1" min="1" max="24" required
                                   class="w-24 border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-center focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                            <span class="text-sm text-gray-600">Bulan</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1.5">Minimal 1 bulan, maksimal 24 bulan.</p>
                    </div>

                    <?php
                    $pricePerMonth = (float) $room['price_per_month'];
                    $deposit = $pricePerMonth * 0.5;
                    ?>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600"><span>Harga per bulan</span><span><?= formatRupiah($pricePerMonth) ?></span></div>
                        <div class="flex justify-between text-gray-600"><span>Deposit (50%)</span><span><?= formatRupiah($deposit) ?></span></div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between font-bold text-gray-900"><span>Total per bulan</span><span><?= formatRupiah($pricePerMonth + $deposit) ?></span></div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3 text-xs text-yellow-800 flex items-start gap-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Pemesanan ini akan mengunci kamar selama <strong>15 menit</strong>. Selesaikan pembayaran sebelum batas waktu.</span>
                    </div>

                    <button type="submit" class="w-full bg-brand hover:bg-emerald-600 text-white font-semibold py-3 rounded-lg transition text-sm">
                        Lanjut ke Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
