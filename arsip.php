<?php
require_once 'admin/koneksi.php';

// Ambil data title/hero
try {
    $sql = "SELECT * FROM v_title_pages WHERE id_title = 7 LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// Search and filter
$search = $_GET['search'] ?? '';
$year = $_GET['year'] ?? 'all';
$type = $_GET['type'] ?? 'penelitian';

// Determine table based on type
$table = $type === 'pengabdian' ? 'pengabdian' : 'penelitian';

// Get total count
$countSql = "SELECT COUNT(*) as total FROM $table WHERE 1=1";
if ($search) {
    $countSql .= " AND judul ILIKE '%$search%'";
}
if ($year !== 'all') {
    $countSql .= " AND tahun = '$year'";
}
$countResult = q($countSql);
$totalRows = pg_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $perPage);

// Get data
$sql = "SELECT * FROM $table WHERE 1=1";
if ($search) {
    $sql .= " AND judul ILIKE '%$search%'";
}
if ($year !== 'all') {
    $sql .= " AND tahun = '$year'";
}
$sql .= " ORDER BY tahun ASC LIMIT $perPage OFFSET $offset";
$result = q($sql);
$documents = pg_fetch_all($result) ?: [];

// Get available years
$yearsSql = "SELECT DISTINCT tahun as year FROM $table ORDER BY year DESC";
$yearsResult = q($yearsSql);
$years = pg_fetch_all($yearsResult) ?: [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/arsip.css?v=<?= time() ?>">
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

        <!-- Archive Section -->
        <section class="section archive-section">
            <div class="container">
                <div class="archive-header">
                    <h2 class="archive-title">Arsip Dokumen & Publikasi JTI Polinema</h2>
                    <p class="archive-subtitle">Kumpulan hasil penelitian, laporan kegiatan, dan dokumen akademik</p>
                </div>

                <!-- Search and Filter -->
                <div class="search-filter-container">
                    <form method="get" class="search-filter-form">
                        <div class="search-box">
                            <input type="text"
                                   name="search"
                                   class="search-input"
                                   placeholder="Cari publikasi..."
                                   value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="filter-box">
                            <select name="year" class="filter-select">
                                <option value="all" <?= $year === 'all' ? 'selected' : '' ?>>Semua Tahun</option>
                                <?php foreach ($years as $y): ?>
                                    <option value="<?= $y['year'] ?>" <?= $year == $y['year'] ? 'selected' : '' ?>>
                                        <?= $y['year'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn-search">Cari</button>
                    </form>

                    <!-- Type Tabs -->
                    <div class="type-tabs">
                        <a href="?type=penelitian&year=<?= $year ?>&search=<?= $search ?>"
                           class="tab-btn <?= $type === 'penelitian' ? 'active' : '' ?>">
                            Penelitian
                        </a>
                        <a href="?type=pengabdian&year=<?= $year ?>&search=<?= $search ?>"
                           class="tab-btn <?= $type === 'pengabdian' ? 'active' : '' ?>">
                            Pengabdian
                        </a>
                    </div>
                </div>

                <!-- Documents List -->
                <?php if ($type === 'pengabdian'): ?>
                    <!-- Table View for Pengabdian -->
                    <div class="table-container">
                        <?php if (empty($documents)): ?>
                            <div class="no-results">
                                <p>Tidak ada dokumen ditemukan.</p>
                            </div>
                        <?php else: ?>
                            <table class="documents-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Tahun</th>
                                        <th class="text-center">Rincian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = $offset + 1;
                                    foreach ($documents as $doc):
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($doc['tahun']) ?></td>
                                            <td class="text-center">
                                                <a href="detail_pengabdian.php?id=<?= $doc['id_pengabdian'] ?>"
                                                   class="btn-table-detail">Lihat</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <!-- Card View for Penelitian -->
                    <div class="documents-list">
                        <?php if (empty($documents)): ?>
                            <div class="no-results">
                                <p>Tidak ada dokumen ditemukan.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($documents as $doc): ?>
                                <div class="document-card">
                                    <h3 class="document-title"><?= htmlspecialchars($doc['judul']) ?></h3>
                                    <p class="document-date">Tahun: <?= htmlspecialchars($doc['tahun']) ?></p>
                                    <a href="admin/<?= htmlspecialchars($doc['file_path']) ?>"
                                       target="_blank"
                                       class="btn-detail">Lihat Detail</a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination-container">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>&type=<?= $type ?>&year=<?= $year ?>&search=<?= $search ?>"
                               class="btn-pagination">
                                ◄ Previous
                            </a>
                        <?php endif; ?>

                        <span class="pagination-info">
                            <?= $page ?> of <?= $totalPages ?>
                        </span>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1 ?>&type=<?= $type ?>&year=<?= $year ?>&search=<?= $search ?>"
                               class="btn-pagination">
                                Next ►
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
