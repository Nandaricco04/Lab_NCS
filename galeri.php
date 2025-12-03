<?php
require_once 'admin/koneksi.php';

// Fetch home data for background image
try {
    $sql = "SELECT * FROM v_title_pages WHERE id_title = 5 LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}

// Ambil parameter filter
$year = isset($_GET['year']) ? $_GET['year'] : 'all';
$type = isset($_GET['type']) ? $_GET['type'] : 'agenda';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6;
$offset = ($page - 1) * $perPage;

// Ambil data galeri berdasarkan tipe
try {
    // Bangun klausa WHERE
    $whereConditions = [];
    $params = [];
    $paramCount = 1;

    // Filter berdasarkan kategori
    if ($type === 'agenda') {
        $whereConditions[] = "kategori = 'Agenda'";
    } else {
        $whereConditions[] = "kategori = 'Kegiatan'";
    }

    // Filter berdasarkan tahun jika ditentukan
    if ($year !== 'all') {
        $whereConditions[] = "EXTRACT(YEAR FROM tanggal_mulai) = $" . $paramCount;
        $params[] = $year;
        $paramCount++;
    }

    $whereClause = "WHERE " . implode(" AND ", $whereConditions);

    // Query utama dengan filter
    $sql = "SELECT * FROM v_agenda_kegiatan $whereClause ORDER BY tanggal_mulai DESC LIMIT $" . $paramCount . " OFFSET $" . ($paramCount + 1);
    $params[] = $perPage;
    $params[] = $offset;
    $result = qparams($sql, $params);
    $galeriItems = pg_fetch_all($result);

    // Hitung total data dengan filter yang sama
    $countSql = "SELECT COUNT(*) as total FROM v_agenda_kegiatan $whereClause";
    if ($year !== 'all') {
        $countResult = qparams($countSql, [$year]);
    } else {
        $countResult = q($countSql);
    }
    $totalCount = pg_fetch_result($countResult, 0, 0);
} catch (Exception $e) {
    $galeriItems = [];
    $totalCount = 0;
    error_log($e->getMessage());
}

$totalPages = ceil($totalCount / $perPage);

// Ambil tahun yang tersedia
try {
    $yearSql = "SELECT DISTINCT EXTRACT(YEAR FROM tanggal_mulai) as year FROM v_agenda_kegiatan ORDER BY year DESC";
    $yearResult = q($yearSql);
    $years = pg_fetch_all($yearResult);
} catch (Exception $e) {
    $years = [];
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/galeri.css?v=<?= time() ?>">
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

        <!-- Gallery Section -->
        <section class="section gallery-section">
            <div class="container">
                <!-- Filter Section -->
                <div class="filter-section">
                    <div class="year-filter">
                        <select id="yearSelect" class="form-select">
                            <option value="all">Years</option>
                            <?php if (!empty($years)): ?>
                                <?php foreach ($years as $y): ?>
                                    <option value="<?= $y['year'] ?>" <?= $year == $y['year'] ? 'selected' : '' ?>>
                                        <?= $y['year'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="type-tabs">
                        <button class="tab-btn <?= $type === 'agenda' ? 'active' : '' ?>" data-type="agenda">
                            Agenda
                        </button>
                        <button class="tab-btn <?= $type === 'kegiatan' ? 'active' : '' ?>" data-type="kegiatan">
                            Kegiatan
                        </button>
                    </div>
                </div>

                <!-- Gallery Grid -->
                <div class="gallery-grid">
                    <?php if (!empty($galeriItems)): ?>
                        <?php foreach ($galeriItems as $item): ?>
                            <div class="gallery-card">
                                <div class="gallery-image">
                                    <img src="admin/<?= htmlspecialchars($item['foto']) ?>"
                                         alt="<?= htmlspecialchars($item['nama_kegiatan'] ?? 'Gallery Image') ?>">
                                </div>
                                <div class="gallery-caption">
                                    <p><?= htmlspecialchars($item['keterangan'] ?? 'Tidak ada keterangan') ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Belum ada data galeri.</p>
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

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Year filter
        document.getElementById('yearSelect').addEventListener('change', function() {
            const year = this.value;
            const type = '<?= $type ?>';
            window.location.href = `galeri.php?year=${year}&type=${type}&page=1`;
        });

        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const type = this.dataset.type;
                const year = '<?= $year ?>';
                window.location.href = `galeri.php?year=${year}&type=${type}&page=1`;
            });
        });

        // Pagination
        function changePage(page) {
            const year = '<?= $year ?>';
            const type = '<?= $type ?>';
            window.location.href = `galeri.php?year=${year}&type=${type}&page=${page}`;
        }
    </script>
</body>
</html>
