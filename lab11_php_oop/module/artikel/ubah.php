<?php

require_once __DIR__ . '/../../class/Database.php';

$db = new Database();

$baseUrl = defined('APP_BASE_URL') ? APP_BASE_URL : '';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    echo "<div class='alert alert-danger'>ID user tidak valid.</div>";
    return;
}

// Ambil data lama
$user = $db->get('users', "id=" . $id);
if (!$user) {
    echo "<div class='alert alert-danger'>Data user tidak ditemukan.</div>";
    return;
}

$pesan = '';

if ($_POST) {
    $hobi = isset($_POST['hobi']) && is_array($_POST['hobi']) ? implode(', ', $_POST['hobi']) : '';

    $data = [
        'nama'          => $_POST['nama'] ?? '',
        'email'         => $_POST['email'] ?? '',
        'pass'          => $_POST['pass'] ?? '',
        'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
        'agama'         => $_POST['agama'] ?? '',
        'hobi'          => $hobi,
        'alamat'        => $_POST['alamat'] ?? '',
    ];

    $update = $db->update('users', $data, "id=" . $id);
    if ($update) {
        header('Location: ' . $baseUrl . '/artikel/index?success=ubah&id=' . urlencode((string) $id));
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengubah data.</div>";
    }
}

// Untuk mempermudah, form helper tidak mendukung value default,
// jadi kita buat form manual sederhana di sini.

?>

<?php include __DIR__ . '/../../template/sidebar.php'; ?>

<div class="app-subtitle mb-1">Form Ubah User (Module Artikel)</div>
<hr class="app-divider mt-0 mb-3">

<?php echo $pesan; ?>

<form action="<?php echo htmlspecialchars($baseUrl . '/artikel/ubah?id=' . $id); ?>" method="POST">
    <table class="table table-borderless">
        <tr>
            <td width="150" align="right">Nama Lengkap</td>
            <td><input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" class="form-control app-input"></td>
        </tr>
        <tr>
            <td align="right">Email</td>
            <td><input type="text" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control app-input"></td>
        </tr>
        <tr>
            <td align="right">Password</td>
            <td><input type="password" name="pass" value="<?php echo htmlspecialchars($user['pass']); ?>" class="form-control app-input"></td>
        </tr>
        <tr>
            <td align="right">Jenis Kelamin</td>
            <td>
                <?php $jk = $user['jenis_kelamin']; ?>
                <label><input type="radio" name="jenis_kelamin" value="Laki-laki" <?php echo ($jk == 'Laki-laki') ? 'checked' : ''; ?>> Laki-laki</label>
                <label class="ms-3"><input type="radio" name="jenis_kelamin" value="Perempuan" <?php echo ($jk == 'Perempuan') ? 'checked' : ''; ?>> Perempuan</label>
            </td>
        </tr>
        <tr>
            <td align="right">Agama</td>
            <td>
                <?php $agama = $user['agama']; ?>
                <select name="agama" class="form-select">
                    <?php
                    $opsiAgama = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'];
                    foreach ($opsiAgama as $a) {
                        $sel = ($agama == $a) ? 'selected' : '';
                        echo "<option value='$a' $sel>$a</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td align="right">Hobi</td>
            <td>
                <?php
                $hobiUser = array_map('trim', explode(',', $user['hobi']));
                $opsiHobi = ['Membaca','Coding','Traveling'];
                foreach ($opsiHobi as $h) {
                    $checked = in_array($h, $hobiUser) ? 'checked' : '';
                    echo "<label class='me-3'><input type='checkbox' name='hobi[]' value='$h' $checked> $h</label>";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td align="right">Alamat Lengkap</td>
            <td><textarea name="alamat" rows="3" class="form-control app-input"><?php echo htmlspecialchars($user['alamat']); ?></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit" class="btn btn-success btn-sm px-3">Simpan Data</button>
                <a href="<?php echo htmlspecialchars($baseUrl . '/artikel/index'); ?>" class="btn btn-secondary btn-sm">Batal</a>
            </td>
        </tr>
    </table>
</form>

