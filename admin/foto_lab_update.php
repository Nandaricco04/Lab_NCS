<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_ruang'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "gambar_path" FROM "ruang_lab" WHERE "id_ruang"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$Gambar_path = $row['gambar_path'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_Ruang = trim($_POST['id_ruang'] ?? '');
    $Gambar_path_baru = $Gambar_path;

    if (isset($_FILES['gambar_path']) && $_FILES['gambar_path']['error'] == UPLOAD_ERR_OK) {
        $img_dir = 'images/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir, 0755, true);
        }
        $tmp_name = $_FILES['gambar_path']['tmp_name'];
        $nama_file = basename($_FILES['gambar_path']['name']);
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_unik = 'fotolab_' . time() . '_' . uniqid() . '.' . $ext;
        $dest_path = $img_dir . $nama_file_unik;

        if (move_uploaded_file($tmp_name, $dest_path)) {
            $Gambar_path_baru = $dest_path;
        } else {
            $err = 'Gagal mengupload gambar.';
        }
    }

    if ($Id_Ruang === '' || $Gambar_path_baru === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "ruang_lab"
                   SET "gambar_path"=$1
                 WHERE "id_ruang"=$2',
                [$Gambar_path_baru, $id]
            );
            header('Location: foto_lab.php');
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
        <title>Ubah Foto Lab</title>
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
                        Ubah Foto Lab
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Ubah data foto lab di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="gambar_path" class="user-form-label">Foto</label>
                            <input type="file" name="gambar_path" id="gambar_path" class="user-form-input" accept="images/*">
                            <?php if ($Gambar_path): ?>
                                <div style="margin-top:8px;">
                                    <img src="<?= htmlspecialchars($Gambar_path) ?>" alt="Foto saat ini" style="max-width:200px;max-height:200px;">
                                </div>
                            <?php endif; ?>
                        </div>
                                
                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="foto_lab.php">
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