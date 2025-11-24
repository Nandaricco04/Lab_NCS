<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_title'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "id_title", "judul", "gambar_path" FROM "title_pages" WHERE "id_title"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$Id_Title = $row['id_title'];
$Judul = $row['judul'];
$Gambar_Path = $row['gambar_path'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_Title = trim($_POST['id_title'] ?? '');
    $Judul = trim($_POST['judul'] ?? '');

    $gambar_path_baru = $Gambar_Path;

    if (isset($_FILES['gambar_path']) && $_FILES['gambar_path']['error'] == UPLOAD_ERR_OK) {
        $img_dir = 'images/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir, 0755, true);
        }
        $tmp_name = $_FILES['gambar_path']['tmp_name'];
        $nama_file = basename($_FILES['gambar_path']['name']);
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_unik = 'title_' . time() . '_' . uniqid() . '.' . $ext;
        $dest_path = $img_dir . $nama_file_unik;

        if (move_uploaded_file($tmp_name, $dest_path)) {
            $gambar_path_baru = $dest_path;
        } else {
            $err = 'Gagal mengupload gambar.';
        }
    }

    if ($Id_Title === '' || $Judul === '' || $gambar_path_baru === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "title_pages"
                   SET "judul"=$1, "gambar_path"=$2
                 WHERE "id_title"=$3',
                [$Judul, $gambar_path_baru, $id]
            );
            header('Location: title.php');
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
    <title>Ubah Title</title>
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
                        Ubah Title
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Ubah data title di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="id_title" class="user-form-label">Id Title</label>
                            <input type="text" name="id_title" id="id_title" class="user-form-input" value="<?= htmlspecialchars($Id_Title) ?>" required autocomplete="off" placeholder="Masukkan Id Title">
                        </div>

                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul</label>
                            <input type="text" name="judul" id="judul" class="user-form-input" value="<?= htmlspecialchars($Judul) ?>" required autocomplete="off" placeholder="Masukkan judul">
                        </div>

                        <div class="form-group">
                            <label for="gambar_path" class="user-form-label">Gambar Path</label>
                            <input type="file" name="gambar_path" id="gambar_path" class="user-form-input" accept="image/*">
                            <?php if ($Gambar_Path): ?>
                                <div style="margin-top:8px;">
                                    <img src="<?= htmlspecialchars($Gambar_Path) ?>" alt="Gambar saat ini" style="max-width:200px;max-height:200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="title.php">
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