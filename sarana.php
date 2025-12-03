<?php
require_once 'admin/koneksi.php';

// Ambil data title/hero
try {
    $sql = "SELECT * FROM v_title_pages WHERE id_title = 4 LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}

// Ambil parameter pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;
$offset = ($page - 1) * $perPage;

// Ambil data sarana prasarana dengan pagination
try {
    $sql = "SELECT * FROM v_sarana_prasarana ORDER BY id_sarana_prasarana DESC LIMIT $1 OFFSET $2";
    $result = qparams($sql, [$perPage, $offset]);
    $saranaItems = pg_fetch_all($result);

    // Hitung total data
    $countSql = "SELECT COUNT(*) as total FROM v_sarana_prasarana";
    $countResult = q($countSql);
    $totalCount = pg_fetch_result($countResult, 0, 0);
} catch (Exception $e) {
    $saranaItems = [];
    $totalCount = 0;
    error_log($e->getMessage());
}

$totalPages = ceil($totalCount / $perPage);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarana & Prasarana - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/sarana.css?v=<?= time() ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero-section" style="background-image: url('admin/<?= htmlspecialchars($homeData['gambar_path'] ?? 'images/hero-bg.jpg') ?>');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1><?= $homeData['judul'] ?? 'N/A' ?></h1>
            </div>
        </section>

        <!-- Sarana Section -->
        <section class="section sarana-section">
            <div class="container">
                <div class="section-header">
                    <a href="konsultasi.php" class="btn btn-konsultasi">Konsultasi</a>
                </div>

                <?php if (!empty($saranaItems)): ?>
                <div class="row g-4">
                    <?php foreach ($saranaItems as $item): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="sarana-card">
                            <div class="sarana-image">
                                <img src="admin/<?= htmlspecialchars($item['gambar_path']) ?>"
                                     alt="<?= htmlspecialchars($item['judul']) ?>">
                            </div>
                            <div class="sarana-content">
                                <h3 class="sarana-title"><?= htmlspecialchars($item['judul']) ?></h3>
                                <p class="sarana-description">
                                    <?= htmlspecialchars($item['sub_judul']) ?>
                                </p>
                                <p class="sarana-unit">Tersedia <?= htmlspecialchars($item['jumlah_alat']) ?> Unit</p>
                                <a href="peminjaman.php " class="btn btn-pinjaman">Ajukan Pinjaman</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted">Belum ada data sarana dan prasarana.</p>
                </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination-section">
                    <button class="pagination-btn"
                            <?= $page <= 1 ? 'disabled' : '' ?>
                            onclick="changePage(<?= $page - 1 ?>)">
                        ◄ Previous
                    </button>

                    <span class="pagination-info">
                        <?= (($page - 1) * $perPage) + 1 ?>-<?= min($page * $perPage, $totalCount) ?> of <?= $totalCount ?>
                    </span>

                    <button class="pagination-btn"
                            <?= $page >= $totalPages ? 'disabled' : '' ?>
                            onclick="changePage(<?= $page + 1 ?>)">
                        Next ►
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pagination
        function changePage(page) {
            window.location.href = `sarana.php?page=${page}`;
        }
    </script>
</body>
</html>
