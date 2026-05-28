<style>
    /* Ini bertugas menyalakan bintang ke arah kiri berkat efek flex-row-reverse */
    .rating-stars input:checked ~ label,
    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #f59e0b !important;
    }
</style>

<?php
$userId = (int) ($_SESSION['user_id'] ?? 0);
$txId = (int) ($_GET['id'] ?? 0); // Ambil ID Transaksi dari URL

// 1. Validasi: Diperbarui agar mendukung status Active / ACTIVE
$stmt = $pdo->prepare('
    SELECT t.*, r.name as room_name 
    FROM transactions t 
    JOIN rooms r ON t.room_id = r.id 
    WHERE t.id = ? AND t.user_id = ? AND t.status IN ("Success", "Paid", "Active", "ACTIVE")
');
$stmt->execute([$txId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    setFlash('error', 'Transaksi tidak valid atau belum diselesaikan.');
    redirect('?page=orders');
}

// 2. Cek apakah user sudah pernah menulis review untuk transaksi ini
$checkReview = $pdo->prepare('SELECT id FROM room_reviews WHERE transaction_id = ?');
$checkReview->execute([$txId]);
$alreadyReviewed = $checkReview->fetch();

$error = '';
// 3. Proses simpan review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyReviewed) {
    $rating = (int) ($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $error = 'Silakan pilih rating bintang 1 sampai 5.';
    } else {
        $insert = $pdo->prepare('
            INSERT INTO room_reviews (room_id, user_id, transaction_id, rating, comment) 
            VALUES (?, ?, ?, ?, ?)
        ');
        $insert->execute([$order['room_id'], $userId, $txId, $rating, $comment]);
        
        setFlash('success', 'Terima kasih! Review Anda berhasil disimpan.');
        redirect('?page=orders');
    }
}
?>

<div class="max-w-xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-brand to-emerald-600 px-6 py-4 text-white">
            <h1 class="text-lg font-bold">Tulis Review Kamar</h1>
            <p class="text-xs text-emerald-100 mt-0.5">Berikan ulasan Anda untuk <?= e($order['room_name']) ?></p>
        </div>

        <div class="p-6">
            <?php if ($alreadyReviewed): ?>
                <div class="text-center py-6">
                    <div class="text-emerald-500 mb-2 font-semibold">Anda sudah memberikan review untuk kamar ini.</div>
                    <a href="?page=orders" class="text-sm text-brand hover:underline">Kembali ke Pesanan Saya</a>
                </div>
            <?php else: ?>
                
                <?php if ($error): ?>
                    <div class="bg-red-50 text-red-700 p-3 rounded-lg text-sm mb-4"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating Kamar</label>
                        <div class="flex items-center flex-row-reverse justify-end gap-1 rating-stars">
                            
                            <input type="radio" id="star5" name="rating" value="5" class="hidden" required />
                            <label for="star5" class="cursor-pointer text-gray-300 hover:text-yellow-400 text-3xl transition">★</label>
                            
                            <input type="radio" id="star4" name="rating" value="4" class="hidden" />
                            <label for="star4" class="cursor-pointer text-gray-300 hover:text-yellow-400 text-3xl transition">★</label>
                            
                            <input type="radio" id="star3" name="rating" value="3" class="hidden" />
                            <label for="star3" class="cursor-pointer text-gray-300 hover:text-yellow-400 text-3xl transition">★</label>
                            
                            <input type="radio" id="star2" name="rating" value="2" class="hidden" />
                            <label for="star2" class="cursor-pointer text-gray-300 hover:text-yellow-400 text-3xl transition">★</label>
                            
                            <input type="radio" id="star1" name="rating" value="1" class="hidden" />
                            <label for="star1" class="cursor-pointer text-gray-300 hover:text-yellow-400 text-3xl transition">★</label>
                            
                        </div>
                    </div>

                    <div>
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Ulasan / Komentar</label>
                        <textarea id="comment" name="comment" rows="4" placeholder="Ceritakan pengalaman Anda tinggal di kost ini..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-brand hover:bg-emerald-600 text-white font-semibold py-2.5 rounded-lg transition text-sm shadow-sm">
                        Kirim Ulasan
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    /* Menyalakan bintang yang dipilih & bintang sebelumnya */
    .rating-stars input:checked ~ label,
    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #f59e0b; /* Warna kuning emas Tailwind (yellow-500) */
    }
</style>