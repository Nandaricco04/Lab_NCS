<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$foto_path = $nama = $posisi = $nip = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama           = trim($_POST['nama'] ?? '');
    $posisi         = trim($_POST['posisi'] ?? '');
    $nip            = trim($_POST['nip'] ?? '');

    if ($nama === '' || $posisi === '' || $nip === '' || empty($_FILES['foto_path']['name'])) {
        $err = 'Semua field wajib diisi.';
    } else {
        $upload_dir = __DIR__ . '/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = basename($_FILES['foto_path']['name']);
        $file_tmp = $_FILES['foto_path']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

        if (!in_array($file_ext, $allowed_ext)) {
            $err = 'Format file foto harus jpg, jpeg, png, gif, atau bmp.';
        } elseif ($_FILES['foto_path']['size'] > 2 * 1024 * 1024) {
            $err = 'Ukuran foto harus maksimal 2MB.';
        } else {
            $new_filename = uniqid('struktur_organisasi_') . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                $foto_path = 'images/' . $new_filename;

                try {
                    qparams(
                        'INSERT INTO "struktur_organisasi" ("foto_path", "nama", "posisi", "nip") VALUES ($1, $2, $3, $4)',
                        [$foto_path, $nama, $posisi, $nip]
                    );
                    header('Location: struktur_organisasi.php');
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
    <title>Tambah Struktur Organisasi</title>
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
                        Tambah Struktur Organisasi
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Isi data struktur organisasi di bawah dengan lengkap dan benar.<br>
                            Upload gambar maksimal 2MB (format: jpg/png/gif/bmp).
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="foto_path" class="user-form-label">Foto<span style="color:red">*</span></label>
                            <input type="file" name="foto_path" id="foto_path" class="user-form-input" required accept=".jpg,.jpeg,.png,.gif,.bmp">
                        </div>

                        <div class="form-group">
                            <label for="nama" class="user-form-label">Nama<span style="color:red">*</span></label>
                            <input type="text" name="nama" id="nama" class="user-form-input" value="<?= htmlspecialchars($nama) ?>" required autocomplete="off" placeholder="Masukkan Nama">
                        </div>

                        <div class="form-group">
                            <label for="posisi" class="user-form-label">Posisi<span style="color:red">*</span></label>
                            <select name="posisi" id="posisi" class="user-form-input" required>
                                <option value="">-- Pilih Posisi --</option>
                                <option value="Kepala Lab" <?= $posisi === 'Kepala Lab' ? 'selected' : '' ?>>Kepala Lab</option>
                                <option value="Peneliti" <?= $posisi === 'Peneliti' ? 'selected' : '' ?>>Peneliti</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nip" class="user-form-label">NIP<span style="color:red">*</span></label>
                            <input type="text" name="nip" id="nip" class="user-form-input" value="<?= htmlspecialchars($nip) ?>" required autocomplete="off" placeholder="Masukkan NIP">
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