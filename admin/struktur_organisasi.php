<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_pengelola", "foto_path", "nama", "posisi", "nip" FROM "struktur_organisasi" ORDER BY "id_pengelola" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Struktur Organisasi</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
     <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Struktur Organisasi</div>
            <p>
                <a class="btn btn-primary" href="struktur_organisasi_create.php" style="margin-left: 0;">+ Tambah Struktur Organisasi</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Posisi</th>
                        <th>NIP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="6">Belum ada struktur organisasi.</td>
                    </tr>
                <?php else: foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_pengelola'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($r['foto_path'])): ?>
                                <img src="<?= htmlspecialchars($r['foto_path']) ?>" alt="foto_path" style="max-width:100px;max-height:100px;">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($r['nama'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['posisi'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['nip'] ?? '') ?></td>
                        <td>
                            <a class="btn btn-warning" href="struktur_organisasi_update.php?id=<?= urlencode($r['id_pengelola']) ?>">Ubah</a>
                            <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_pengelola'] ?>').submit(); }">Hapus</a>

                            <form id="deleteForm<?= $r['id_pengelola'] ?>" action="struktur_organisasi_delete.php" method="post" style="display:none;">
                                <input type="hidden" name="id_pengelola" value="<?= htmlspecialchars($r['id_pengelola']) ?>">
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