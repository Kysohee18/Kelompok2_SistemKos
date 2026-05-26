<?php
$txId   = (int) ($_GET['t'] ?? 0);
$userId = (int) ($_SESSION['user_id'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT t.*, r.name AS room_name, r.price_per_month, r.facilities
     FROM transactions t JOIN rooms r ON t.room_id = r.id
     WHERE t.id = ? AND t.user_id = ?'
);
$stmt->execute([$txId, $userId]);
$tx = $stmt->fetch();

if (!$tx) { setFlash('error', 'Transaksi tidak ditemukan.'); redirect('?page=catalog'); }

if ($tx['status'] !== 'DRAFT') {
    if ($tx['status'] === 'WAITING_VALIDATION') setFlash('error', 'Pembayaran sudah dikirim. Silakan tunggu verifikasi admin.');
    else setFlash('error', 'Transaksi tidak dapat diproses (status: ' . e($tx['status']) . ').');
    redirect('?page=catalog');
}

$remaining = getRemainingTTL($pdo, $txId);
if ($remaining <= 0) { setFlash('error', 'Waktu pemesanan habis. Silakan pesan ulang.'); redirect('?page=catalog'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Silakan pilih file bukti pembayaran.';
    } elseif (!validateFileType($_FILES['payment_proof'])) {
        $error = 'File harus bertipe JPG atau PNG.';
    } elseif (!validateFileSize($_FILES['payment_proof'])) {
        $error = 'Ukuran file maksimal 2 MB.';
    } else {
        $uploadDir = __DIR__ . '/../uploads/payments/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext  = strtolower(pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION));
        $name = 'payment_' . $txId . '_' . time() . '.' . $ext;
        $dest = $uploadDir . $name;

        if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $dest)) {
            $stmt = $pdo->prepare("UPDATE transactions SET status = 'WAITING_VALIDATION', payment_proof = ?, expires_at = NULL WHERE id = ? AND status = 'DRAFT'");
            $stmt->execute(['uploads/payments/' . $name, $txId]);
            setFlash('success', 'Bukti pembayaran berhasil diunggah! Admin akan memverifikasi dalam waktu 1x24 jam.');
            redirect('?page=catalog');
        } else {
            $error = 'Gagal mengunggah file. Silakan coba lagi.';
        }
    }
}
?><?php require __DIR__ . '/../includes/layout.php'; ?>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-brand to-emerald-600 px-6 py-5">
                <h1 class="text-xl font-bold text-white">Checkout</h1>
                <p class="text-emerald-100 text-sm mt-1">Selesaikan pembayaran dalam waktu 15 menit</p>
            </div>
            <div class="p-6">
                <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm"><?= e($error) ?></div>
                <?php endif; ?>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3 mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-sm text-yellow-800">Sisa waktu: <strong id="ttl-timer" class="text-yellow-900"><?= gmdate('i:s', $remaining) ?></strong></div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-3">Ringkasan Pesanan</h2>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600">Kamar</span><span class="font-medium text-gray-900"><?= e($tx['room_name']) ?></span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Durasi</span><span class="font-medium text-gray-900"><?= (int) $tx['duration_months'] ?> bulan</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Harga per bulan</span><span class="font-medium text-gray-900"><?= formatRupiah((float) $tx['price_per_month']) ?></span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span class="font-medium text-gray-900"><?= formatRupiah((float) $tx['total_amount']) ?></span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Deposit (50%)</span><span class="font-medium text-gray-900"><?= formatRupiah((float) $tx['deposit']) ?></span></div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between text-base font-bold"><span class="text-gray-900">Total Dibayar</span><span class="text-brand"><?= formatRupiah((float) $tx['total_amount'] + (float) $tx['deposit']) ?></span></div>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-brand transition cursor-pointer" onclick="document.getElementById('payment_proof').click()">
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <p class="text-sm text-gray-500 mb-1"><span class="text-brand font-medium">Klik untuk upload</span> atau drag & drop</p>
                            <p class="text-xs text-gray-400">JPG / PNG, maksimal 2 MB</p>
                            <p id="file-name" class="text-xs text-brand mt-2 font-medium hidden"></p>
                        </div>
                        <input type="file" id="payment_proof" name="payment_proof" accept=".jpg,.jpeg,.png" class="hidden"
                               onchange="document.getElementById('file-name').textContent = this.files[0]?.name; document.getElementById('file-name').classList.remove('hidden')">
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-sm text-blue-800">
                        <p class="font-medium mb-1">Transfer ke rekening berikut:</p>
                        <p class="text-blue-700">Bank BCA — 1234567890 — a.n. KosBooking Pro</p>
                        <p class="text-blue-700 mt-0.5">Bank Mandiri — 0987654321 — a.n. KosBooking Pro</p>
                    </div>

                    <button type="submit" class="w-full bg-brand hover:bg-emerald-600 text-white font-semibold py-3 rounded-lg transition text-sm">Konfirmasi Pembayaran</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    (function() {
        var remaining = <?= $remaining ?>;
        var el = document.getElementById('ttl-timer');
        function pad(n) { return n < 10 ? '0' + n : n; }
        function tick() {
            if (remaining <= 0) { el.textContent = '00:00'; setTimeout(function() { window.location.href = '?page=catalog'; }, 1000); return; }
            el.textContent = pad(Math.floor(remaining/60)) + ':' + pad(remaining%60);
            remaining--;
        }
        tick();
        setInterval(tick, 1000);
    })();
    </script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
