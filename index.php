<?php
// ============================================================
// index.php — Front Controller Router
// website_ready — Monolithic Demo
// ============================================================

require_once __DIR__ . '/config.php';

// --- Route Definition ---
$routes = [
    // Public
    'catalog'            => ['view' => 'catalog',            'auth' => false, 'admin' => false],
    'login'              => ['view' => 'login',              'auth' => false, 'admin' => false],
    'register'           => ['view' => 'register',           'auth' => false, 'admin' => false],
    'logout'             => ['action' => 'auth',             'auth' => false, 'admin' => false],

    // Authenticated
    'booking'            => ['view' => 'booking',            'auth' => true,  'admin' => false],
    'checkout'           => ['view' => 'checkout',           'auth' => true,  'admin' => false],

    // Admin — Unified Dashboard
    'admin_dashboard'    => ['view' => 'admin_dashboard',    'auth' => true,  'admin' => true],
    'admin_process'      => ['action' => 'admin_process',    'auth' => true,  'admin' => true],
];

$page  = $_GET['page'] ?? 'catalog';
$route = $routes[$page] ?? null;

// --- 404 ---
if (!$route) {
    http_response_code(404);
    require __DIR__ . '/includes/layout.php';
    echo '<div class="text-center py-16">
              <h1 class="text-4xl font-bold text-gray-800 mb-4">404</h1>
              <p class="text-gray-600 mb-6">Halaman tidak ditemukan.</p>
              <a href="?page=catalog" class="inline-block bg-brand text-white px-6 py-2.5 rounded-lg hover:bg-emerald-600 transition">Kembali ke Katalog</a>
          </div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

if ($route['auth'] && empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: ?page=login');
    exit;
}

// --- Admin Middleware ---
if ($route['admin'] && ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ?page=catalog');
    exit;
}

// --- Dispatch ---
if (isset($route['action'])) {
    $path = __DIR__ . '/actions/' . $route['action'] . '.php';
} else {
    $path = __DIR__ . '/views/' . $route['view'] . '.php';
}

if (!file_exists($path)) {
    die('Module tidak ditemukan: ' . htmlspecialchars(basename($path), ENT_QUOTES, 'UTF-8'));
}

require $path;
