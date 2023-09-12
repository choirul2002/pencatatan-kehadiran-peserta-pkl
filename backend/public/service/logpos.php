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
        case "viewLogpos":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");

                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_peserta, tabel_logpos, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                WHERE tabel_logpos.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_logpos.KD_PST
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS_PST = 'aktif'
                AND TGL_LOG = '$hari'
                ORDER BY TGL_LOG DESC, ID_LOG DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
                        $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
                        $day = substr($frm['TGL_LOG'],0,10);

                        $hari = substr($day, 8, 2);
                        $bulan = substr($day, 5, 2);
                        $tahun = substr($day, 2, 2);
                        $tanggal = $hari . '/' . $bulan . '/' . $tahun;

                        $frm['TGL_LOG'] = $tanggal;
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
        case "jumlahViewLogpos":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");
                $sql = "SELECT COUNT(*) AS jumlah FROM tabel_peserta, tabel_logpos, tabel_dtl_tim_peserta, tabel_tim_peserta
                WHERE tabel_logpos.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_logpos.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS_PST = 'aktif'
                AND TGL_LOG = '$hari'
                ORDER BY TGL_LOG DESC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "lokasi":
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
                                            $cekSql = "SELECT COUNT(*) AS jumlah FROM tabel_lokasi WHERE KD_PST = '$kodeMahasiswa'";
                                            $resultcek = mysqli_query($conn, $cekSql);
                                            if(mysqli_num_rows($resultcek) > 0){
                                                $datalokasi = mysqli_fetch_assoc($resultcek);
                                                $jumlah = $datalokasi['jumlah'];
                                                $latitude = $_POST['latitude'];
                                                $longitude = $_POST['longitude'];
                            
                                                if($jumlah > 0){
                                                    $sqllokasi = "UPDATE tabel_lokasi SET TANGGAL_LOK = '$tanggallokasi', LATITUDE_LOK = '$latitude', LONGITUDE_LOK = '$longitude' WHERE KD_PST = '$kodeMahasiswa'";
                                                    $resultlokasi = mysqli_query($conn, $sqllokasi);
                                                    if ($resultlokasi) {
                                                        $respon['respon'] = "1";
                                        
                                                        echo json_encode($respon);
                                                        exit();
                                                    }
                                                }else{
                                                    $sqllokasi = "INSERT INTO tabel_lokasi(KD_PST, TANGGAL_LOK, LATITUDE_LOK, LONGITUDE_LOK) VALUES('$kodeMahasiswa', '$tanggallokasi', '$latitude','$longitude')";
                                                    $resultlokasi = mysqli_query($conn, $sqllokasi);
                                                    if ($resultlokasi) {
                                                        $respon['respon'] = "1";
                                        
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
                                } else {
                                    $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                                    $resultlibur = mysqli_query($conn, $libur);
                                    if(mysqli_num_rows($resultlibur) > 0){
                                        $respon['respon'] = "0";
                        
                                        echo json_encode($respon);
                                        exit(); 
                                    }else{
                                        if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_SELESAI']) {
                                            $cekSql = "SELECT COUNT(*) AS jumlah FROM tabel_lokasi WHERE KD_PST = '$kodeMahasiswa'";
                                            $resultcek = mysqli_query($conn, $cekSql);
                                            if(mysqli_num_rows($resultcek) > 0){
                                                $datalokasi = mysqli_fetch_assoc($resultcek);
                                                $jumlah = $datalokasi['jumlah'];
                                                $latitude = $_POST['latitude'];
                                                $longitude = $_POST['longitude'];
                            
                                                if($jumlah > 0){
                                                    $sqllokasi = "UPDATE tabel_lokasi SET TANGGAL_LOK = '$tanggallokasi', LATITUDE_LOK = '$latitude', LONGITUDE_LOK = '$longitude' WHERE KD_PST = '$kodeMahasiswa'";
                                                    $resultlokasi = mysqli_query($conn, $sqllokasi);
                                                    if ($resultlokasi) {
                                                        $respon['respon'] = "1";
                                        
                                                        echo json_encode($respon);
                                                        exit();
                                                    }
                                                }else{
                                                    $sqllokasi = "INSERT INTO tabel_lokasi(KD_PST, TANGGAL_LOK, LATITUDE_LOK, LONGITUDE_LOK) VALUES('$kodeMahasiswa', '$tanggallokasi', '$latitude','$longitude')";
                                                    $resultlokasi = mysqli_query($conn, $sqllokasi);
                                                    if ($resultlokasi) {
                                                        $respon['respon'] = "1";
                                        
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
        case "hapusceklokasi":
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
                                            $cekSql = "SELECT COUNT(*) AS jumlah FROM tabel_lokasi WHERE KD_PST = '$kodeMahasiswa'";
                                            $resultcek = mysqli_query($conn, $cekSql);
                                            if(mysqli_num_rows($resultcek) > 0){
                                                $datalokasi = mysqli_fetch_assoc($resultcek);
                                                $jumlah = $datalokasi['jumlah'];
                            
                                                if($jumlah > 0){
                                                    $sqllokasi = "DELETE FROM tabel_lokasi WHERE KD_PST = '$kodeMahasiswa'";
                                                    $resultlokasi = mysqli_query($conn, $sqllokasi);
                                                    if ($resultlokasi) {
                                                        $respon['respon'] = "1";
                                        
                                                        echo json_encode($respon);
                                                        exit();
                                                    }
                                                }else{
                                                    $respon['respon'] = "0";
                                    
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
                                            $cekSql = "SELECT COUNT(*) AS jumlah FROM tabel_lokasi WHERE KD_PST = '$kodeMahasiswa'";
                                            $resultcek = mysqli_query($conn, $cekSql);
                                            if(mysqli_num_rows($resultcek) > 0){
                                                $datalokasi = mysqli_fetch_assoc($resultcek);
                                                $jumlah = $datalokasi['jumlah'];
                            
                                                if($jumlah > 0){
                                                    $sqllokasi = "DELETE FROM tabel_lokasi WHERE KD_PST = '$kodeMahasiswa'";
                                                    $resultlokasi = mysqli_query($conn, $sqllokasi);
                                                    if ($resultlokasi) {
                                                        $respon['respon'] = "1";
                                        
                                                        echo json_encode($respon);
                                                        exit();
                                                    }
                                                }else{
                                                    $respon['respon'] = "0";
                                    
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
        case "lokasiPembimbing":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $hari = date("l");
            $jam = date("H:i:s");

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];

                $sqlKonf = "SELECT*FROM tabel_konfigurasi";
                $resultKonf = mysqli_query($conn, $sqlKonf);
                if (mysqli_num_rows($resultKonf) > 0) {
                    $dataKonf = mysqli_fetch_assoc($resultKonf);
                
                    if ($hari == "Saturday" || $hari == "Sunday") {
                        $data = array();

                        echo json_encode($data);
                        exit();
                    } else if ($hari == "Friday") {
                        $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                        $resultlibur = mysqli_query($conn, $libur);
                        if(mysqli_num_rows($resultlibur) > 0){
                            $data = array();

                            echo json_encode($data);
                            exit();
                        }else{
                            if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_OUT']) {
                                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_lokasi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                                WHERE tabel_lokasi.KD_PST = tabel_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND TGL_SELESAI_TIM >= '$tanggal'
                                AND TANGGAL_LOK = '$tanggal'
                                AND STATUS_PST = 'aktif'";
                    
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $data = array();
                    
                                    while ($frm = mysqli_fetch_assoc($result)) {
                                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
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
                            }else{
                                $data = array();

                                echo json_encode($data);
                                exit();
                            }
                        }
                    } else {
                        $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                        $resultlibur = mysqli_query($conn, $libur);
                        if(mysqli_num_rows($resultlibur) > 0){
                            $data = array();

                            echo json_encode($data);
                            exit();
                        }else{
                            if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_OUT']) {
                                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_lokasi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                                WHERE tabel_lokasi.KD_PST = tabel_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND TGL_SELESAI_TIM >= '$tanggal'
                                AND TANGGAL_LOK = '$tanggal'
                                AND STATUS_PST = 'aktif'";
                    
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $data = array();
                    
                                    while ($frm = mysqli_fetch_assoc($result)) {
                                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
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
                            }else{
                                $data = array();

                                echo json_encode($data);
                                exit();
                            }
                        }
                    }
                }
            }
            break;
        case "lokasiPembimbingPerson":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $hari = date("l");
            $jam = date("H:i:s");
            $searchpeserta = $_POST['person'];

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];

                $sqlKonf = "SELECT*FROM tabel_konfigurasi";
                $resultKonf = mysqli_query($conn, $sqlKonf);
                if (mysqli_num_rows($resultKonf) > 0) {
                    $dataKonf = mysqli_fetch_assoc($resultKonf);
                
                    if ($hari == "Saturday" || $hari == "Sunday") {
                        $data = array();

                        echo json_encode($data);
                        exit();
                    } else if ($hari == "Friday") {
                        $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                        $resultlibur = mysqli_query($conn, $libur);
                        if(mysqli_num_rows($resultlibur) > 0){
                            $data = array();

                            echo json_encode($data);
                            exit();
                        }else{
                            if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_OUT']) {
                                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_lokasi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                                WHERE tabel_lokasi.KD_PST = tabel_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND TGL_SELESAI_TIM >= '$tanggal'
                                AND TANGGAL_LOK = '$tanggal'
                                AND tabel_peserta.KD_PST = '$searchpeserta'
                                AND STATUS_PST = 'aktif'";
                    
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $data = array();
                    
                                    while ($frm = mysqli_fetch_assoc($result)) {
                                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
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
                            }else{
                                $data = array();

                                echo json_encode($data);
                                exit();
                            }
                        }
                    } else {
                        $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                        $resultlibur = mysqli_query($conn, $libur);
                        if(mysqli_num_rows($resultlibur) > 0){
                            $data = array();

                            echo json_encode($data);
                            exit();
                        }else{
                            if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_OUT']) {
                                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_lokasi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                                WHERE tabel_lokasi.KD_PST = tabel_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND TGL_SELESAI_TIM >= '$tanggal'
                                AND TANGGAL_LOK = '$tanggal'
                                AND tabel_peserta.KD_PST = '$searchpeserta'
                                AND STATUS_PST = 'aktif'";
                    
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $data = array();
                    
                                    while ($frm = mysqli_fetch_assoc($result)) {
                                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
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
                            }else{
                                $data = array();

                                echo json_encode($data);
                                exit();
                            }
                        }
                    }
                }
            }
            break;
        case "lokasiPembimbingAsal":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $hari = date("l");
            $jam = date("H:i:s");
            $searchasal = $_POST['asal'];

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];

                $sqlKonf = "SELECT*FROM tabel_konfigurasi";
                $resultKonf = mysqli_query($conn, $sqlKonf);
                if (mysqli_num_rows($resultKonf) > 0) {
                    $dataKonf = mysqli_fetch_assoc($resultKonf);
                
                    if ($hari == "Saturday" || $hari == "Sunday") {
                        $data = array();

                        echo json_encode($data);
                        exit();
                    } else if ($hari == "Friday") {
                        $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                        $resultlibur = mysqli_query($conn, $libur);
                        if(mysqli_num_rows($resultlibur) > 0){
                            $data = array();

                            echo json_encode($data);
                            exit();
                        }else{
                            if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_OUT']) {
                                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_lokasi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                                WHERE tabel_lokasi.KD_PST = tabel_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND TGL_SELESAI_TIM >= '$tanggal'
                                AND TANGGAL_LOK = '$tanggal'
                                AND tabel_peserta.KD_ASAL = '$searchasal'
                                AND STATUS_PST = 'aktif'";
                    
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $data = array();
                    
                                    while ($frm = mysqli_fetch_assoc($result)) {
                                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
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
                            }else{
                                $data = array();

                                echo json_encode($data);
                                exit();
                            }
                        }
                    } else {
                        $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                        $resultlibur = mysqli_query($conn, $libur);
                        if(mysqli_num_rows($resultlibur) > 0){
                            $data = array();

                            echo json_encode($data);
                            exit();
                        }else{
                            if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_OUT']) {
                                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_lokasi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                                WHERE tabel_lokasi.KD_PST = tabel_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND TGL_SELESAI_TIM >= '$tanggal'
                                AND TANGGAL_LOK = '$tanggal'
                                AND tabel_peserta.KD_ASAL = '$searchasal'
                                AND STATUS_PST = 'aktif'";
                    
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $data = array();
                    
                                    while ($frm = mysqli_fetch_assoc($result)) {
                                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
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
                            }else{
                                $data = array();

                                echo json_encode($data);
                                exit();
                            }
                        }
                    }
                }
            }
            break;
        case "lokasiPembimbingTim":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $hari = date("l");
            $jam = date("H:i:s");
            $searchtim = $_POST['tim'];

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];

                $sqlKonf = "SELECT*FROM tabel_konfigurasi";
                $resultKonf = mysqli_query($conn, $sqlKonf);
                if (mysqli_num_rows($resultKonf) > 0) {
                    $dataKonf = mysqli_fetch_assoc($resultKonf);
                
                    if ($hari == "Saturday" || $hari == "Sunday") {
                        $data = array();

                        echo json_encode($data);
                        exit();
                    } else if ($hari == "Friday") {
                        $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                        $resultlibur = mysqli_query($conn, $libur);
                        if(mysqli_num_rows($resultlibur) > 0){
                            $data = array();

                            echo json_encode($data);
                            exit();
                        }else{
                            if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_OUT']) {
                                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_lokasi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                                WHERE tabel_lokasi.KD_PST = tabel_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND TGL_SELESAI_TIM >= '$tanggal'
                                AND TANGGAL_LOK = '$tanggal'
                                                                AND tabel_tim_peserta.KD_TIM = '$searchtim'
                                AND STATUS_PST = 'aktif'";
                    
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $data = array();
                    
                                    while ($frm = mysqli_fetch_assoc($result)) {
                                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
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
                            }else{
                                $data = array();

                                echo json_encode($data);
                                exit();
                            }
                        }
                    } else {
                        $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                        $resultlibur = mysqli_query($conn, $libur);
                        if(mysqli_num_rows($resultlibur) > 0){
                            $data = array();

                            echo json_encode($data);
                            exit();
                        }else{
                            if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_OUT']) {
                                $sql = "SELECT*, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_lokasi, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                                WHERE tabel_lokasi.KD_PST = tabel_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND TGL_SELESAI_TIM >= '$tanggal'
                                AND TANGGAL_LOK = '$tanggal'
                                AND tabel_tim_peserta.KD_TIM = '$searchtim'
                                AND STATUS_PST = 'aktif'";
                    
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $data = array();
                    
                                    while ($frm = mysqli_fetch_assoc($result)) {
                                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
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
                            }else{
                                $data = array();

                                echo json_encode($data);
                                exit();
                            }
                        }
                    }
                }
            }
            break;
        case "hapuslokasi":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $tanggallokasi = date("Y-m-d");
            $jam = date("H:i:s");
            
            $sqlAkun = "SELECT*FROM tabel_peserta,tabel_akun,tabel_tim_peserta,tabel_dtl_tim_peserta 
            WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN 
            AND tabel_tim_peserta.kd_tim = tabel_dtl_tim_peserta.kd_tim
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

                        $ceklokasi = "SELECT*FROM tabel_lokasi WHERE KD_PST = '$kodeMahasiswa'";
                        $resultlokasi = mysqli_query($conn, $ceklokasi);
                        if(mysqli_num_rows($resultlokasi) > 0){
                            $hapuslokasi = "DELETE FROM tabel_lokasi WHERE KD_PST = '$kodeMahasiswa'";
                            $resulthapus = mysqli_query($conn, $hapuslokasi);
                            if($resulthapus){
                                $data['respon'] = "1";
    
                                echo json_encode($data);
                                exit();
                            }
                        }else{
                            $data['respon'] = "0";

                            echo json_encode($data);
                            exit();
                        }
                    }else{
                        $respon['respon'] = "0";
                
                        echo json_encode($respon);
                        exit();
                    }
                }
            }
            break;
    }
}