<?php

$DB_NAME = "simaptapkl";
$DB_USER = "root";
$DB_PASS = "";
$DB_SERVER_LOC = "localhost";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect($DB_SERVER_LOC, $DB_USER, $DB_PASS, $DB_NAME);

    $respon = array();
    $pilihan = $_POST['pilihan'];
    $akun = $_POST['id'];

    switch ($pilihan) {
        case "viewKampus":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT * FROM tabel_asal NATURAL JOIN tabel_tim_peserta WHERE KD_KAWAN = '$akunKaryawan' AND TGL_SELESAI_TIM >= '$tanggal' GROUP BY KD_ASAL ORDER BY NAMA_ASAL DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
                        $frm['ALAMAT_ASAL'] = ucfirst($frm['ALAMAT_ASAL']);
                        array_push($data, $frm);
                    }
    
                    echo json_encode($data);
                    exit();
                } else {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        array_push($data, $frm);
                    }
    
                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "jumlahViewKampus":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.kd_akun AND tabel_akun.KD_AKUN = '$akun'";
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT COUNT(*) AS jumlah FROM tabel_asal NATURAL JOIN tabel_tim_peserta WHERE KD_KAWAN = '$akunKaryawan' AND TGL_SELESAI_TIM >= '$tanggal' GROUP BY KD_ASAL ORDER BY NAMA_ASAL DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);
                    exit();
                }else{
                    $data['jumlah'] = "0";

                    echo json_encode($data);
                    exit();
                }
            }
            break;
    }
}
