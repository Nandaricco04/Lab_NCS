<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_ruang", "gambar_path" FROM "ruang_lab" ORDER BY "id_ruang" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Foto Lab</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
     <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Foto Lab</div>
            <p>
                <a class="btn btn-primary" href="foto_lab_create.php" style="margin-left: 0;">+ Tambah Foto Lab</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto Lab</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="3">Belum ada foto lab.</td>
                    </tr>
                <?php else: foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_ruang'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($r['gambar_path'])): ?>
                                <img src="<?= htmlspecialchars($r['gambar_path']) ?>" alt="foto" style="max-width:100px;max-height:100px;">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-warning" href="foto_lab_update.php?id=<?= urlencode($r['id_ruang']) ?>">Ubah</a>
                            <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_ruang'] ?>').submit(); }">Hapus</a>

                            <form id="deleteForm<?= $r['id_ruang'] ?>" action="foto_lab_delete.php" method="post" style="display:none;">
                                <input type="hidden" name="id_agenda_kegiatan" value="<?= htmlspecialchars($r['id_agenda_kegiatan']) ?>">
                            </form> 
                        </td>
                    </tr>
                <?php endforeach;
                endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>