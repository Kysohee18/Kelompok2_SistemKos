<?php
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password harus diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $error = 'Username atau password salah.';
        } else {
            $_SESSION['user_id']  = (int) $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            setFlash('success', 'Selamat datang kembali, ' . e($user['username']) . '!');
            $redirectUrl = $_SESSION['redirect_after_login'] ?? '?page=catalog';
            unset($_SESSION['redirect_after_login']);
            redirect($redirectUrl);
        }
    }
}
?><?php require __DIR__ . '/../includes/layout.php'; ?>
    <div class="max-w-md mx-auto mt-10 px-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Masuk</h1>
            <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="POST" class="space-y-5">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" value="<?= e($_POST['username'] ?? '') ?>" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand" placeholder="Masukkan username">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand" placeholder="Masukkan password">
                </div>
                <button type="submit" class="w-full bg-brand hover:bg-emerald-600 text-white font-semibold py-2.5 rounded-lg transition text-sm">Masuk</button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">Belum punya akun? <a href="?page=register" class="text-brand hover:underline font-medium">Daftar</a></p>
        </div>
    </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
