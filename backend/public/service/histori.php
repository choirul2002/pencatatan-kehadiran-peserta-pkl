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
        case "view":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "SELECT DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, STATUS, KEHADIRAN, CHECK_IN, CHECK_OUT, KETERANGAN, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS url, SURAT AS srt, LOKASI_KIRIM_SURAT, LOKASI_CHECK_IN, LOKASI_CHECK_OUT, KEGIATAN FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND STATUS_SURAT != 'disapprove' AND STATUS_SURAT != 'waiting' ORDER BY TGL DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['KEGIATAN'] = ucfirst($frm['KEGIATAN']);
                        $frm['KETERANGAN'] = ucfirst($frm['KETERANGAN']);
                        array_push($data, $frm);
                    }
    
                    echo json_encode($data);    //data full
                    exit();
                } else {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        array_push($data, $frm);
                    }
    
                    echo json_encode($data);    //data full
                    exit();
                }
            }
            break;
        case "jumlahHistori":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "SELECT COUNT(*) AS jumlah FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND STATUS_SURAT != 'disapprove' AND STATUS_SURAT != 'waiting' ORDER BY TGL DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);    //data full
                    exit();
                }
            }
            break;
    }
}
