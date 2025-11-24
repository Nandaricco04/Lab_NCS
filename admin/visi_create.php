<?php
session_start();

if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$err = '';
$Id_visi = $Isi_visi = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Id_visi   = trim($_POST['id_visi'] ?? '');
    $Isi_visi  = trim($_POST['isi_visi'] ?? '');

    if ($Id_visi === '' || $Isi_visi === '') {
        $err = 'Semua field wajib diisi.';
    } else {
        try {
            qparams(
                'INSERT INTO "visi" ("id_visi", "isi_visi") VALUES ($1, $2)',
                [$Id_visi, $Isi_visi]
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
    <title>Tambah Visi</title>
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
                        Tambah Visi
                    </div>
                    <form class="form-user-box" method="post" autocomplete="off">
                        <div class="desc-form">
                            Isi data visi di bawah dengan lengkap dan benar.
                        </div>
                        <?php if ($err): ?>
                            <div class="form-error"><?= htmlspecialchars($err) ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="id_visi" class="user-form-label">Id Visi</label>
                            <input type="text" name="id_visi" id="id_visi" class="user-form-input" value="<?= htmlspecialchars($Id_visi) ?>" required autocomplete="off" placeholder="Masukkan Id Visi">
                        </div>

                        <div class="form-group">
                            <label for="isi_visi" class="user-form-label">Isi</label>
                            <textarea name="isi_visi" id="isi_visi" class="user-form-input" rows="8" required autocomplete="off" placeholder="Masukkan isi"><?= htmlspecialchars($Isi_visi) ?></textarea>
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