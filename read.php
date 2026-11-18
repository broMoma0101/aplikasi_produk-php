
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

$query = mysqli_query($koneksi, "
    SELECT 
        id AS id_produk,
        nama_produk,
        harga_produk
    FROM tb_produk
");

if ($query) {
    $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
    echo json_encode($data);
} else {
    http_response_code(500);
    echo json_encode(['pesan' => 'Gagal mengambil data']);
}

$koneksi->close();
?>
