<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_misi", "isi_misi" FROM "misi" ORDER BY "id_misi" ASC');
$rows = pg_fetch_all($res) ?: [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Misi</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Misi</div>
            <p>
                <a class="btn btn-primary" href="misi_create.php" style="margin-left: 0;">+ Tambah Misi</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Isi Misi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="6">Belum ada misi.</td>
                    </tr>
                <?php else: foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_misi'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['isi_misi'] ?? '') ?></td>
                        <td>
                            <a class="btn btn-warning" href="misi_update.php?id=<?= urlencode($r['id_misi']) ?>">Ubah</a>
                            <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_misi'] ?>').submit(); }">Hapus</a>

                            <form id="deleteForm<?= $r['id_misi'] ?>" action="misi_delete.php" method="post" style="display:none;">
                                <input type="hidden" name="id_misi" value="<?= htmlspecialchars($r['id_misi']) ?>">
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