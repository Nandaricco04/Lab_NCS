<?php
require_once 'admin/koneksi.php';

// Get pengabdian ID
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: arsip.php?type=pengabdian');
    exit;
}

// Get pengabdian data
try {
    $sql = "SELECT * FROM pengabdian WHERE id_pengabdian = $1";
    $result = qparams($sql, [$id]);
    $pengabdian = pg_fetch_assoc($result);

    if (!$pengabdian) {
        header('Location: arsip.php?type=pengabdian');
        exit;
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    header('Location: arsip.php?type=pengabdian');
    exit;
}

// Get detail pengabdian with ketua name
try {
    $detailSql = "SELECT dp.*, so.nama as ketua_nama
                  FROM detail_pengabdian dp
                  LEFT JOIN struktur_organisasi so ON dp.id_pengelola::integer = so.id_pengelola
                  WHERE dp.id_pengabdian = $1
                  ORDER BY dp.id_detail_pengabdian";
    $detailResult = qparams($detailSql, [$id]);
    $details = pg_fetch_all($detailResult) ?: [];
} catch (Exception $e) {
    $details = [];
    error_log($e->getMessage());
}

// Ambil data title/hero
try {
    $sql = "SELECT * FROM v_title_pages LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengabdian - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/detail_pengabdian.css?v=<?= time() ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero-section" style="background-image: url('admin/<?= htmlspecialchars($homeData['gambar_path'] ?? 'images/hero-bg.jpg') ?>');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Pengabdian Masyarakat Tahun <?= htmlspecialchars($pengabdian['tahun']) ?></h1>
            </div>
        </section>

        <!-- Detail Section -->
        <section class="section detail-section">
            <div class="container">
                <div class="detail-header">
                    <h2 class="page-subtitle">Arsip Dokumen & Publikasi JTI Polinema</h2>
                    <p class="page-description">Kumpulan hasil penelitian, laporan kegiatan, dan dokumen akademik</p>
                </div>

                <div class="detail-card">
                    <?php if (!empty($details)): ?>
                        <div class="table-responsive">
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Ketua</th>
                                        <th>Prodi</th>
                                        <th>Judul</th>
                                        <th>Skema</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($details as $detail):
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($detail['ketua_nama'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($detail['prodi'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($detail['judul'] ?? '-') ?></td>
                                            <td><?= htmlspecialchars($detail['skema'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-details">
                            <p>Tidak ada detail pengabdian ditemukan.</p>
                        </div>
                    <?php endif; ?>

                    <div class="detail-actions">
                        <a href="arsip.php?type=pengabdian" class="btn btn-back">Kembali ke Arsip</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
