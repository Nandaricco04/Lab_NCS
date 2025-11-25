<?php
session_start();
if (!isset($_SESSION['id_pengguna'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/koneksi.php';

$res = q('SELECT "id_agenda_kegiatan", "keterangan", "tanggal_mulai", "tanggal_selesai", "kategori", "foto" FROM "v_agenda_kegiatan" ORDER BY "id_agenda_kegiatan" ASC');
$rows = pg_fetch_all($res) ?: [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Galeri</title>
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js" defer></script>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
     <div class="layout">
        <?php include 'sidebar.php'; ?>

        <div class="content">
            <div class="main-table-title">Daftar Galeri</div>
            <p>
                <a class="btn btn-primary" href="galeri_create.php" style="margin-left: 0;">+ Tambah Galeri</a>
            </p>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>keterangan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Kategori</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$rows): ?>
                    <tr>
                        <td colspan="7">Belum ada galeri.</td>
                    </tr>
                <?php else: foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id_agenda_kegiatan'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['keterangan'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['tanggal_mulai'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['tanggal_selesai'] ?? '') ?></td>
                        <td><?= htmlspecialchars($r['kategori'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($r['foto'])): ?>
                                <img src="<?= htmlspecialchars($r['foto']) ?>" alt="foto" style="max-width:100px;max-height:100px;">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-warning" href="galeri_update.php?id=<?= urlencode($r['id_agenda_kegiatan']) ?>">Ubah</a>
                            <a href="#" class="btn btn-danger" onclick="if(confirm('Hapus data ini?')) { document.getElementById('deleteForm<?= $r['id_agenda_kegiatan'] ?>').submit(); }">Hapus</a>

                            <form id="deleteForm<?= $r['id_agenda_kegiatan'] ?>" action="galeri_delete.php" method="post" style="display:none;">
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