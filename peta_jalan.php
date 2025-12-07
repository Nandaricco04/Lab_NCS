<?php
require_once 'admin/koneksi.php';

// Fetch home data for background image
try {
    $sql = "SELECT * FROM v_title_pages WHERE id_title = 4 LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}

// Pagination setup
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// Fetch peta jalan data with pagination
$petaJalanData = [];
$tahunList = [];
try {
    $sql = "SELECT * FROM v_peta_jalan ORDER BY tahun ASC LIMIT $1 OFFSET $2";
    $result = qparams($sql, [$perPage, $offset]);
    while ($row = pg_fetch_assoc($result)) {
        $petaJalanData[] = $row;
        $tahunList[] = substr($row['tahun'], 0, 4);
    }

    $sqlAll = "SELECT tahun FROM v_peta_jalan ORDER BY tahun ASC";
    $resultAll = q($sqlAll);
    $allYears = [];
    while ($rowAll = pg_fetch_assoc($resultAll)) {
        $allYears[] = substr($rowAll['tahun'], 0, 4);
    }
    if (count($allYears) > 0) {
        $tahunMinimal = min($allYears);
        $tahunMaksimal = max($allYears);
        $judulRentangTahun = "$tahunMinimal-$tahunMaksimal";
    } else {
        $judulRentangTahun = '';
    }

    $sqlCount = "SELECT COUNT(*) as total FROM v_peta_jalan";
    $resultCount = q($sqlCount);
    $totalCount = (int)pg_fetch_result($resultCount, 0, 0);
} catch (Exception $e) {
    error_log($e->getMessage());
    $judulRentangTahun = '';
    $totalCount = 0;
}

$totalPages = ceil($totalCount / $perPage);

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Jalan - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/peta_jalan.css?v=<?= time() ?>">
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

        <!-- Peta Jalan Section -->
        <section class="section peta-jalan-section">
            <div class="container">
                <h2 class="section-title">
                    Peta Jalan<?= $judulRentangTahun ? ' <span class="tahun-range">' . htmlspecialchars($judulRentangTahun) . '</span>' : '' ?>
                </h2>

                <div class="timeline">
                    <?php if (!empty($petaJalanData)): ?>
                        <?php foreach ($petaJalanData as $index => $item): ?>
                            <div class="timeline-item <?= $index % 2 === 0 ? 'timeline-left' : 'timeline-right' ?>">
                                <div class="timeline-card">
                                    <h3 class="timeline-year"><?= htmlspecialchars(substr($item['tahun'], 0, 4)) ?></h3>
                                    <p class="timeline-title"><strong><?= htmlspecialchars($item['judul']) ?></strong></p>
                                    <?php if (!empty($item['file_path'])): ?>
                                        <a href="admin/<?= htmlspecialchars($item['file_path']) ?>" target="_blank" class="btn-file">
                                            Lihat File
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Belum ada data peta jalan.</p>
                        </div>
                    <?php endif; ?>
                </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pagination
        function changePage(page) {
            window.location.href = `peta_jalan.php?page=${page}`;
        }
    </script>
</body>

</html>