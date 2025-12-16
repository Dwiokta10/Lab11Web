<?php

require_once __DIR__ . '/../../class/Database.php';
require_once __DIR__ . '/../../class/Form.php';

$db = new Database();

$baseUrl = defined('APP_BASE_URL') ? APP_BASE_URL : '';

// Proses simpan data
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

    $simpan = $db->insert('users', $data);
    if ($simpan) {
        header('Location: ' . $baseUrl . '/artikel/index?success=simpan&nama=' . urlencode($data['nama']));
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal menyimpan data.</div>";
    }
}

// Buat form dengan helper Form
$form = new Form($baseUrl . '/artikel/tambah', 'Simpan Data');
$form->addField('nama', 'Nama Lengkap');
$form->addField('email', 'Email');
$form->addField('pass', 'Password', 'password');
$form->addField('jenis_kelamin', 'Jenis Kelamin', 'radio', [
    'Laki-laki' => 'Laki-laki',
    'Perempuan' => 'Perempuan',
]);
$form->addField('agama', 'Agama', 'select', [
    'Islam' => 'Islam',
    'Kristen' => 'Kristen',
    'Katolik' => 'Katolik',
    'Hindu' => 'Hindu',
    'Buddha' => 'Buddha',
    'Konghucu' => 'Konghucu',
]);
$form->addField('hobi', 'Hobi', 'checkbox', [
    'Membaca' => 'Membaca',
    'Coding' => 'Coding',
    'Traveling' => 'Traveling',
]);
$form->addField('alamat', 'Alamat Lengkap', 'textarea');

?>

<?php include __DIR__ . '/../../template/sidebar.php'; ?>

<div class="app-subtitle mb-1">Form Input User (Module Artikel)</div>
<hr class="app-divider mt-0 mb-3">

<?php echo $pesan; ?>

<?php $form->displayForm(); ?>

<a href="<?php echo htmlspecialchars($baseUrl . '/artikel/index'); ?>" class="btn btn-link btn-sm mt-2 p-0">Kembali ke Data Pengguna</a>

