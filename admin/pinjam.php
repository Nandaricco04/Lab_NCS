require 'koneksi.php';

$id = $_POST['id_sarana'];
$jml = $_POST['jumlah'];

$stokSekarang = getStok($id);

if ($jml > $stokSekarang) {
    echo "Stok tidak cukup!";
    exit;
}

kurangiStok($id, $jml);
header("Location: sarana.php");
exit;
