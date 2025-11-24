<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$Id_ruang = $Gambar_path = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_ruang   = trim($_POST['Id_ruang'] ?? '');

    if ($Id_ruang === '' || empty($_FILES['gambar_path']['name'])) {
        $err = 'Semua field wajib diisi.';
    } else {
        $upload_dir = __DIR__ . '/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = basename($_FILES['gambar_path']['name']);
        $file_tmp = $_FILES['gambar_path']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

        if (!in_array($file_ext, $allowed_ext)) {
            $err = 'Format file foto harus jpg, jpeg, png, gif, atau bmp.';
        } elseif ($_FILES['gambar_path']['size'] > 2 * 1024 * 1024) {
            $err = 'Ukuran foto harus maksimal 2MB.';
        } else {
            $new_filename = uniqid('fotolab_') . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                $foto = 'images/' . $new_filename;

                try {
                    qparams(
                        'INSERT INTO "ruang_lab" ("id_ruang", "gambar_path") VALUES ($1, $2)',
                        [$Id_ruang, $foto]
                    );
                    header('Location: foto_lab.php');
                    exit;
                } catch (Throwable $e) {
                    $err = $e->getMessage();
                    if (file_exists($destination)) unlink($destination);
                }
            } else {
                $err = 'Gagal upload foto.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Foto Lab</title>
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
                        Tambah Foto Lab
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Isi data foto lab di bawah dengan lengkap dan benar.<br>
                            Upload gambar maksimal 2MB (format: jpg/png/gif/bmp).
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="Id_ruang" class="user-form-label">Id Ruang</label>
                            <input type="text" name="Id_ruang" id="Id_ruang" class="user-form-input" value="<?= htmlspecialchars($Id_ruang) ?>" required autocomplete="off" placeholder="Masukkan Id Ruang">
                        </div>

                        <div class="form-group">
                            <label for="gambar_path" class="user-form-label">Foto</label>
                            <input type="file" name="gambar_path" id="gambar_path" class="user-form-input" required accept=".jpg,.jpeg,.png,.gif,.bmp">
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="struktur_organisasi.php">
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