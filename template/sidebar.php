<?php
// Template Sidebar
?>

<?php
$currentPage = '';
if (isset($_GET['page'])) {
    $currentPage = trim((string) $_GET['page']);
} elseif (isset($_SERVER['PATH_INFO']) && (string) $_SERVER['PATH_INFO'] !== '') {
    $currentPage = trim((string) $_SERVER['PATH_INFO'], '/');
} else {
    $uriPath = isset($_SERVER['REQUEST_URI']) ? (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
    $baseDir = rtrim(str_replace('\\', '/', (string) dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
    if ($baseDir !== '' && strpos($uriPath, $baseDir) === 0) {
        $uriPath = substr($uriPath, strlen($baseDir));
    }
    $currentPage = trim($uriPath, '/');
}

$currentPage = trim($currentPage, '/');
if ($currentPage === '' || $currentPage === 'index.php') {
    $currentPage = 'artikel/index';
}
if (strpos($currentPage, 'index.php/') === 0) {
    $currentPage = substr($currentPage, strlen('index.php/'));
}
if ($currentPage === 'artikel') {
    $currentPage = 'artikel/index';
}

$baseUrl = defined('APP_BASE_URL') ? APP_BASE_URL : '';

$isLoggedIn = isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user']);
$authNama = $isLoggedIn && isset($_SESSION['auth_user']['nama']) ? (string) $_SESSION['auth_user']['nama'] : '';

$hrefIndex = $baseUrl . '/artikel/index';
$hrefTambah = $baseUrl . '/artikel/tambah';
$hrefLogin = $baseUrl . '/auth/login';
$hrefLogout = $baseUrl . '/auth/logout';

$isIndex = $currentPage === 'artikel/index';
$isTambah = $currentPage === 'artikel/tambah';

$classIndex = $isIndex ? 'btn btn-primary btn-sm me-2' : 'btn btn-outline-primary btn-sm me-2';
$classTambah = $isTambah ? 'btn btn-primary btn-sm' : 'btn btn-outline-primary btn-sm';
?>

<h2 class="mb-2">Framework OOP Modular</h2>
<hr class="app-divider mt-0 mb-3">

<div class="mb-3">
    <?php if ($isLoggedIn): ?>
        <a href="<?php echo htmlspecialchars($hrefIndex); ?>" class="<?php echo $classIndex; ?>">Data Pengguna</a>
        <a href="<?php echo htmlspecialchars($hrefTambah); ?>" class="<?php echo $classTambah; ?>">Tambah Data</a>
        <a href="<?php echo htmlspecialchars($hrefLogout); ?>" class="btn btn-outline-danger btn-sm ms-2">Logout</a>
        <?php if ($authNama !== ''): ?>
            <span class="ms-2 text-muted" style="font-size: 0.85rem;">(<?php echo htmlspecialchars($authNama); ?>)</span>
        <?php endif; ?>
    <?php else: ?>
        <?php
        $requestUri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
        $next = $requestUri !== '' ? $requestUri : ($baseUrl . '/artikel/index');
        ?>
        <a href="<?php echo htmlspecialchars($hrefLogin . '?next=' . urlencode($next)); ?>" class="btn btn-primary btn-sm">Login</a>
    <?php endif; ?>
</div>
