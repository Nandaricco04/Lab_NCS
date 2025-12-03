<?php
require_once 'admin/koneksi.php';

// Proses simpan konsultasi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama_konsultasi'];
    $nim    = $_POST['nim_konsultasi'];
    $isi    = $_POST['isi_konsultasi'];
    $tgl    = $_POST['tanggal_konsultasi'];
    $email  = $_POST['email_konsultasi'];
    $wa     = $_POST['no_wa_konsultasi'];

    try {
        // Call Stored Procedure
        $sql = "CALL sp_insert_konsultasi($1,$2,$3,$4,$5,$6,$7)";
        qparams($sql, [$nama, $nim, $isi, $tgl, $email, $wa, 'proses']);

        echo "<script>alert('Konsultasi berhasil dikirim!'); window.location='konsultasi.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Gagal mengirim konsultasi!');</script>";
        error_log($e->getMessage());
    }
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
    <title>Konsultasi - Lab NCS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/variables.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/global.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/konsultasi.css?v=<?= time() ?>">
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
                            <h2 class="form-title">Konsultasi</h2>

                            <form method="post" autocomplete="off">
                                <div class="form-group">
                                    <label for="nama_konsultasi" class="form-label">Nama</label>
                                    <input type="text"
                                        name="nama_konsultasi"
                                        id="nama_konsultasi"
                                        class="form-input"
                                        required
                                        placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="nim_konsultasi" class="form-label">NIM</label>
                                    <input type="text"
                                        name="nim_konsultasi"
                                        id="nim_konsultasi"
                                        class="form-input"
                                        required
                                        placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="isi_konsultasi" class="form-label">Isi Konsultasi</label>
                                    <textarea name="isi_konsultasi"
                                        id="isi_konsultasi"
                                        class="form-input"
                                        rows="5"
                                        required
                                        placeholder=""></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_konsultasi" class="form-label">Tanggal Konsultasi</label>
                                    <input type="date"
                                        name="tanggal_konsultasi"
                                        id="tanggal_konsultasi"
                                        class="form-input"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label for="email_konsultasi" class="form-label">Email</label>
                                    <input type="email"
                                        name="email_konsultasi"
                                        id="email_konsultasi"
                                        class="form-input"
                                        required
                                        placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="no_wa_konsultasi" class="form-label">No Whatsapp</label>
                                    <input type="tel"
                                        name="no_wa_konsultasi"
                                        id="no_wa_konsultasi"
                                        class="form-input"
                                        required
                                        placeholder="">
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