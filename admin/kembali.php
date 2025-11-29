require 'koneksi.php';

$id = $_POST['id_sarana'];
$jml = $_POST['jumlah'];

tambahStok($id, $jml);

header("Location: sarana.php");
exit;
