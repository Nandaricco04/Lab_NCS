<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$id = (string)($_GET['id'] ?? $_GET['id_visi'] ?? '');

if ($id === '') {
    http_response_code(400);
    exit('ID tidak valid.');
}

try {
    $res = qparams('SELECT "isi_visi" FROM "visi" WHERE "id_visi"=$1', [$id]);
    $row = pg_fetch_assoc($res);
    if (!$row) {
        http_response_code(404);
        exit('Data tidak ditemukan.');
    }
} catch (Throwable $e) {
    exit('Error: ' . htmlspecialchars($e->getMessage()));
}

$Isi_Visi = $row['isi_visi'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Isi_Visi = trim($_POST['isi_visi'] ?? '');

    if ($Isi_Visi === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'UPDATE "visi"
                   SET "isi_visi"=$1
                 WHERE "id_visi"=$2',
                [$Isi_Visi, $id]
            );
            header('Location: visi.php');
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
    <title>Ubah Visi</title>
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
                        Ubah Visi
                    </div>
                    <form class="form-user-box" method="post" enctype="multipart/form-data" autocomplete="off">
                        <div class="desc-form">
                            Ubah data visi di bawah sesuai kebutuhan.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="isi_visi" class="user-form-label">Isi</label><span style="color:red">*</span>
                            <textarea name="isi_visi" id="isi_visi" class="user-form-input" rows="8" required autocomplete="off" placeholder="Masukkan isi"><?= htmlspecialchars($Isi_Visi) ?></textarea>
                        </div>

                        <div class="form-btn-bar" style="margin-top:32px;">
                            <button class="btn-user-primary" type="submit">
                                <span class="iconify" data-icon="mdi:content-save"></span> Simpan
                            </button>
                            <a class="btn-user-warning" href="visi.php">
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