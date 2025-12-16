<?php

require_once __DIR__ . '/../../class/Database.php';

$db = new Database();

$baseUrl = defined('APP_BASE_URL') ? APP_BASE_URL : '';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    include __DIR__ . '/../../template/sidebar.php';
    echo "<div class='alert alert-danger py-2'>ID user tidak valid.</div>";
    return;
}

$hapus = $db->delete('users', 'WHERE id=' . $id);
if ($hapus) {
    header('Location: ' . $baseUrl . '/artikel/index?success=hapus&id=' . urlencode((string) $id));
    exit;
}

include __DIR__ . '/../../template/sidebar.php';
echo "<div class='alert alert-danger py-2'>Gagal menghapus data.</div>";
