<?php
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '' || $confirm === '') {
        $error = 'Semua field harus diisi.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Username harus 3-50 karakter.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $confirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = 'Username hanya boleh huruf, angka, dan underscore.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = 'Username sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
            $stmt->execute([$username, $hash, 'user']);

            $userId = (int) $pdo->lastInsertId();
            $_SESSION['user_id']  = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['role']     = 'user';

            setFlash('success', 'Pendaftaran berhasil! Selamat datang, ' . e($username) . '.');
            redirect('?page=catalog');
        }
    }
}
?>
    <div class="max-w-md mx-auto mt-10 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Daftar Akun</h1>
            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="POST" class="space-y-5">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" value="<?= e($_POST['username'] ?? '') ?>" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand" placeholder="Buat username">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand" placeholder="Minimal 6 karakter">
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand" placeholder="Ulangi password">
                </div>
                <button type="submit" class="w-full bg-brand hover:bg-emerald-600 text-white font-semibold py-2.5 rounded-lg transition text-sm">Daftar</button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">Sudah punya akun? <a href="?page=login" class="text-brand hover:underline font-medium">Masuk</a></p>
        </div>
    </div>

