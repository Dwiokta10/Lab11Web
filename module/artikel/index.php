<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../class/Database.php';
require_once __DIR__ . '/../../class/Form.php';

// Pastikan class Database sudah di-include dari index.php utama
$db = new Database();

// Ambil semua data user
$result = $db->query("SELECT * FROM users ORDER BY id DESC");
?>

<?php include __DIR__ . '/../../template/sidebar.php'; ?>

<?php $baseUrl = defined('APP_BASE_URL') ? APP_BASE_URL : ''; ?>

<?php
$success = isset($_GET['success']) ? (string) $_GET['success'] : '';
if ($success === 'simpan') {
    $nama = isset($_GET['nama']) ? (string) $_GET['nama'] : '';
    echo "<div class='alert alert-success py-2 mb-3'>Data pengguna \"" . htmlspecialchars($nama) . "\" berhasil disimpan!</div>";
} elseif ($success === 'hapus') {
    $id = isset($_GET['id']) ? (string) $_GET['id'] : '';
    echo "<div class='alert alert-success py-2 mb-3'>Data pengguna dengan ID \"" . htmlspecialchars($id) . "\" berhasil dihapus!</div>";
} elseif ($success === 'ubah') {
    $id = isset($_GET['id']) ? (string) $_GET['id'] : '';
    echo "<div class='alert alert-success py-2 mb-3'>Data pengguna dengan ID \"" . htmlspecialchars($id) . "\" berhasil diubah!</div>";
}
?>

<div class="app-subtitle mb-1">Data Pengguna (Module Artikel - Index)</div>
<hr class="app-divider mt-0 mb-2">

<a href="<?php echo htmlspecialchars($baseUrl . '/artikel/tambah'); ?>" class="app-add-link mb-2">+ Tambah Data Baru</a>

<div class="table-responsive">
    <table class="table table-bordered table-sm app-table">
        <thead>
            <tr>
                <th width="50">ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Jenis Kelamin</th>
                <th>Agama</th>
                <th>Hobi</th>
                <th width="100">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <?php
                        $jk = isset($row['jenis_kelamin']) ? (string) $row['jenis_kelamin'] : '';
                        echo ($jk === 'Laki-laki') ? 'L' : (($jk === 'Perempuan') ? 'P' : htmlspecialchars($jk));
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['agama']); ?></td>
                    <td><?php echo htmlspecialchars($row['hobi']); ?></td>
                    <td>
                        <a href="<?php echo htmlspecialchars($baseUrl . '/artikel/ubah?id=' . $row['id']); ?>" class="text-decoration-none">Ubah</a>
                        |
                        <a href="<?php echo htmlspecialchars($baseUrl . '/artikel/hapus?id=' . $row['id']); ?>" class="text-decoration-none text-danger" onclick="return confirm('Yakin ingin menghapus data ID <?php echo $row['id']; ?>?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">
                    <div class="alert alert-danger mb-0 py-2">Belum ada data pengguna yang tersimpan.</div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

