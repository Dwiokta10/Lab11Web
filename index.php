<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ob_start();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/template/header.php';

$page = '';

if (isset($_GET['page'])) {
    $page = trim((string) $_GET['page']);
} else {
    $pathInfo = isset($_SERVER['PATH_INFO']) ? (string) $_SERVER['PATH_INFO'] : '';
    if ($pathInfo !== '') {
        $page = trim($pathInfo, '/');
    } else {
        $uriPath = isset($_SERVER['REQUEST_URI']) ? (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '';
        $baseDir = rtrim(str_replace('\\', '/', (string) dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/');

        if ($baseDir !== '' && strpos($uriPath, $baseDir) === 0) {
            $uriPath = substr($uriPath, strlen($baseDir));
        }

        $page = trim($uriPath, '/');
    }
}

$page = trim($page, '/');
if ($page === '' || $page === 'index.php') {
    $page = 'artikel/index';
}

if (strpos($page, 'index.php/') === 0) {
    $page = substr($page, strlen('index.php/'));
}

if ($page === 'artikel') {
    $page = 'artikel/index';
}

$isSafePage = preg_match('/^[a-zA-Z0-9_\/-]+$/', $page) === 1 && strpos($page, '..') === false;

if ($isSafePage) {
    $baseUrl = defined('APP_BASE_URL') ? APP_BASE_URL : '';
    $isLoggedIn = isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user']);
    $isAuthPage = (strpos($page, 'auth/') === 0);
    $isArtikelPage = (strpos($page, 'artikel/') === 0);

    if ($isArtikelPage && !$isLoggedIn) {
        $requestUri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
        $next = $requestUri !== '' ? $requestUri : ($baseUrl . '/artikel/index');
        header('Location: ' . $baseUrl . '/auth/login?next=' . urlencode($next));
        exit;
    }

    $target = __DIR__ . '/module/' . $page . '.php';
    if (is_file($target)) {
        require $target;
    } else {
        echo "<div class='alert alert-danger py-2'>Halaman tidak ditemukan.</div>";
    }
} else {
    echo "<div class='alert alert-danger py-2'>Halaman tidak valid.</div>";
}

require_once __DIR__ . '/template/footer.php';

ob_end_flush();

