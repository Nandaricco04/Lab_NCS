<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_pengelola'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "id_pengelola", "foto_path", "nama", "posisi", "nip" FROM "struktur_organisasi" WHERE "id_pengelola"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$Id_Pengelola = $row['id_pengelola'];
$Foto_Path = $row['foto_path'];
$Nama = $row['nama'];
$Posisi = $row['posisi'];
$NIP = $row['nip'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_Pengelola = trim($_POST['id_pengelola'] ?? '');
    $Nama = trim($_POST['nama'] ?? '');
    $Posisi = trim($_POST['posisi'] ?? '');
    $NIP = trim($_POST['nip'] ?? '');
    $foto_path_baru = $Foto_Path;

    if (isset($_FILES['foto_path']) && $_FILES['foto_path']['error'] == UPLOAD_ERR_OK) {
        $img_dir = 'images/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir, 0755, true);
        }
        $tmp_name = $_FILES['foto_path']['tmp_name'];
        $nama_file = basename($_FILES['foto_path']['name']);
        $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_unik = 'struktur_' . time() . '_' . uniqid() . '.' . $ext;
        $dest_path = $img_dir . $nama_file_unik;

        if (move_uploaded_file($tmp_name, $dest_path)) {
            $foto_path_baru = $dest_path;
        } else {
            $err = 'Gagal mengupload gambar.';
        }
    }

    if ($Id_Pengelola === '' || $Nama === '' || $foto_path_baru === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "pengelola_lab"
                   SET "nama"=$1, "foto_path"=$2, "posisi"=$3
                 WHERE "id_pengelola"=$4',
                [$Nama, $foto_path_baru, $Posisi, $id]
            );
            header('Location: pengelola_lab.php');
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
        <title>Ubah Struktur Organisasi</title>
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
                        Ubah Struktur Organisasi
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Ubah data struktur organisasi di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="id_pengelola" class="user-form-label">Id Pengelola</label>
                            <input type="text" name="id_pengelola" id="id_pengelola" class="user-form-input" value="<?= htmlspecialchars($Id_Pengelola) ?>" required autocomplete="off" placeholder="Masukkan Id Pengelola">
                        </div>

                        <div class="form-group">
                            <label for="foto_path" class="user-form-label">Foto</label>
                            <input type="file" name="foto_path" id="foto_path" class="user-form-input" accept="image/*">
                            <?php if ($Foto_Path): ?>
                                <div style="margin-top:8px;">
                                    <img src="<?= htmlspecialchars($Foto_Path) ?>" alt="Foto saat ini" style="max-width:200px;max-height:200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="nama" class="user-form-label">Nama</label>
                            <input type="text" name="nama" id="nama" class="user-form-input" value="<?= htmlspecialchars($Nama) ?>" required autocomplete="off" placeholder="Masukkan Nama">
                        </div>

                        <div class="form-group">
                            <label for="posisi  " class="user-form-label">Posisi</label>
                            <input type="text" name="posisi" id="posisi" class="user-form-input" value="<?= htmlspecialchars($Posisi) ?>" required autocomplete="off" placeholder="Masukkan Posisi">
                        </div>

                        <div class="form-group">
                            <label for="nip" class="user-form-label">NIP</label>
                            <input type="text" name="nip" id="nip" class="user-form-input" value="<?= htmlspecialchars($NIP) ?>" required autocomplete="off" placeholder="Masukkan NIP">
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