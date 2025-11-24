<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$Id_Pengguna = $Username = $Password_Hash = $Nama_Lengkap = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_Pengguna   = trim($_POST['id_pengguna'] ?? '');
    $Username      = trim($_POST['username'] ?? '');
    $Password_Hash = password_hash(trim($_POST['password_hash'] ?? ''), PASSWORD_DEFAULT);
    $Nama_Lengkap  = trim($_POST['nama_lengkap'] ?? '');

    if ($Id_Pengguna === '' || $Username === '' || $Password_Hash === '' || $Nama_Lengkap === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'INSERT INTO "users" ("id_pengguna", "username", "password_hash", "nama_lengkap") VALUES ($1, $2, $3, $4)',
                [$Id_Pengguna, $Username, $Password_Hash, $Nama_Lengkap]
            );
            header('Location: user.php');
            exit;
        } catch (Throwable $e) {
            $err = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Admin</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
</head>

<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>
        <div class="content">
            <div class="flex-center">
                <div class="card-wrapper">
                    <div class="main-table-title" style="margin-bottom:16px;">
                        Tambah Admin
                    </div>
                    <form class="form-user-box" method="post" autocomplete="off">
                        <div class="desc-form">
                            Isi data admin di bawah dengan lengkap dan benar.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="id_pengguna" class="user-form-label">Id Pengguna</label>
                            <input type="text" name="id_pengguna" id="id_pengguna" class="user-form-input" value="<?= htmlspecialchars($Id_Pengguna) ?>" required autocomplete="off" placeholder="Masukkan Id Pengguna">
                        </div>

                        <div class="form-group">
                            <label for="username" class="user-form-label">Username</label>
                            <input type="text" name="username" id="username" class="user-form-input" value="<?= htmlspecialchars($Username) ?>" required autocomplete="off" placeholder="Masukkan username">
                        </div>

                        <div class="form-group">
                            <label for="password_hash" class="user-form-label">Password</label>
                            <input type="password" name="password_hash" id="password_hash" class="user-form-input" value="<?= htmlspecialchars($Password_Hash) ?>" required autocomplete="off" placeholder="Masukkan password">
                        </div>

                        <div class="form-group">
                            <label for="nama_lengkap" class="user-form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="user-form-input" value="<?= htmlspecialchars($Nama_Lengkap) ?>" required autocomplete="off" placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="user.php">
                                <span class="iconify" data-icon="mdi:arrow-left"></span> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>