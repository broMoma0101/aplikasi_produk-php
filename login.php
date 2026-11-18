<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if($_SERVER['REQUEST_METHOD']=='OPTIONS'){
    http_response_code(200);
    exit;
}
$koneksi = new mysqli('localhost', 'root', '', 'api_moses'); 
if($koneksi->connect_error){
    echo json_encode([
      "status"=>false,
      "pesan" =>"koneksi databases gagal"

    ]);
    exit;
}
$username=$_POST['username']??'';
$password=$_POST['password']??'';

$stm=$koneksi->prepare(
    "select * from tb_user where username=?"
);
$stm->bind_param("s",$username);
$stm->execute();

$hasil=$stm->get_result();

if($user=$hasil->fetch_assoc()){
    if(md5($password)==$user['password']) {
        echo json_encode([
            "status"=> true,
            "pesan"=> "login berhasil",
            "data"=>[
                "id"=>$user['id'],
                "nama"=>$user['nama'],
                "username"=>$user['username']
            ]
        ]

        );
         }else{
    echo json_encode([
        "status"=>false,
        "pesan"=>"password salah"
    ]);
    }
     }else{
    echo json_encode([
        "status"=>false,
        "pesan"=>"username tidak ditemukan"
    ]);
    
    
}
?>
