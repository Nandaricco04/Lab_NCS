<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id_penelitian = $judul = $tahun = $file_path = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $tahun = trim($_POST['tahun'] ?? '');

    if ($judul === '' || $tahun === '' || empty($_FILES['file_path']['name'])) {
        $err = 'Semua field wajib diisi.';
    } else {
        $upload_dir = __DIR__ . '/files/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = basename($_FILES['file_path']['name']);
        $file_tmp = $_FILES['file_path']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['pdf'];

        if (!in_array($file_ext, $allowed_ext)) {
            $err = 'Format file harus PDF.';
        } elseif ($_FILES['file_path']['size'] > 2 * 1024 * 1024) {
            $err = 'Ukuran file harus maksimal 2MB.';
        } else {
            $new_filename = uniqid('penelitian_') . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                $file_path = 'files/' . $new_filename;

                try {
                    qparams(
                        'INSERT INTO "penelitian" ("judul", "tahun", "file_path") VALUES ($1, $2, $3)',
                        [$judul, $tahun, $file_path]
                    );
                    header('Location: penelitian.php');
                    exit;
                } catch (Throwable $e) {
                    $err = $e->getMessage();
                    if (file_exists($destination)) unlink($destination);
                }
            } else {
                $err = 'Gagal upload file.';
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
    <title>Tambah Penelitian</title>
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
                        Tambah Penelitian
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Isi data penelitian di bawah dengan lengkap dan benar.<br>
                            Upload file PDF maksimal 2MB.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul<span style="color:red">*</span></label>
                            <input type="text" name="judul" id="judul" class="user-form-input" value="<?= htmlspecialchars($judul) ?>" required autocomplete="off" placeholder="Masukkan Judul">
                        </div>

                        <div class="form-group">
                            <label for="tahun" class="user-form-label">Tahun<span style="color:red">*</span></label>
                            <input type="date" name="tahun" id="tahun" class="user-form-input" value="<?= htmlspecialchars($tahun) ?>" required autocomplete="off" placeholder="Masukkan tahun">
                        </div>

                        <div class="form-group">
                            <label for="file_path" class="user-form-label">File PDF<span style="color:red">*</span></label>
                            <input type="file" name="file_path" id="file_path" class="user-form-input" required accept=".pdf">
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="penelitian.php">
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