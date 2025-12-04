<?php
require_once 'admin/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alat      = $_POST['id_sarana_prasarana'];
    $nama      = $_POST['nama_peminjam'];
    $nim       = $_POST['nim_peminjam'];
    $email     = $_POST['email_peminjam'];
    $wa        = $_POST['no_wa_peminjam'];
    $jumlah    = $_POST['jumlah_alat'];
    $tgl_pinjam  = $_POST['tanggal_pinjam'];
    $tgl_kembali = $_POST['tanggal_kembali'];

    try {
        $sql = "CALL sp_insert_peminjaman($1,$2,$3,$4,$5,$6,$7,$8,$9)";
        qparams($sql, [$alat, $nama, $nim, $email, $wa, $jumlah, $tgl_pinjam, $tgl_kembali, 'Proses']);

        echo "<script>alert('Peminjaman berhasil dikirim!'); window.location='sarana.php';</script>";

    } catch (Exception $e) {
        echo "<script>alert('Gagal mengirim peminjaman!');</script>";
        error_log($e->getMessage());
    }
}

// Ambil data title/hero
try {
    $sql = "SELECT * FROM v_title_pages WHERE id_title = 6 LIMIT 1";
    $result = q($sql);
    $homeData = pg_fetch_assoc($result);
} catch (Exception $e) {
    $homeData = null;
    error_log($e->getMessage());
}

$alatDipilih = isset($_GET['alat']) ? $_GET['alat'] : '';
$idSarana = isset($_GET['id']) ? $_GET['id'] : '';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Alat - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/peminjaman.css?v=<?= time() ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
        <!-- Hero Section -->
        <section class="hero-section" style="background-image: url('admin/<?= htmlspecialchars($homeData['gambar_path'] ?? 'images/hero-bg.jpg') ?>');">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Layanan</h1>
            </div>
        </section>

        <!-- Form Section -->
        <section class="section form-section">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10 col-xl-10">
                        <div class="form-card">
                            <h2 class="form-title">Peminjaman Alat</h2>

                            <form method="post" autocomplete="off">
                                <div class="form-group">
                                    <label for="alat" class="form-label">Alat</label>
                                    <div class="form-group">
                                        <input type="text"
                                            name="alat"
                                            id="alat"
                                            class="form-input"
                                            value="<?= htmlspecialchars($alatDipilih) ?>"
                                            readonly>
                                        <input type="hidden" name="id_sarana_prasarana" value="<?= htmlspecialchars($idSarana) ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="nama_peminjam" class="form-label">Nama</label>
                                    <input type="text"
                                           name="nama_peminjam"
                                           id="nama_peminjam"
                                           class="form-input"
                                           required
                                           placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="nim_peminjam" class="form-label">NIM</label>
                                    <input type="text"
                                           name="nim_peminjam"
                                           id="nim_peminjam"
                                           class="form-input"
                                           required
                                           placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="email_peminjam" class="form-label">Email</label>
                                    <input type="email"
                                           name="email_peminjam"
                                           id="email_peminjam"
                                           class="form-input"
                                           required
                                           placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="no_wa_peminjam" class="form-label">No Whatsapp</label>
                                    <input type="tel"
                                           name="no_wa_peminjam"
                                           id="no_wa_peminjam"
                                           class="form-input"
                                           required
                                           placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_alat" class="form-label">Jumlah Alat</label>
                                    <input type="number"
                                           name="jumlah_alat"
                                           id="jumlah_alat"
                                           class="form-input"
                                           min="1"
                                           required
                                           placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                                    <input type="date"
                                           name="tanggal_pinjam"
                                           id="tanggal_pinjam"
                                           class="form-input"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                                    <input type="date"
                                           name="tanggal_kembali"
                                           id="tanggal_kembali"
                                           class="form-input"
                                           required>
                                </div>

                                <div class="form-buttons">
                                    <button type="submit" class="btn btn-submit">Kirim</button>
                                    <a href="sarana.php" class="btn btn-back">Kembali</a>
                                </div>
                            </form>
                        </div>
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
