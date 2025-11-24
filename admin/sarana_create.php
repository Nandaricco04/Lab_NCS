<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$Id_sarana_prasarana = $gambar_path = $judul = $sub_judul = $jumlah_alat = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_sarana_prasarana = trim($_POST['id_sarana_prasarana'] ?? '');
    $judul = trim($_POST['judul'] ?? '');
    $sub_judul = trim($_POST['sub_judul'] ?? '');
    $jumlah_alat = trim($_POST['jumlah_alat'] ?? '');

    if ($Id_sarana_prasarana === '' || $judul === '' || $sub_judul === '' || $jumlah_alat === '' || empty($_FILES['gambar_path']['name'])) {
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
            $new_filename = uniqid('sarana_prasarana_') . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                $foto_path = 'images/' . $new_filename;

                try {
                    qparams(
                        'INSERT INTO "sarana_prasarana" ("id_sarana_prasarana", "gambar_path", "judul", "sub_judul", "jumlah_alat") VALUES ($1, $2, $3, $4, $5)',
                        [$Id_sarana_prasarana, $foto_path, $judul, $sub_judul, $jumlah_alat]
                    );
                    header('Location: sarana.php');
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
    <title>Tambah Sarana Prasarana</title>
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
                        Tambah Sarana Prasarana
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Isi data sarana prasarana di bawah dengan lengkap dan benar.<br>
                            Upload gambar maksimal 2MB (format: jpg/png/gif/bmp).
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="id_sarana_prasarana" class="user-form-label">Id Sarana Prasarana</label>
                            <input type="text" name="id_sarana_prasarana" id="id_sarana_prasarana" class="user-form-input" value="<?= htmlspecialchars($Id_sarana_prasarana) ?>" required autocomplete="off" placeholder="Masukkan Id Sarana Prasarana">
                        </div>

                        <div class="form-group">
                            <label for="gambar_path" class="user-form-label">Gambar</label>
                            <input type="file" name="gambar_path" id="gambar_path" class="user-form-input" required accept=".jpg,.jpeg,.png,.gif,.bmp">
                        </div>

                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul</label>
                            <input type="text" name="judul" id="judul" class="user-form-input" value="<?= htmlspecialchars($judul) ?>" required autocomplete="off" placeholder="Masukkan Judul">
                        </div>

                        <div class="form-group">
                            <label for="sub_judul" class="user-form-label">Sub Judul</label>
                            <input type="text" name="sub_judul" id="sub_judul" class="user-form-input" value="<?= htmlspecialchars($sub_judul) ?>" required autocomplete="off" placeholder="Masukkan Sub Judul">
                        </div>

                        <div class="form-group">
                            <label for="jumlah_alat" class="user-form-label">Jumlah Alat</label>
                            <input type="text" name="jumlah_alat" id="jumlah_alat" class="user-form-input" value="<?= htmlspecialchars($jumlah_alat) ?>" required autocomplete="off" placeholder="Masukkan Jumlah Alat">
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="sarana.php">
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