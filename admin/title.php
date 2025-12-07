<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_title", "judul", "gambar_path" FROM "v_title_pages" ORDER BY "id_title" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Title</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
     <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Title</div>
            <p>
                <a class="btn btn-primary" href="title_create.php" style="margin-left: 0;">+ Tambah Title</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Gambar Page</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="6">Belum ada title.</td>
                    </tr>
                <?php else: foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_title'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['judul'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($r['gambar_path'])): ?>
                                <img src="<?= htmlspecialchars($r['gambar_path']) ?>" alt="Gambar" style="max-width:100px;max-height:100px;">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-warning" style="margin: 2px;" href="title_update.php?id=<?= urlencode($r['id_title']) ?>">Ubah</a>
                            <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_title'] ?>').submit(); }">Hapus</a>

                            <form id="deleteForm<?= $r['id_title'] ?>" action="title_delete.php" method="post" style="display:none;">
                                <input type="hidden" name="id_title" value="<?= htmlspecialchars($r['id_title']) ?>">
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