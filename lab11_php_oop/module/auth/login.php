<?php

require_once __DIR__ . '/../../class/Database.php';

$baseUrl = defined('APP_BASE_URL') ? APP_BASE_URL : '';

$next = isset($_GET['next']) ? (string) $_GET['next'] : '';
if ($next === '') {
    $next = $baseUrl . '/artikel/index';
}

$tab = isset($_GET['tab']) ? (string) $_GET['tab'] : 'login';
$tab = ($tab === 'register') ? 'register' : 'login';

if (isset($_SESSION['auth_user']) && is_array($_SESSION['auth_user'])) {
    header('Location: ' . $next);
    exit;
}

$pesan = '';

$registered = isset($_GET['registered']) ? (string) $_GET['registered'] : '';
if ($registered === '1') {
    $pesan = "<div class='alert alert-success py-2 mb-3'>Registrasi berhasil. Silakan login.</div>";
    $tab = 'login';
}

if ($_POST) {
    $action = isset($_POST['action']) ? (string) $_POST['action'] : 'login';
    $action = ($action === 'register') ? 'register' : 'login';

    if ($action === 'register') {
        $tab = 'register';

        $nama = isset($_POST['nama']) ? trim((string) $_POST['nama']) : '';
        $username = isset($_POST['username']) ? trim((string) $_POST['username']) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';
        $password2 = isset($_POST['password2']) ? (string) $_POST['password2'] : '';

        if ($nama === '' || $username === '' || $password === '' || $password2 === '') {
            $pesan = "<div class='alert alert-danger py-2 mb-3'>Semua field wajib diisi.</div>";
        } elseif ($password !== $password2) {
            $pesan = "<div class='alert alert-danger py-2 mb-3'>Konfirmasi password tidak sama.</div>";
        } elseif (strlen($password) < 6) {
            $pesan = "<div class='alert alert-danger py-2 mb-3'>Password minimal 6 karakter.</div>";
        } else {
            try {
                $db = new Database();

                $cek = $db->query("SHOW TABLES LIKE 'users_login'");
                if (!$cek || (int) $cek->num_rows === 0) {
                    $pesan = "<div class='alert alert-danger py-2 mb-3'>Tabel <b>users_login</b> belum ada di database. Buat dulu tabelnya di phpMyAdmin (database sesuai <code>config.php</code>).</div>";
                } else {
                    $stmt = $db->prepare('SELECT id FROM users_login WHERE username = ? LIMIT 1');
                    if ($stmt) {
                        $stmt->bind_param('s', $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $exist = $result ? $result->fetch_assoc() : null;
                        $stmt->close();

                        if ($exist) {
                            $pesan = "<div class='alert alert-danger py-2 mb-3'>Username sudah dipakai. Silakan pilih username lain.</div>";
                        } else {
                            $hash = password_hash($password, PASSWORD_DEFAULT);
                            $stmt2 = $db->prepare('INSERT INTO users_login (username, password, nama) VALUES (?, ?, ?)');
                            if ($stmt2) {
                                $stmt2->bind_param('sss', $username, $hash, $nama);
                                $ok = $stmt2->execute();
                                $stmt2->close();

                                if ($ok) {
                                    header('Location: ' . $baseUrl . '/auth/login?tab=login&registered=1&next=' . urlencode($next));
                                    exit;
                                }
                            }

                            $pesan = "<div class='alert alert-danger py-2 mb-3'>Gagal membuat akun. Coba lagi.</div>";
                        }
                    } else {
                        $pesan = "<div class='alert alert-danger py-2 mb-3'>Gagal memproses registrasi. Coba lagi.</div>";
                    }
                }
            } catch (mysqli_sql_exception $e) {
                $pesan = "<div class='alert alert-danger py-2 mb-3'>Koneksi/Query database bermasalah: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
    } else {
        $tab = 'login';

        $username = isset($_POST['username']) ? trim((string) $_POST['username']) : '';
        $password = isset($_POST['password']) ? (string) $_POST['password'] : '';

        if ($username === '' || $password === '') {
            $pesan = "<div class='alert alert-danger py-2 mb-3'>Username dan password wajib diisi.</div>";
        } else {
            try {
                $db = new Database();

                $cek = $db->query("SHOW TABLES LIKE 'users_login'");
                if (!$cek || (int) $cek->num_rows === 0) {
                    $pesan = "<div class='alert alert-danger py-2 mb-3'>Tabel <b>users_login</b> belum ada di database. Buat dulu tabelnya di phpMyAdmin (database sesuai <code>config.php</code>), lalu coba login lagi.</div>";
                } else {
                    $stmt = $db->prepare('SELECT id, username, password, nama FROM users_login WHERE username = ? LIMIT 1');
                    if ($stmt) {
                        $stmt->bind_param('s', $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result ? $result->fetch_assoc() : null;
                        $stmt->close();

                        if ($user && isset($user['password']) && password_verify($password, (string) $user['password'])) {
                            $_SESSION['auth_user'] = [
                                'id' => (int) $user['id'],
                                'username' => (string) $user['username'],
                                'nama' => (string) $user['nama'],
                            ];

                            header('Location: ' . $next);
                            exit;
                        }
                    }

                    $pesan = "<div class='alert alert-danger py-2 mb-3'>Login gagal. Username atau password salah.</div>";
                }
            } catch (mysqli_sql_exception $e) {
                $pesan = "<div class='alert alert-danger py-2 mb-3'>Koneksi/Query database bermasalah: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        }
    }
}

$loggedOut = isset($_GET['logged_out']) ? (string) $_GET['logged_out'] : '';
if ($loggedOut === '1') {
    $pesan = "<div class='alert alert-success py-2 mb-3'>Kamu sudah logout.</div>";
}

?>

<div class="app-subtitle mb-1">Login & Register</div>
<hr class="app-divider mt-0 mb-3">

<?php echo $pesan; ?>

<?php
$tabLoginActive = ($tab === 'login') ? 'active' : '';
$tabRegisterActive = ($tab === 'register') ? 'active' : '';
?>

<ul class="nav nav-tabs mb-3" style="max-width: 520px;">
    <li class="nav-item">
        <a class="nav-link <?php echo $tabLoginActive; ?>" href="<?php echo htmlspecialchars($baseUrl . '/auth/login?tab=login&next=' . urlencode($next)); ?>">Login</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo $tabRegisterActive; ?>" href="<?php echo htmlspecialchars($baseUrl . '/auth/login?tab=register&next=' . urlencode($next)); ?>">Register</a>
    </li>
</ul>

<?php if ($tab === 'register'): ?>
    <form action="<?php echo htmlspecialchars($baseUrl . '/auth/login?tab=register&next=' . urlencode($next)); ?>" method="POST" style="max-width: 520px;">
        <input type="hidden" name="action" value="register">
        <div class="mb-2">
            <label class="form-label form-label-sm mb-1">Nama</label>
            <input type="text" name="nama" class="form-control form-control-sm app-input" required>
        </div>
        <div class="mb-2">
            <label class="form-label form-label-sm mb-1">Username</label>
            <input type="text" name="username" class="form-control form-control-sm app-input" autocomplete="username" required>
        </div>
        <div class="mb-2">
            <label class="form-label form-label-sm mb-1">Password</label>
            <input type="password" name="password" class="form-control form-control-sm app-input" autocomplete="new-password" required>
        </div>
        <div class="mb-3">
            <label class="form-label form-label-sm mb-1">Konfirmasi Password</label>
            <input type="password" name="password2" class="form-control form-control-sm app-input" autocomplete="new-password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-3">Buat Akun</button>
    </form>
<?php else: ?>
    <form action="<?php echo htmlspecialchars($baseUrl . '/auth/login?tab=login&next=' . urlencode($next)); ?>" method="POST" style="max-width: 520px;">
        <input type="hidden" name="action" value="login">
        <div class="mb-2">
            <label class="form-label form-label-sm mb-1">Username</label>
            <input type="text" name="username" class="form-control form-control-sm app-input" autocomplete="username" required>
        </div>
        <div class="mb-3">
            <label class="form-label form-label-sm mb-1">Password</label>
            <input type="password" name="password" class="form-control form-control-sm app-input" autocomplete="current-password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-3">Login</button>
    </form>
<?php endif; ?>
