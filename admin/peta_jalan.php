<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_peta_jalan", "judul", "tahun", "file_path" FROM "v_peta_jalan" ORDER BY "id_peta_jalan" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Peta Jalan</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Peta Jalan</div>
            <p>
                <a class="btn btn-primary" href="peta_jalan_create.php" style="margin-left: 0;">+ Tambah Peta Jalan</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Tahun</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rows): ?>
                        <tr>
                            <td colspan="5">Belum ada peta jalan.</td>
                        </tr>
                    <?php else: foreach ($rows as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['id_peta_jalan'] ?? '') ?></td>
                            <td><?= htmlspecialchars($r['judul'] ?? '') ?></td>
                            <td><?= date('Y', strtotime($r['tahun'])) ?></td>
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
                                <a class="btn btn-warning" style="margin: 2px;" href="peta_jalan_update.php?id=<?= urlencode($r['id_peta_jalan']) ?>">Ubah</a>
                                <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_peta_jalan'] ?>').submit(); }">Hapus</a>
                                <form id="deleteForm<?= $r['id_peta_jalan'] ?>" action="peta_jalan_delete.php" method="post" style="display:none;">
                                    <input type="hidden" name="id_peta_jalan" value="<?= htmlspecialchars($r['id_peta_jalan']) ?>">
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
