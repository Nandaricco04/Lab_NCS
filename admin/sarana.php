<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT * FROM v_sarana_prasarana ORDER BY id_sarana_prasarana ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Sarana Prasarana</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
     <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Sarana Prasarana</div>
            <p>
                <a class="btn btn-primary" href="sarana_create.php" style="margin-left: 0;">+ Tambah Sarana Prasarana</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Judul</th>
                        <th>Sub Judul</th>
                        <th>Jumlah Alat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="6">Belum ada sarana prasarana.</td>
                    </tr>
                <?php else: foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_sarana_prasarana'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($r['gambar_path'])): ?>
                                <img src="<?= htmlspecialchars($r['gambar_path']) ?>" alt="gambar_path" style="max-width:100px;max-height:100px;">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($r['judul'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['sub_judul'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['jumlah_alat']) ?></td>

                        <td>
                            <a class="btn btn-warning" href="sarana_update.php?id=<?= urlencode($r['id_sarana_prasarana']) ?>">Ubah</a>
                            <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_sarana_prasarana'] ?>').submit(); }">Hapus</a>

                            <form id="deleteForm<?= $r['id_sarana_prasarana'] ?>" action="sarana_delete.php" method="post" style="display:none;">
                                <input type="hidden" name="id_sarana_prasarana" value="<?= htmlspecialchars($r['id_sarana_prasarana']) ?>">
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