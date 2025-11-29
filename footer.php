<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Lab NCS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
        }

        footer {
            background: linear-gradient(135deg, #1e5f8c 0%, #2980b9 100%);
            color: white;
            padding: 20px 0 20px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 0px;
            padding-bottom: 20px;
        }

        .footer-logo {
            width: 300px;
            height: auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 20px;
        }

        .footer-section h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0px;
        }

        .footer-section ul li a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-section ul li a:hover {
            transform: translateX(5px);
            color: #ffd700;
        }

        .footer-section p {
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            margin-top: 20px;
        }

        .footer-bottom p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <footer>
        <div class="footer-container">
            <div class="footer-header">
                <img src="images/logo.png" alt="Logo Lab NCS - Jurusan Teknologi Informasi Politeknik Negeri Malang" class="footer-logo">
            </div>

            <div class="footer-content">
                <div class="footer-section">
                    <h3>Tentang Lab NCS</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#visi-misi">Visi dan Misi</a></li>
                        <li><a href="#struktur">Struktur Organisasi</a></li>
                        <li><a href="#galeri">Galeri</a></li>
                        <li><a href="#produk">Produk Layanan</a></li>
                        <li><a href="#arsip">Arsip</a></li>
                        <li><a href="#peta">Peta Jalan</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Website Polinema Lainnya</h3>
                    <ul>
                        <li><a href="https://polinema.ac.id" target="_blank">Polinema.ac.id</a></li>
                    </ul>
                    
                    <h3 style="margin-top: 30px;">Website Lainnya</h3>
                    <ul>
                        <li><a href="https://sinta.kemdikbud.go.id" target="_blank">SINTA</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Pembuat</h3>
                    <ul>
                        <li>Nadia Minatul Salma</li>
                        <li>Nanda Ricco Satria Indrawan</li>
                        <li>Rafif Farrelsyah Fawwazka</li>
                        <li>Ratih Purnama Dewi</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>