
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
    echo json_encode([
        'pesan' => 'Gagal koneksi database',
        'error' => $koneksi->connect_error
    ]);
    exit;
}

$id_produk = $_POST['id_produk'] ?? '';

if ($id_produk == '') {
    echo json_encode([
        'pesan' => 'ID produk tidak ditemukan'
    ]);
    exit;
}

$stmt = $koneksi->prepare(
    "DELETE FROM tb_produk WHERE id = ?"
);

$stmt->bind_param("i", $id_produk);

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        echo json_encode([
            'status' => true,
            'pesan' => 'Produk berhasil dihapus'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'pesan' => 'Produk tidak ditemukan'
        ]);
    }

} else {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'pesan' => 'Gagal menghapus produk'
    ]);
}

$stmt->close();
$koneksi->close();
?>
