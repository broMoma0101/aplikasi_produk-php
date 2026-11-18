
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$koneksi = new mysqli('localhost', 'root', '', 'api_moses');

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode(['pesan' => 'Koneksi database gagal']);
    exit;
}

$id_produk = $_POST['id_produk'] ?? '';
$nama_produk = $_POST['nama_produk'] ?? '';
$harga_produk = $_POST['harga_produk'] ?? '';

if ($id_produk === '' || $nama_produk === '' || $harga_produk === '') {
    echo json_encode(['pesan' => 'Data tidak boleh kosong']);
    exit;
}

$stmt = $koneksi->prepare("
    UPDATE tb_produk
    SET nama_produk = ?, harga_produk = ?
    WHERE id = ?
");

$stmt->bind_param("sii", $nama_produk, $harga_produk, $id_produk);

if ($stmt->execute()) {
    if ($stmt->affected_rows >= 0) {
        echo json_encode(['pesan' => 'sukses']);
    }
} else {
    http_response_code(500);
    echo json_encode([
        'pesan' => 'gagal',
        'error' => $stmt->error
    ]);
}

$stmt->close();
$koneksi->close();
?>
