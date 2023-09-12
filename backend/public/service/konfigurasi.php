<?php
$DB_NAME = "simaptapkl";
$DB_USER = "root";
$DB_PASS = "";
$DB_SERVER_LOC = "localhost";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect($DB_SERVER_LOC, $DB_USER, $DB_PASS, $DB_NAME);

    $respon = array();
    $pilihan = $_POST['pilihan'];

    switch ($pilihan) {
        case "lokasi":
            $sql = "SELECT LATITUDE_KONF, LONGITUDE_KONF, RADIUS_KONF, JUDUL_RADIUS FROM tabel_konfigurasi";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                $data['JUDUL_RADIUS'] = ucwords($data['JUDUL_RADIUS']);

                echo json_encode($data);
                exit();
            }
            break;
    }
}
