<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$Judul = $Gambar_Path = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Judul      = trim($_POST['judul'] ?? '');

    if ($Judul === '' || empty($_FILES['gambar_path']['name'])) {
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
            $err = 'Format file gambar harus jpg, jpeg, png, gif, atau bmp.';
        } else {
            $new_filename = uniqid('title') . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                $Gambar_Path = 'images/' . $new_filename;

                try {
                    qparams(
                        'INSERT INTO "title_pages" ("judul", "gambar_path") VALUES ($1, $2)',
                        [$Judul, $Gambar_Path]
                    );
                    header('Location: title.php');
                    exit;
                } catch (Throwable $e) {
                    $err = $e->getMessage();
                    if (file_exists($destination)) unlink($destination);
                }
            } else {
                $err = 'Gagal upload gambar.';
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
    <title>Tambah Title</title>
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
                        Tambah Title
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Isi data title di bawah dengan lengkap dan benar.<br>
                            Upload gambar maksimal 2MB (format: jpg/png/gif/bmp).
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul<span style="color:red">*</span></label>
                            <input type="text" name="judul" id="judul" class="user-form-input" value="<?= htmlspecialchars($Judul) ?>" required autocomplete="off" placeholder="Masukkan Judul">
                        </div>

                        <div class="form-group">
                            <label for="gambar_path" class="user-form-label">Gambar<span style="color:red">*</span></label>
                            <input type="file" name="gambar_path" id="gambar_path" class="user-form-input" required accept=".jpg,.jpeg,.png,.gif,.bmp">
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