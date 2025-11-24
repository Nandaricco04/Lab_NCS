<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_penelitian", "judul", "deskripsi", "file_path" FROM "penelitian" ORDER BY "id_penelitian" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Penelitian</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Penelitian</div>
            <p>
                <a class="btn btn-primary" href="penelitian_create.php" style="margin-left: 0;">+ Tambah Penelitian</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="5">Belum ada penelitian.</td>
                        </tr>
                        <?php else: foreach ($rows as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['id_penelitian'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['judul'] ?? '') ?></td>
                                <td><?= htmlspecialchars($r['deskripsi'] ?? '') ?></td>
                                <td>
                                    <?php if (!empty($r['file_path'])): ?>
                                        <?php $filename = basename($r['file_path']); ?>
                                        <a href="<?= htmlspecialchars($r['file_path']) ?>" target="_blank">
                                            <?= htmlspecialchars($filename) ?>
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="penelitian_update.php?id=<?= urlencode($r['id_penelitian']) ?>">Ubah</a>
                                    <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_penelitian'] ?>').submit(); }">Hapus</a>
                                    <form id="deleteForm<?= $r['id_penelitian'] ?>" action="penelitian_delete.php" method="post" style="display:none;">
                                        <input type="hidden" name="id_penelitian" value="<?= htmlspecialchars($r['id_penelitian']) ?>">
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