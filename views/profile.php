<?php
$userId = (int) ($_SESSION['user_id'] ?? 0);

// Proteksi jika user belum login
if ($userId === 0) {
    setFlash('error', 'Silakan login terlebih dahulu untuk mengakses profil.');
    redirect('?page=login');
}

// 1. Ambil data username terbaru dari database
$stmt = $pdo->prepare('SELECT id, username, role, created_at FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    setFlash('error', 'Pengguna tidak ditemukan.');
    redirect('?page=catalog');
}

$error = '';
$success = '';

// 2. Proses Update Profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '') {
        $error = 'Username tidak boleh kosong.';
    } else {
        try {
            // Cek apakah username sudah dipakai oleh orang lain
            $checkUser = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
            $checkUser->execute([$username, $userId]);
            
            if ($checkUser->fetch()) {
                $error = 'Username sudah digunakan oleh orang lain.';
            } else {
                // Jika password baru diisi
                if ($password !== '') {
                    if (strlen($password) < 6) {
                        $error = 'Password baru minimal harus 6 karakter.';
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                        $updateStmt = $pdo->prepare('UPDATE users SET username = ?, password = ? WHERE id = ?');
                        $updateStmt->execute([$username, $hashedPassword, $userId]);
                        $success = 'Username dan password berhasil diperbarui!';
                    }
                } else {
                    // Jika password dikosongkan (hanya ubah username)
                    $updateStmt = $pdo->prepare('UPDATE users SET username = ? WHERE id = ?');
                    $updateStmt->execute([$username, $userId]);
                    $success = 'Username berhasil diperbarui!';
                }

                if ($success) {
                    setFlash('success', $success);
                    redirect('?page=profile');
                }
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    }
}
?>

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="bg-gradient-to-r from-brand to-emerald-600 px-6 py-6 text-white flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center text-xl font-bold uppercase border border-white/30">
                <?= substr(e($user['username']), 0, 2) ?>
            </div>
            <div>
                <h1 class="text-lg font-bold">@<?= e($user['username']) ?></h1>
                <p class="text-emerald-100 text-xs mt-0.5">Status Account: <span class="capitalize font-semibold"><?= e($user['role'] ?? 'User') ?></span></p>
            </div>
        </div>

        <div class="p-6">
            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username Anda</label>
                    <input type="text" id="username" name="username" value="<?= e($user['username']) ?>" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                </div>

                <hr class="border-gray-200">

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Ganti Password (Opsional)</label>
                    <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin diubah"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand">
                    <p class="text-xs text-gray-400 mt-1.5">Isi hanya jika Anda ingin mengganti password login lama Anda.</p>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-brand hover:bg-emerald-600 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>