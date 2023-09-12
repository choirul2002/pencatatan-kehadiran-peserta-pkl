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

    date_default_timezone_set("Asia/Jakarta");
    date_default_timezone_get();

    switch ($pilihan) {
        case "viewLogposService":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");

                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_peserta, tabel_logservice, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                WHERE tabel_logservice.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_logservice.KD_PST
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS_PST = 'aktif'
                AND TGL_LOGSER = '$hari'
                ORDER BY TGL_LOGSER DESC, ID_LOGSER DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
                        $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
                        $day = substr($frm['TGL_LOGSER'],0,10);

                        $hari = substr($day, 8, 2);
                        $bulan = substr($day, 5, 2);
                        $tahun = substr($day, 2, 2);
                        $tanggal = $hari . '/' . $bulan . '/' . $tahun;

                        $frm['TGL_LOGSER'] = $tanggal;
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
        case "viewLogposServiceKode":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");
                $idlogser = $_POST['idlogservice'];

                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_peserta, tabel_logservice, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                WHERE tabel_logservice.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_logservice.KD_PST
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS_PST = 'aktif'
                AND TGL_LOGSER = '$hari'
                AND ID_LOGSER = '$idlogser'
                ORDER BY TGL_LOGSER DESC, ID_LOGSER DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
                        $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
                        $day = substr($frm['TGL_LOGSER'],0,10);

                        $hari = substr($day, 8, 2);
                        $bulan = substr($day, 5, 2);
                        $tahun = substr($day, 2, 2);
                        $tanggal = $hari . '/' . $bulan . '/' . $tahun;

                        $frm['TGL_LOGSER'] = $tanggal;
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
        case "jumlahViewLogposService":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");
                $sql = "SELECT COUNT(*) AS jumlah FROM tabel_peserta, tabel_logservice, tabel_dtl_tim_peserta, tabel_tim_peserta
                WHERE tabel_logservice.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_logservice.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS_PST = 'aktif'
                AND TGL_LOGSER = '$hari'
                ORDER BY TGL_LOGSER DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "jumlahViewLogposServiceKode":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");
                $idlogser = $_POST['idlogservice'];
                $sql = "SELECT COUNT(*) AS jumlah FROM tabel_peserta, tabel_logservice, tabel_dtl_tim_peserta, tabel_tim_peserta
                WHERE tabel_logservice.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_logservice.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS_PST = 'aktif'
                AND TGL_LOGSER = '$hari'
                AND ID_LOGSER = '$idlogser'
                ORDER BY TGL_LOGSER DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "service":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $tanggallokasi = date("Y-m-d");
            $hari = date("l");
            $jam = date("H:i:s");

            $sqlMhs = "SELECT*FROM tabel_peserta,tabel_akun,tabel_tim_peserta,tabel_dtl_tim_peserta 
            WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN 
            AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
            AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST 
            AND tabel_akun.KD_AKUN = '$akun'";
            $resultMhs = mysqli_query($conn, $sqlMhs);
            if(mysqli_num_rows($resultMhs) > 0){
                $data = mysqli_fetch_assoc($resultMhs);
                $kodeMahasiswa = $data['KD_PST'];
                if($tanggal < $data['TGL_MULAI_TIM']){
                    $respon['respon'] = "0";
                        
                    echo json_encode($respon);
                    exit(); 
                }else{
                    $sqlAkun = "SELECT*FROM tabel_peserta,tabel_akun,tabel_tim_peserta,tabel_dtl_tim_peserta 
                    WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN 
                    AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
                    AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST 
                    AND tabel_akun.KD_AKUN = '$akun'";
                    $resultAkun = mysqli_query($conn, $sqlAkun);
                    if(mysqli_num_rows($resultAkun) > 0){
                        $dataAkun = mysqli_fetch_assoc($resultAkun);
                        $kodeMahasiswa = $dataAkun['KD_PST'];
        
                        $sqlKonf = "SELECT*FROM tabel_konfigurasi";
                        $resultKonf = mysqli_query($conn, $sqlKonf);
                        if (mysqli_num_rows($resultKonf) > 0) {
                            $dataKonf = mysqli_fetch_assoc($resultKonf);
                        
                            if($tanggal <= $dataAkun['TGL_SELESAI_TIM']){
                                if ($hari == "Saturday" || $hari == "Sunday") {
                                    $respon['respon'] = "0";
                        
                                    echo json_encode($respon);
                                    exit(); 
                                } else if ($hari == "Friday") {
                                    $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                                    $resultlibur = mysqli_query($conn, $libur);
                                    if(mysqli_num_rows($resultlibur) > 0){
                                        $respon['respon'] = "0";
                        
                                        echo json_encode($respon);
                                        exit(); 
                                    }else{
                                        if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_SELESAI']) {
                                            date_default_timezone_set("Asia/Jakarta");
                                            date_default_timezone_get();
                                            $waktu = date('Y-m-d H:i:s');
                                            $jam = date('H:i:s');
                                            $keterangan = "Pada jam " . $jam . " peserta telah mematikan service lokasi/menghentikan aplikasi";
                            
                                            $sql = "INSERT INTO tabel_logservice(TGL_LOGSER, KD_PST, KETERANGAN_LOGSER) VALUES('$waktu','$kodeMahasiswa','$keterangan')";
                                
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                $sqlservice = "SELECT MAX(ID_LOGSER) AS ID, KD_PST FROM tabel_logservice WHERE KD_PST = '$kodeMahasiswa'";
                                                $resultService = mysqli_query($conn, $sqlservice);
                                                if(mysqli_num_rows($resultService) > 0){
                                                    $dataService = mysqli_fetch_assoc($resultService);

                                                    $respon['respon'] = $dataService['ID'];
                                
                                                    echo json_encode($respon);
                                                    exit();
                                                }
                                            }
                                        }else{
                                            $respon['respon'] = "0";
                            
                                            echo json_encode($respon);
                                            exit(); 
                                        }
                                    }
                                } else {
                                    $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                                    $resultlibur = mysqli_query($conn, $libur);
                                    if(mysqli_num_rows($resultlibur) > 0){
                                        $respon['respon'] = "0";
                        
                                        echo json_encode($respon);
                                        exit(); 
                                    }else{
                                        if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_SELESAI']) {
                                                                                        date_default_timezone_set("Asia/Jakarta");
                                            date_default_timezone_get();
                                            $waktu = date('Y-m-d H:i:s');
                                            $jam = date('H:i:s');
                                            $keterangan = "Pada jam " . $jam . " peserta telah mematikan service lokasi/menghentikan aplikasi";
                            
                                            $sql = "INSERT INTO tabel_logservice(TGL_LOGSER, KD_PST, KETERANGAN_LOGSER) VALUES('$waktu','$kodeMahasiswa','$keterangan')";
                                
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                $sqlservice = "SELECT MAX(ID_LOGSER) AS ID, KD_PST FROM tabel_logservice WHERE KD_PST = '$kodeMahasiswa'";
                                                $resultService = mysqli_query($conn, $sqlservice);
                                                if(mysqli_num_rows($resultService) > 0){
                                                    $dataService = mysqli_fetch_assoc($resultService);

                                                    $respon['respon'] = $dataService['ID'];
                                
                                                    echo json_encode($respon);
                                                    exit();
                                                }
                                            }
                                        }else{
                                            $respon['respon'] = "0";
                            
                                            echo json_encode($respon);
                                            exit(); 
                                        }
                                    }
                                }
                            }else{
                                $respon['respon'] = "0";
                        
                                echo json_encode($respon);
                                exit();
                            }
                        }
                    }
                }
            }
            
            break;
    }
}