<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_penelitian'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "id_penelitian", "judul", "tahun", "file_path" FROM "penelitian" WHERE "id_penelitian"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$id_penelitian = $row['id_penelitian'];
$judul = $row['judul'];
$tahun = $row['tahun'];
$file_path = $row['file_path'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_penelitian = trim($_POST['id_penelitian'] ?? '');
    $judul = trim($_POST['judul'] ?? '');
    $tahun = trim($_POST['tahun'] ?? '');
    $file_path_baru = $file_path;

    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] == UPLOAD_ERR_OK) {
        $allowed_ext = ['pdf'];
        $upload_dir = __DIR__ . '/files/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $tmp_name = $_FILES['file_path']['tmp_name'];
        $nama_file = basename($_FILES['file_path']['name']);
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $err = 'File harus berformat PDF.';
        } elseif ($_FILES['file_path']['size'] > 2 * 1024 * 1024) {
            $err = 'Ukuran file maksimal 2MB.';
        } else {
            $nama_file_unik = 'penelitian_' . time() . '_' . uniqid() . '.' . $ext;
            $dest_path = $upload_dir . $nama_file_unik;

            if (move_uploaded_file($tmp_name, $dest_path)) {
                $file_path_baru = 'files/' . $nama_file_unik;
            } else {
                $err = 'Gagal upload file.';
            }
        }
    }

    if ($id_penelitian === '' || $judul === '' || $tahun === '') {
        $err = 'Semua field wajib diisi.';
    }
    
    if (!$err) {
        try {
            qparams(
                'UPDATE "penelitian"
                   SET "judul"=$1, "tahun"=$2, "file_path"=$3
                 WHERE "id_penelitian"=$4',
                [$judul, $tahun, $file_path_baru, $id_penelitian]
            );
            header('Location: penelitian.php');
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
    <title>Ubah Penelitian</title>
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
                        Ubah Penelitian
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Ubah data penelitian di bawah sesuai kebutuhan.<br>
                            File hanya menerima PDF maksimal 2MB.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="id_penelitian" class="user-form-label">Id Penelitian</label>
                            <input type="text" name="id_penelitian" id="id_penelitian" class="user-form-input" value="<?= htmlspecialchars($id_penelitian) ?>" required autocomplete="off" placeholder="Masukkan Id Penelitian" readonly>
                        </div>

                        <div class="form-group">
                            <label for="judul" class="user-form-label">Judul</label>
                            <input type="text" name="judul" id="judul" class="user-form-input" value="<?= htmlspecialchars($judul) ?>" required autocomplete="off" placeholder="Masukkan Judul">
                        </div>

                        <div class="form-group">
                            <label for="tahun" class="user-form-label">Tahun</label>
                            <input type="date" name="tahun" id="tahun" class="user-form-input" value="<?= htmlspecialchars($tahun) ?>" required autocomplete="off" placeholder="Masukkan tahun">
                        </div>

                        <div class="form-group">
                            <label for="file_path" class="user-form-label">File PDF</label>
                            <input type="file" name="file_path" id="file_path" class="user-form-input" accept=".pdf">
                            <?php if ($file_path): ?>
                                <div style="margin-top:8px;">
                                    <a href="<?= htmlspecialchars($file_path) ?>" target="_blank"><?= htmlspecialchars(basename($file_path)) ?></a>
                                </div>
                            <?php endif; ?>
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