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
        case "jamcekin":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $hari = date("l");
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            $sqlKonf = "SELECT*FROM tabel_konfigurasi";

            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                $masaPKL = "SELECT*FROM tabel_peserta,tabel_akun,tabel_tim_peserta,tabel_dtl_tim_peserta 
                WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN 
                AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST 
                AND tabel_akun.KD_AKUN = '$akun'";
                $resultmasapkl = mysqli_query($conn, $masaPKL);
                if (mysqli_num_rows($resultmasapkl) > 0) {
                    $datamasapkl = mysqli_fetch_assoc($resultmasapkl);
                    if($tanggal < $datamasapkl['TGL_MULAI_TIM']){
                        $respon['respon'] = "13";
            
                        echo json_encode($respon);
                        exit();
                    }else{
                    if($tanggal <= $datamasapkl['TGL_SELESAI_TIM']){
                        if ($hari == "Saturday" || $hari == "Sunday") {
                            $respon['respon'] = "10";
            
                            echo json_encode($respon);
                            exit();
                        } else if ($hari == "Friday") {
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $respon['respon'] = "12";
            
                                echo json_encode($respon);
                                exit();
                            }else{
                                $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
                                $resultAkun = mysqli_query($conn, $sqlAkun);
                                if(mysqli_num_rows($resultAkun) > 0){
                                    $dataAkun = mysqli_fetch_assoc($resultAkun);
                                    $kodeMahasiswa = $dataAkun['KD_PST'];
            
                                    $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
                
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        $data = mysqli_fetch_assoc($result);
                                        if ($data['STATUS'] == "hadir") {
                                            $respon['respon'] = "4";
                
                                            echo json_encode($respon);
                                            exit();
                                        } else {
                                            if ($data['STATUS_SURAT'] == "disapprove") {
                                                $respon['respon'] = "6";
                
                                                echo json_encode($respon);
                                                exit();
                                            } else {
                                                $respon['respon'] = "5";
                
                                                echo json_encode($respon);
                                                exit();
                                            }
                                        }
                                    } else {
                                        if ($jam < $dataKonf['PRE_JUM_MULAI']) {
                                            $respon['respon'] = "2";
                        
                                            echo json_encode($respon);
                                            exit();
                                        } else if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_SELESAI']) {
                                            $respon['respon'] = "1";
                            
                                            echo json_encode($respon);
                                            exit();
                                        } else {
                                            $respon['respon'] = "3";
                        
                                            echo json_encode($respon);
                                            exit();
                                        }
                                    }
                                }
                            }
                        } else {
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $respon['respon'] = "12";
            
                                echo json_encode($respon);
                                exit();
                            }else{
                                $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
                                $resultAkun = mysqli_query($conn, $sqlAkun);
                                if(mysqli_num_rows($resultAkun) > 0){
                                    $dataAkun = mysqli_fetch_assoc($resultAkun);
                                    $kodeMahasiswa = $dataAkun['KD_PST'];
            
                                    $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
                
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        $data = mysqli_fetch_assoc($result);
                                        if ($data['STATUS'] == "hadir") {
                                            $respon['respon'] = "4";
                
                                            echo json_encode($respon);
                                            exit();
                                        } else {
                                            if ($data['STATUS_SURAT'] == "disapprove") {
                                                $respon['respon'] = "6";
                
                                                echo json_encode($respon);
                                                exit();
                                            } else {
                                                $respon['respon'] = "5";
                
                                                echo json_encode($respon);
                                                exit();
                                            }
                                        }
                                    } else {
                                        if ($jam < $dataKonf['PRE_SEKAM_MULAI']) {
                                            $respon['respon'] = "2";
                        
                                            echo json_encode($respon);
                                            exit();
                                        } else if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_SELESAI']) {
                                            $respon['respon'] = "1";
                            
                                            echo json_encode($respon);
                                            exit();
                                        } else {
                                            $respon['respon'] = "3";
                        
                                            echo json_encode($respon);
                                            exit();
                                        } 
                                    }
                                }
                            }
                        }
                    }else{
                        $respon['respon'] = "11";
            
                        echo json_encode($respon);
                        exit();
                    }
                    }

                }

            }

            break;
        case "cekin":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");
            $kehadiran = "";
            $jalan = substr($_POST['jalan'],6);
            $desa = $_POST['desa'];
            $kecamatan = substr($_POST['kecamatan'],10);
            $kabupaten = substr($_POST['kabupaten'],10);
            $provinsi = $_POST['provinsi'];
            $kodepos = $_POST['kodepos'];
            $lokasi = "Jln. ".$jalan.", Ds. ".$desa.", Kec. ".$kecamatan.", Kab. ".$kabupaten.", Prov. ".$provinsi.", ".$kodepos;

            $hari = date("l");

            $sqlKonf = "SELECT*FROM tabel_konfigurasi";
            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                $sekamMulai =  date("H:i:s", strtotime($dataKonf['PRE_SEKAM_MULAI']) + (15 * 60));
                $sekamSlesai =  date("H:i:s", strtotime($sekamMulai) + (30 * 60));
                $jumMulai =  date("H:i:s", strtotime($dataKonf['PRE_JUM_MULAI']) + (15 * 60));
                $jumSlesai =  date("H:i:s", strtotime($sekamMulai) + (30 * 60));


                if ($hari == "Friday") {
                    if ($jam < $jumMulai) {
                        $kehadiran = "tepat waktu";
                    } else if ($jam > $jumMulai && $jam < $jumSlesai) {
                        $kehadiran = "telat";
                    } else {
                        $kehadiran = "terlambat";
                    }
                } else {
                    if ($jam < $sekamMulai) {
                        $kehadiran = "tepat waktu";
                    } else if ($jam > $sekamMulai && $jam < $sekamSlesai) {
                        $kehadiran = "telat";
                    } else {
                         $kehadiran = "terlambat";
                    }
                }
            }

            if ($jam < "07:15:00") {
                $kehadiran = "tepat waktu";
            } else if ($jam > "07:15:00" && $jam < "07:30:00") {
                $kehadiran = "telat";
            } else {
                $kehadiran = "terlambat";
            }

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "INSERT INTO tabel_absensi(KD_PST, TGL, CHECK_IN, LOKASI_CHECK_IN, KEHADIRAN, STATUS_SURAT, STATUS) VALUES('$kodeMahasiswa','$tanggal','$jam', '$lokasi', '$kehadiran','-','hadir')";
    
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $respon['respon'] = "1";
    
                    echo json_encode($respon);
                    exit();
                }
            }

            break;
        case "jamcekout":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $hari = date("l");
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            $sqlKonf = "SELECT*FROM tabel_konfigurasi";

            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                $masaPKL = "SELECT*FROM tabel_peserta,tabel_akun,tabel_tim_peserta,tabel_dtl_tim_peserta 
                WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN 
                AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST 
                AND tabel_akun.KD_AKUN = '$akun'";
                $resultmasapkl = mysqli_query($conn, $masaPKL);
                if (mysqli_num_rows($resultmasapkl) > 0) {
                    $datamasapkl = mysqli_fetch_assoc($resultmasapkl);

                    if($tanggal < $datamasapkl['TGL_MULAI_TIM']){
                        $respon['respon'] = "13";
            
                        echo json_encode($respon);
                        exit();
                    }else{
                    if($tanggal <= $datamasapkl['TGL_SELESAI_TIM']){
                        if ($hari == "Saturday" || $hari == "Sunday") {
                            $respon['respon'] = "10";
            
                            echo json_encode($respon);
                            exit();
                        } else if ($hari == "Friday") {
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $respon['respon'] = "12";
            
                                echo json_encode($respon);
                                exit();
                            }else{
                                $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
                                $resultAkun = mysqli_query($conn, $sqlAkun);
                                if(mysqli_num_rows($resultAkun) > 0){
                                    $dataAkun = mysqli_fetch_assoc($resultAkun);
                                    $kodeMahasiswa = $dataAkun['KD_PST'];
            
                                    $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
                
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        $data = mysqli_fetch_assoc($result);
                                        if ($data['STATUS'] == "hadir") {
                                            if ($data['CHECK_OUT']) {
                                                if($jam > $dataKonf['PRE_JUM_OUT']){
                                                    $respon['respon'] = "3";
                                        
                                                    echo json_encode($respon);
                                                    exit();
                                                }else{
                                                    $respon['respon'] = "6";
                    
                                                    echo json_encode($respon);
                                                    exit();
                                                }
                                            } else {
                                                if ($jam < $dataKonf['PRE_JUM_SELESAI']) {
                                                    $respon['respon'] = "2";
                                
                                                    echo json_encode($respon);
                                                    exit();
                                                } else if ($jam > $dataKonf['PRE_JUM_SELESAI'] && $jam < $dataKonf['PRE_JUM_OUT']) {
                                                    $respon['respon'] = "1";
                                    
                                                    echo json_encode($respon);
                                                    exit();
                                                } else {
                                                    $respon['respon'] = "30";
                                
                                                    echo json_encode($respon);
                                                    exit();
                                                }
                                            }
                                        } else {
                                            if ($data['STATUS_SURAT'] == "disapprove") {
                                                $respon['respon'] = "7";
                
                                                echo json_encode($respon);
                                                exit();
                                            } else {
                                                $respon['respon'] = "5";
                
                                                echo json_encode($respon);
                                                exit();
                                            }
                                        }
                                    } else {
                                        $respon['respon'] = "4";
                
                                        echo json_encode($respon);
                                        exit();
                                    }
                                }
                            }
                        } else {
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $respon['respon'] = "12";
            
                                echo json_encode($respon);
                                exit();
                            }else{
                                $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
                                $resultAkun = mysqli_query($conn, $sqlAkun);
                                if(mysqli_num_rows($resultAkun) > 0){
                                    $dataAkun = mysqli_fetch_assoc($resultAkun);
                                    $kodeMahasiswa = $dataAkun['KD_PST'];
            
                                    $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
                
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        $data = mysqli_fetch_assoc($result);
                                        if ($data['STATUS'] == "hadir") {
                                            if ($data['CHECK_OUT']) {
                                                if($jam > $dataKonf['PRE_SEKAM_OUT']){
                                                    $respon['respon'] = "3";
                                        
                                                    echo json_encode($respon);
                                                    exit();
                                                }else{
                                                    $respon['respon'] = "6";
                    
                                                    echo json_encode($respon);
                                                    exit();
                                                }
                                            } else {
                                                if ($jam < $dataKonf['PRE_SEKAM_SELESAI']) {
                                                    $respon['respon'] = "2";
                                
                                                    echo json_encode($respon);
                                                    exit();
                                                } else if ($jam > $dataKonf['PRE_SEKAM_SELESAI'] && $jam < $dataKonf['PRE_SEKAM_OUT']) {                   
                                                    $respon['respon'] = "1";
                            
                                                    echo json_encode($respon);
                                                    exit();
                                                } else {
                                                    $respon['respon'] = "30";
                                
                                                    echo json_encode($respon);
                                                    exit();
                                                }
                                            }
                                        } else {
                                            if ($data['STATUS_SURAT'] == "disapprove") {
                                                $respon['respon'] = "7";
                
                                                echo json_encode($respon);
                                                exit();
                                            } else {
                                                $respon['respon'] = "5";
                
                                                echo json_encode($respon);
                                                exit();
                                            }
                                        }
                                    } else {
                                        $respon['respon'] = "4";
                
                                        echo json_encode($respon);
                                        exit();
                                    }
                                }
                            }
                        }
                    }else{
                        $respon['respon'] = "11";
            
                        echo json_encode($respon);
                        exit();
                    }
                    }
                }

            }
            break;
        case "cekout":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");
            $jalan = substr($_POST['jalan'],6);  
            $desa = $_POST['desa'];
            $kecamatan = substr($_POST['kecamatan'],10);
            $kabupaten = substr($_POST['kabupaten'],10);
            $provinsi = $_POST['provinsi'];
            $kodepos = $_POST['kodepos'];
            $kegiatan = $_POST['kegiatan'];
            $lokasi = "Jln. ".$jalan.", Ds. ".$desa.", Kec. ".$kecamatan.", Kab. ".$kabupaten.", Prov. ".$provinsi.", ".$kodepos;

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "UPDATE tabel_absensi SET CHECK_OUT = '$jam', LOKASI_CHECK_OUT = '$lokasi', KEGIATAN = '$kegiatan' WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
    
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $respon['respon'] = "1";
    
                    echo json_encode($respon);
                    exit();
                }
            }

            break;
        case "cekoutperbarui":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");
            $jalan = substr($_POST['jalan'],6);  
            $desa = $_POST['desa'];
            $kecamatan = substr($_POST['kecamatan'],10);
            $kabupaten = substr($_POST['kabupaten'],10);
            $provinsi = $_POST['provinsi'];
            $kodepos = $_POST['kodepos'];
            $kegiatan = $_POST['kegiatan'];
            $lokasi = "Jln. ".$jalan.", Ds. ".$desa.", Kec. ".$kecamatan.", Kab. ".$kabupaten.", Prov. ".$provinsi.", ".$kodepos;
            $mhs = $_POST['kode'];
            

            $sql = "UPDATE tabel_absensi SET CHECK_OUT = '$jam', LOKASI_CHECK_OUT = '$lokasi', KEGIATAN = '$kegiatan' WHERE KD_PST = '$mhs' AND TGL = '$tanggal'";
    
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $respon['respon'] = "1";

                echo json_encode($respon);
                exit();
            }

            break;
        case "jamcekoutperbarui":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");
            $hari = date("l");

            $sqlKonf = "SELECT*FROM tabel_konfigurasi";

            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                if ($hari == "Saturday" || $hari == "Sunday") {
                    $respon['respon'] = "10";
    
                    echo json_encode($respon);
                    exit();
                } else if ($hari == "Friday") {
                    if ($jam > $dataKonf['PRE_JUM_OUT']) {
                        $respon['respon'] = "1";
        
                        echo json_encode($respon);
                        exit();
                    } else {
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);
                        exit();
                    }
                } else {
                    if ($jam > $dataKonf['PRE_SEKAM_OUT']) {                   
                        $respon['respon'] = "1";
        
                        echo json_encode($respon);
                        exit();
                    } else {
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);
                        exit();
                    }
                }

            }
                break;
        case "izinout":
            $tgl = date("Y-m-d");
            
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tgl' AND STATUS_SURAT = 'disapprove'";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
                    $id = $data['ID'];
    
                    $sql1 = "DELETE FROM tabel_absensi WHERE id = '$id'";
                    $result1 = mysqli_query($conn, $sql1);
                    if ($result1) {
                        $sql2 = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tgl'";
                        $result2 = mysqli_query($conn, $sql2);
                        if (mysqli_num_rows($result2) > 0) {
                        } else {
                            $respon['respon'] = "0";
    
                            echo json_encode($respon);
                            exit();
                        }
                    }
                }
            }

            break;
        case "izinin":
            $tgl = date("Y-m-d");

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tgl' AND STATUS_SURAT = 'disapprove'";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
                    $id = $data['ID'];
    
                    $sql1 = "DELETE FROM tabel_absensi WHERE id = '$id'";
                    $result1 = mysqli_query($conn, $sql1);
                    if ($result1) {
                        $sql2 = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tgl'";
                        $result2 = mysqli_query($conn, $sql2);
                        if (mysqli_num_rows($result2) > 0) {
                        } else {
                            date_default_timezone_set("Asia/Jakarta");
                            date_default_timezone_get();
    
                            $tanggal = date("Y-m-d");
                            $jam = date("H:i:s");
                            $kehadiran = "";
    
                            $hari = date("l");

                            $sqlKonf = "SELECT*FROM tabel_konfigurasi";
                            $resultKonf = mysqli_query($conn, $sqlKonf);
                            if (mysqli_num_rows($resultKonf) > 0) {
                                $dataKonf = mysqli_fetch_assoc($resultKonf);
                
                                $sekamMulai =  date("H:i:s", strtotime($dataKonf['PRE_SEKAM_MULAI']) + (15 * 60));
                                $sekamSlesai =  date("H:i:s", strtotime($sekamMulai) + (30 * 60));
                                $jumMulai =  date("H:i:s", strtotime($dataKonf['PRE_JUM_MULAI']) + (15 * 60));
                                $jumSlesai =  date("H:i:s", strtotime($sekamMulai) + (30 * 60));
                
                
                                if ($hari == "Friday") {
                                    if ($jam < $jumMulai) {
                                        $kehadiran = "tepat waktu";
                                    } else if ($jam > $jumMulai && $jam < $jumSlesai) {
                                        $kehadiran = "telat";
                                    } else {
                                        $kehadiran = "terlambat";
                                    }
                                } else {
                                    if ($jam < $sekamMulai) {
                                        $kehadiran = "tepat waktu";
                                    } else if ($jam > $sekamMulai && $jam < $sekamSlesai) {
                                        $kehadiran = "telat";
                                    } else {
                                         $kehadiran = "terlambat";
                                    }
                                }
                            }

                            $jalan = substr($_POST['jalan'],6);  
                            $desa = $_POST['desa'];
                            $kecamatan = substr($_POST['kecamatan'],10);
                            $kabupaten = substr($_POST['kabupaten'],10);
                            $provinsi = $_POST['provinsi'];
                            $kodepos = $_POST['kodepos'];
                            $lokasi = "Jln. ".$jalan.", Ds. ".$desa.", Kec. ".$kecamatan.", Kab. ".$kabupaten.", Prov. ".$provinsi.", ".$kodepos;
    
                            $sql = "INSERT INTO tabel_absensi(KD_PST, TGL, CHECK_IN, LOKASI_CHECK_IN, KEHADIRAN, STATUS_SURAT, STATUS) VALUES('$kodeMahasiswa','$tanggal','$jam','$lokasi','$kehadiran','-','hadir')";
    
                            $result = mysqli_query($conn, $sql);
                            if ($result) {
                                $respon['respon'] = "1";
    
                                echo json_encode($respon);
                                exit();
                            }
                        }
                    }
                }
            }

            break;
        case "ceknotifikasicekin":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $hari = date("l");
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            $sqlKonf = "SELECT*FROM tabel_konfigurasi";

            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                $sqlMhs = "SELECT*FROM tabel_peserta,tabel_akun,tabel_tim_peserta,tabel_dtl_tim_peserta 
                WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN 
                AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST 
                AND tabel_akun.KD_AKUN = '$akun'";
                $resultMhs = mysqli_query($conn, $sqlMhs);
                if(mysqli_num_rows($resultMhs) > 0){
                    $data = mysqli_fetch_assoc($resultMhs);
                    $kodeMahasiswa = $data['KD_PST'];
                    if($data['STATUS_PST'] == "aktif"){
                        if($tanggal < $data['TGL_MULAI_TIM']){
                            $respon['respon'] = "0";
    
                            echo json_encode($respon);
                            exit();
                        }else{
                        if($tanggal <= $data['TGL_SELESAI_TIM']){
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
                                    if ($jam < $dataKonf['PRE_JUM_MULAI']) {
                                        $respon['respon'] = "0";
                    
                                        echo json_encode($respon);
                                        exit();
                                    } else if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_SELESAI']) {
                                        $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
                    
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $respon['respon'] = "0";
                    
                                            echo json_encode($respon);
                                            exit();
                                        } else {
                                            $respon['respon'] = "1";
                    
                                            echo json_encode($respon);
                                            exit();
                                        }
                                    } else {
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
                                    if ($jam < $dataKonf['PRE_SEKAM_MULAI']) {
                                        $respon['respon'] = "0";
                    
                                        echo json_encode($respon);
                                        exit();
                                    } else if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_SELESAI']) {
                                        $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
                    
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $respon['respon'] = "0";
                    
                                            echo json_encode($respon);
                                            exit();
                                        } else {
                                            $respon['respon'] = "1";
                    
                                            echo json_encode($respon);
                                            exit();
                                        }
                                    } else {
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
                    }else{
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);
                        exit();
                    }
                }else{
                    $respon['respon'] = "0";
    
                    echo json_encode($respon);
                    exit();
                }
            }

            break;
        case "ceknotifikasicekout":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $hari = date("l");
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            
            $sqlKonf = "SELECT*FROM tabel_konfigurasi";

            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                $sqlMhs = "SELECT*FROM tabel_peserta,tabel_akun,tabel_tim_peserta,tabel_dtl_tim_peserta 
                WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN 
                AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST 
                AND tabel_akun.KD_AKUN = '$akun'";
                $resultMhs = mysqli_query($conn, $sqlMhs);
                if(mysqli_num_rows($resultMhs) > 0){
                    $data = mysqli_fetch_assoc($resultMhs);
                    $kodeMahasiswa = $data['KD_PST'];
                    if($data['STATUS_PST'] == "aktif"){
                        if($tanggal < $data['TGL_MULAI_TIM']){
                            $respon['respon'] = "0";
                    
                            echo json_encode($respon);
                            exit();
                        }else{
                        if($tanggal <= $data['TGL_SELESAI_TIM']){
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
                                    if ($jam < $dataKonf['PRE_JUM_SELESAI']) {
                                        $respon['respon'] = "0";
                    
                                        echo json_encode($respon);
                                        exit();
                                    } else if ($jam > $dataKonf['PRE_JUM_SELESAI'] && $jam < $dataKonf['PRE_JUM_OUT']) {
                                        $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
                    
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $data = mysqli_fetch_assoc($result);
                                            if ($data['STATUS'] == "hadir") {
                                                if ($data['CHECK_OUT']) {
                                                    $respon['respon'] = "0";
                    
                                                    echo json_encode($respon);
                                                    exit();
                                                } else {
                                                    $respon['respon'] = "1";
                    
                                                    echo json_encode($respon);
                                                    exit();
                                                }
                                            } else {
                                                $respon['respon'] = "0";
                    
                                                echo json_encode($respon);
                                                exit();
                                            }
                                            $respon['respon'] = "0";
                    
                                            echo json_encode($respon);
                                            exit();
                                        } else {
                                            $respon['respon'] = "0";
                    
                                            echo json_encode($respon);
                                            exit();
                                        }
                                    } else {
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
                                    if ($jam < $dataKonf['PRE_SEKAM_SELESAI']) {
                                        $respon['respon'] = "0";
                    
                                        echo json_encode($respon);
                                        exit();
                                    } else if ($jam > $dataKonf['PRE_SEKAM_SELESAI'] && $jam < $dataKonf['PRE_SEKAM_OUT']) {
                                        $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tanggal'";
                    
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $data = mysqli_fetch_assoc($result);
                                            if ($data['STATUS'] == "hadir") {
                                                if ($data['CHECK_OUT']) {
                                                    $respon['respon'] = "0";
                    
                                                    echo json_encode($respon);
                                                    exit();
                                                } else {
                                                    $respon['respon'] = "1";
                    
                                                    echo json_encode($respon);
                                                    exit();
                                                }
                                            } else {
                                                $respon['respon'] = "0";
                    
                                                echo json_encode($respon);
                                                exit();
                                            }
                                            $respon['respon'] = "0";
                    
                                            echo json_encode($respon);
                                            exit();
                                        } else {
                                            $respon['respon'] = "0";
                    
                                            echo json_encode($respon);
                                            exit();
                                        }
                                    } else {
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
                    }else{
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);
                        exit();
                    }
                }else{
                    $respon['respon'] = "0";
                    
                    echo json_encode($respon);
                    exit();
                }
            }

            break;
        case "homeIzin":
            $tanggal = date("Y-m-d");
            
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT COUNT(*) AS izin FROM tabel_karyawan, tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_absensi
                WHERE tabel_karyawan.KD_KAWAN = tabel_tim_peserta.KD_KAWAN
                AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
                AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                AND tabel_karyawan.KD_KAWAN = '$akunKaryawan'
                AND STATUS = 'izin'
                AND STATUS_SURAT = 'approve'
                AND STATUS_PST = 'aktif'
                AND TGL = '$tanggal'";

                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    $data = mysqli_fetch_assoc($result);

                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "homeHadir":
            $tanggal = date("Y-m-d");
            
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT COUNT(*) AS hadir FROM tabel_karyawan, tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_absensi
                WHERE tabel_karyawan.KD_KAWAN = tabel_tim_peserta.KD_KAWAN
                AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
                AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                AND tabel_karyawan.KD_KAWAN = '$akunKaryawan'
                AND STATUS = 'hadir'
                AND STATUS_PST = 'aktif'
                AND TGL = '$tanggal'";

                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    $data = mysqli_fetch_assoc($result);

                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "viewPembimbing":
            $sqlKonf = "SELECT*FROM tabel_konfigurasi";
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $hari = date("l");
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                if ($hari == "Saturday" || $hari == "Sunday") {
                    $datalibur = array();
    
                    echo json_encode($datalibur);
                    exit();
                } else if ($hari == "Friday") {
                    if ($jam > $dataKonf['PRE_JUM_OUT']) {
                        $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            
                        date_default_timezone_set("Asia/Jakarta");
                        date_default_timezone_get();
            
                        $resultAkun = mysqli_query($conn, $sqlAkun);
                        if(mysqli_num_rows($resultAkun) > 0){
                            $dataAkun = mysqli_fetch_assoc($resultAkun);
                            $kodeKaryawan = $dataAkun['KD_KAWAN'];
                            $hari = date("Y-m-d");
            
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$hari'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $data = array();
            
                                echo json_encode($data);
                                exit();
                            }else{
                                $sqlabsen = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT != 'waiting'
                                AND STATUS_SURAT != 'disapprove'
                                AND STATUS_PST = 'aktif'
                                AND CHECK_OUT != 'null'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST";
                                $resultabsen = mysqli_query($conn, $sqlabsen);
                                $absenform = array();
                                if (mysqli_num_rows($resultabsen) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultabsen)) {
                                        array_push($absenform, $frm);
                                    }
                                }

                                $sqlizin = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT = 'approve'
                                AND STATUS_PST = 'aktif'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST";
                                $resultIzin = mysqli_query($conn, $sqlizin);
                                if (mysqli_num_rows($resultIzin) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultIzin)) {
                                        array_push($absenform, $frm);
                                    }
                                }

                                $sqlpulang = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND CHECK_OUT IS NULL
                                AND STATUS_PST = 'aktif'
                                AND status = 'hadir'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST";
                                $absenformpulang = array();
                                $resultpulang = mysqli_query($conn, $sqlpulang);
                                if (mysqli_num_rows($resultpulang) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultpulang)) {
                                        array_push($absenformpulang, $frm);
                                    }
                                }
                
                                $sqlwaiting = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT = 'waiting'
                                AND STATUS_PST = 'aktif'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST ASC";
                                $resultwaiting = mysqli_query($conn, $sqlwaiting);
                                $waitingform = array();
                                if (mysqli_num_rows($resultwaiting) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultwaiting)) {
                                        array_push($waitingform, $frm);
                                    }
                                }
                
                                $sqlkosong = "SELECT KATEGORI_ASAL, tabel_peserta.KD_PST, tabel_peserta.KD_AKUN, NAMA_PST, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NOHP_PST, NAMA_ASAL
                                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                                WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$hari' AND STATUS_SURAT != 'disapprove')
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_tim_peserta.KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND STATUS_PST = 'aktif'
                                AND TGL_SELESAI_TIM >= '$hari'
                                AND KD_KAWAN = '$kodeKaryawan'
                                ORDER BY NAMA_PST ASC";
                                $resultkosong = mysqli_query($conn, $sqlkosong);
                                $kosongform = array();
                                if (mysqli_num_rows($resultkosong) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultkosong)) {
                                        array_push($kosongform, $frm);
                                    }
                                }
                
                                $datapresensi = array();
                                $datapulang = array();
                                $datawaiting = array();
                                $datakosong = array();
                                $pembatas = array();
                                $menunggu = array();
                                $pulang = array();
                                $tanggal = date("d/m/y");
                
                                foreach ($absenform as $absen) {
                                    $datapresensi[] = array('KD_PST' => $absen['KD_PST'], 'NAMA_PST' => ucwords($absen['NAMA_PST']), 'NAMA_ASAL' => ucwords($absen['NAMA_ASAL']), 'tanggal' => $absen['tanggal'], 'KEHADIRAN' => $absen['KEHADIRAN'], 'CHECK_IN' => $absen['CHECK_IN'], 'CHECK_OUT' => $absen['CHECK_OUT'], 'LOKASI_CHECK_IN' => $absen['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $absen['LOKASI_CHECK_OUT'], 'KETERANGAN' => $absen['KETERANGAN'], 'KEGIATAN' => $absen['KEGIATAN'], 'STATUS' => $absen['STATUS'], 'surat' => $absen['surat'], 'srt' =>$absen['srt'], 'surat' => $absen['surat'], 'srt' => $absen['srt'], 'LOKASI_KIRIM_SURAT' => $absen['LOKASI_KIRIM_SURAT'], 'url' => $absen['url'], 'STATUS_SURAT' => $absen['STATUS_SURAT'], 'KATEGORI_ASAL' => $absen['KATEGORI_ASAL']);
                                }

                                foreach ($absenformpulang as $absen) {
                                    $datapulang[] = array('KD_PST' => $absen['KD_PST'], 'NAMA_PST' => ucwords($absen['NAMA_PST']), 'NAMA_ASAL' => ucwords($absen['NAMA_ASAL']), 'tanggal' => $absen['tanggal'], 'KEHADIRAN' => $absen['KEHADIRAN'], 'CHECK_IN' => $absen['CHECK_IN'], 'CHECK_OUT' => $absen['CHECK_OUT'], 'LOKASI_CHECK_IN' => $absen['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $absen['LOKASI_CHECK_OUT'], 'KETERANGAN' => $absen['KETERANGAN'], 'KEGIATAN' => $absen['KEGIATAN'], 'STATUS' => $absen['STATUS'], 'surat' => $absen['surat'], 'srt' =>$absen['srt'], 'surat' => $absen['surat'], 'srt' => $absen['srt'], 'LOKASI_KIRIM_SURAT' => $absen['LOKASI_KIRIM_SURAT'], 'url' => $absen['url'], 'STATUS_SURAT' => $absen['STATUS_SURAT'], 'KATEGORI_ASAL' => $absen['KATEGORI_ASAL']);
                                }
                
                                foreach ($waitingform as $waiting) {
                                    $datawaiting[] = array('KD_PST' => $waiting['KD_PST'], 'NAMA_PST' => ucwords($waiting['NAMA_PST']), 'NAMA_ASAL' => ucwords($waiting['NAMA_ASAL']), 'tanggal' => $waiting['tanggal'], 'KEHADIRAN' => $waiting['KEHADIRAN'], 'CHECK_IN' => $waiting['CHECK_IN'], 'CHECK_OUT' => $waiting['CHECK_OUT'], 'LOKASI_CHECK_IN' => $waiting['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $waiting['LOKASI_CHECK_OUT'], 'KETERANGAN' => $waiting['KETERANGAN'], 'KEGIATAN' => $waiting['KEGIATAN'], 'STATUS' => $waiting['STATUS'], 'surat' => $waiting['surat'], 'srt' =>$waiting['srt'], 'surat' => $waiting['surat'], 'srt' => $waiting['srt'], 'LOKASI_KIRIM_SURAT' => $waiting['LOKASI_KIRIM_SURAT'], 'url' => $waiting['url'], 'STATUS_SURAT' => $waiting['STATUS_SURAT'], 'KATEGORI_ASAL' => $waiting['KATEGORI_ASAL']);
                                }
                
                                foreach ($kosongform as $kosong) {
                                    $datakosong[] = array('KD_PST' => $kosong['KD_PST'], 'NAMA_PST' => ucwords($kosong['NAMA_PST']), 'NAMA_ASAL' => ucwords($kosong['NAMA_ASAL']), 'tanggal' => $tanggal, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => $kosong['url'], 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => $kosong['KATEGORI_ASAL']);
                                }
                
                                $nama_mhs = array_column($datakosong, 'NAMA_PST');
                                array_multisort($nama_mhs, SORT_ASC, $datakosong);

                                if($datapulang){
                                    $pulang = array(
                                        array('KD_PST' => 'pulang', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
            
                                    foreach ($datapulang as $data) {
                                        $pulang[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }
            
            
                                if($datawaiting){
                                    $menunggu = array(
                                        array('KD_PST' => 'menunggu', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
            
                                    foreach ($datawaiting as $data) {
                                        $menunggu[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }
            
                                if($datakosong){
                                    $pembatas = array(
                                        array('KD_PST' => 'pembatas', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
                                    
                                    foreach ($datakosong as $data) {
                                        $pembatas[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }

                                foreach ($pulang as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
            
                                foreach ($menunggu as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
                
                                foreach ($pembatas as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
                
                                echo json_encode($datapresensi);
                                exit();
                            }
                        }
                    } else {
                        $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            
                        date_default_timezone_set("Asia/Jakarta");
                        date_default_timezone_get();
            
                        $resultAkun = mysqli_query($conn, $sqlAkun);
                        if(mysqli_num_rows($resultAkun) > 0){
                            $dataAkun = mysqli_fetch_assoc($resultAkun);
                            $kodeKaryawan = $dataAkun['KD_KAWAN'];
                            $hari = date("Y-m-d");
            
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$hari'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $data = array();
            
                                echo json_encode($data);
                                exit();
                            }else{
                                $sqlabsen = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT != 'waiting'
                                AND STATUS_SURAT != 'disapprove'
                                AND STATUS_PST = 'aktif'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST";
                                $resultabsen = mysqli_query($conn, $sqlabsen);
                                $absenform = array();
                                if (mysqli_num_rows($resultabsen) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultabsen)) {
                                        array_push($absenform, $frm);
                                    }
                                }
                
                                $sqlwaiting = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT = 'waiting'
                                AND STATUS_PST = 'aktif'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST ASC";
                                $resultwaiting = mysqli_query($conn, $sqlwaiting);
                                $waitingform = array();
                                if (mysqli_num_rows($resultwaiting) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultwaiting)) {
                                        array_push($waitingform, $frm);
                                    }
                                }
                
                                $sqlkosong = "SELECT KATEGORI_ASAL, tabel_peserta.KD_PST, tabel_peserta.KD_AKUN, NAMA_PST, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NOHP_PST, NAMA_ASAL
                                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                                WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$hari' AND STATUS_SURAT != 'disapprove')
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_tim_peserta.KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND STATUS_PST = 'aktif'
                                AND TGL_SELESAI_TIM >= '$hari'
                                AND KD_KAWAN = '$kodeKaryawan'
                                ORDER BY NAMA_PST ASC";
                                $resultkosong = mysqli_query($conn, $sqlkosong);
                                $kosongform = array();
                                if (mysqli_num_rows($resultkosong) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultkosong)) {
                                        array_push($kosongform, $frm);
                                    }
                                }
                
                                $datapresensi = array();
                                $datawaiting = array();
                                $datakosong = array();
                                $pembatas = array();
                                $menunggu = array();
                                $tanggal = date("d/m/y");
                
                                foreach ($absenform as $absen) {
                                    $datapresensi[] = array('KD_PST' => $absen['KD_PST'], 'NAMA_PST' => ucwords($absen['NAMA_PST']), 'NAMA_ASAL' => ucwords($absen['NAMA_ASAL']), 'tanggal' => $absen['tanggal'], 'KEHADIRAN' => $absen['KEHADIRAN'], 'CHECK_IN' => $absen['CHECK_IN'], 'CHECK_OUT' => $absen['CHECK_OUT'], 'LOKASI_CHECK_IN' => $absen['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $absen['LOKASI_CHECK_OUT'], 'KETERANGAN' => $absen['KETERANGAN'], 'KEGIATAN' => $absen['KEGIATAN'], 'STATUS' => $absen['STATUS'], 'surat' => $absen['surat'], 'srt' =>$absen['srt'], 'surat' => $absen['surat'], 'srt' => $absen['srt'], 'LOKASI_KIRIM_SURAT' => $absen['LOKASI_KIRIM_SURAT'], 'url' => $absen['url'], 'STATUS_SURAT' => $absen['STATUS_SURAT'], 'KATEGORI_ASAL' => $absen['KATEGORI_ASAL']);
                                }
                
                                foreach ($waitingform as $waiting) {
                                    $datawaiting[] = array('KD_PST' => $waiting['KD_PST'], 'NAMA_PST' => ucwords($waiting['NAMA_PST']), 'NAMA_ASAL' => ucwords($waiting['NAMA_ASAL']), 'tanggal' => $waiting['tanggal'], 'KEHADIRAN' => $waiting['KEHADIRAN'], 'CHECK_IN' => $waiting['CHECK_IN'], 'CHECK_OUT' => $waiting['CHECK_OUT'], 'LOKASI_CHECK_IN' => $waiting['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $waiting['LOKASI_CHECK_OUT'], 'KETERANGAN' => $waiting['KETERANGAN'], 'KEGIATAN' => $waiting['KEGIATAN'], 'STATUS' => $waiting['STATUS'], 'surat' => $waiting['surat'], 'srt' =>$waiting['srt'], 'surat' => $waiting['surat'], 'srt' => $waiting['srt'], 'LOKASI_KIRIM_SURAT' => $waiting['LOKASI_KIRIM_SURAT'], 'url' => $waiting['url'], 'STATUS_SURAT' => $waiting['STATUS_SURAT'], 'KATEGORI_ASAL' => $waiting['KATEGORI_ASAL']);
                                }
                
                                foreach ($kosongform as $kosong) {
                                    $datakosong[] = array('KD_PST' => $kosong['KD_PST'], 'NAMA_PST' => ucwords($kosong['NAMA_PST']), 'NAMA_ASAL' => ucwords($kosong['NAMA_ASAL']), 'tanggal' => $tanggal, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => $kosong['url'], 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => $kosong['KATEGORI_ASAL']);
                                }
                
                                $nama_mhs = array_column($datakosong, 'NAMA_PST');
                                array_multisort($nama_mhs, SORT_ASC, $datakosong);
            
            
                                if($datawaiting){
                                    $menunggu = array(
                                        array('KD_PST' => 'menunggu', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
            
                                    foreach ($datawaiting as $data) {
                                        $menunggu[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }
            
                                if($datakosong){
                                    $pembatas = array(
                                        array('KD_PST' => 'pembatas', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
                                    
                                    foreach ($datakosong as $data) {
                                        $pembatas[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }
            
                                foreach ($menunggu as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
                
                                foreach ($pembatas as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
                
                                echo json_encode($datapresensi);
                                exit();
                            }
                        }
                    }
                } else {
                    if ($jam > $dataKonf['PRE_SEKAM_OUT']) {
                        $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            
                        date_default_timezone_set("Asia/Jakarta");
                        date_default_timezone_get();
            
                        $resultAkun = mysqli_query($conn, $sqlAkun);
                        if(mysqli_num_rows($resultAkun) > 0){
                            $dataAkun = mysqli_fetch_assoc($resultAkun);
                            $kodeKaryawan = $dataAkun['KD_KAWAN'];
                            $hari = date("Y-m-d");
            
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$hari'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $data = array();
            
                                echo json_encode($data);
                                exit();
                            }else{
                                $sqlabsen = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT != 'waiting'
                                AND STATUS_SURAT != 'disapprove'
                                AND STATUS_PST = 'aktif'
                                AND CHECK_OUT != 'null'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST";
                                $resultabsen = mysqli_query($conn, $sqlabsen);
                                $absenform = array();
                                if (mysqli_num_rows($resultabsen) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultabsen)) {
                                        array_push($absenform, $frm);
                                    }
                                }

                                $sqlizin = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT = 'approve'
                                AND STATUS_PST = 'aktif'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST";
                                $resultIzin = mysqli_query($conn, $sqlizin);
                                if (mysqli_num_rows($resultIzin) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultIzin)) {
                                        array_push($absenform, $frm);
                                    }
                                }

                                $sqlpulang = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND CHECK_OUT IS NULL
                                AND STATUS_PST = 'aktif'
                                AND status = 'hadir'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST";
                                $absenformpulang = array();
                                $resultpulang = mysqli_query($conn, $sqlpulang);
                                if (mysqli_num_rows($resultpulang) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultpulang)) {
                                        array_push($absenformpulang, $frm);
                                    }
                                }
                
                                $sqlwaiting = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT = 'waiting'
                                AND STATUS_PST = 'aktif'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST ASC";
                                $resultwaiting = mysqli_query($conn, $sqlwaiting);
                                $waitingform = array();
                                if (mysqli_num_rows($resultwaiting) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultwaiting)) {
                                        array_push($waitingform, $frm);
                                    }
                                }
                
                                $sqlkosong = " SELECT KATEGORI_ASAL, tabel_peserta.KD_PST, tabel_peserta.KD_AKUN, NAMA_PST, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NOHP_PST, NAMA_ASAL
                                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                                WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$hari' AND STATUS_SURAT != 'disapprove')
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_tim_peserta.KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND STATUS_PST = 'aktif'
                                AND TGL_SELESAI_TIM >= '$hari'
                                AND KD_KAWAN = '$kodeKaryawan'
                                ORDER BY NAMA_PST ASC";
                                $resultkosong = mysqli_query($conn, $sqlkosong);
                                $kosongform = array();
                                if (mysqli_num_rows($resultkosong) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultkosong)) {
                                        array_push($kosongform, $frm);
                                    }
                                }
                
                                $datapresensi = array();
                                $datapulang = array();
                                $datawaiting = array();
                                $datakosong = array();
                                $pembatas = array();
                                $menunggu = array();
                                $pulang = array();
                                $tanggal = date("d/m/y");
                
                                foreach ($absenform as $absen) {
                                    $datapresensi[] = array('KD_PST' => $absen['KD_PST'], 'NAMA_PST' => ucwords($absen['NAMA_PST']), 'NAMA_ASAL' => ucwords($absen['NAMA_ASAL']), 'tanggal' => $absen['tanggal'], 'KEHADIRAN' => $absen['KEHADIRAN'], 'CHECK_IN' => $absen['CHECK_IN'], 'CHECK_OUT' => $absen['CHECK_OUT'], 'LOKASI_CHECK_IN' => $absen['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $absen['LOKASI_CHECK_OUT'], 'KETERANGAN' => $absen['KETERANGAN'], 'KEGIATAN' => $absen['KEGIATAN'], 'STATUS' => $absen['STATUS'], 'surat' => $absen['surat'], 'srt' =>$absen['srt'], 'surat' => $absen['surat'], 'srt' => $absen['srt'], 'LOKASI_KIRIM_SURAT' => $absen['LOKASI_KIRIM_SURAT'], 'url' => $absen['url'], 'STATUS_SURAT' => $absen['STATUS_SURAT'], 'KATEGORI_ASAL' => $absen['KATEGORI_ASAL']);
                                }

                                foreach ($absenformpulang as $absen) {
                                    $datapulang[] = array('KD_PST' => $absen['KD_PST'], 'NAMA_PST' => ucwords($absen['NAMA_PST']), 'NAMA_ASAL' => ucwords($absen['NAMA_ASAL']), 'tanggal' => $absen['tanggal'], 'KEHADIRAN' => $absen['KEHADIRAN'], 'CHECK_IN' => $absen['CHECK_IN'], 'CHECK_OUT' => $absen['CHECK_OUT'], 'LOKASI_CHECK_IN' => $absen['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $absen['LOKASI_CHECK_OUT'], 'KETERANGAN' => $absen['KETERANGAN'], 'KEGIATAN' => $absen['KEGIATAN'], 'STATUS' => $absen['STATUS'], 'surat' => $absen['surat'], 'srt' =>$absen['srt'], 'surat' => $absen['surat'], 'srt' => $absen['srt'], 'LOKASI_KIRIM_SURAT' => $absen['LOKASI_KIRIM_SURAT'], 'url' => $absen['url'], 'STATUS_SURAT' => $absen['STATUS_SURAT'], 'KATEGORI_ASAL' => $absen['KATEGORI_ASAL']);
                                }
                
                                foreach ($waitingform as $waiting) {
                                    $datawaiting[] = array('KD_PST' => $waiting['KD_PST'], 'NAMA_PST' => ucwords($waiting['NAMA_PST']), 'NAMA_ASAL' => ucwords($waiting['NAMA_ASAL']), 'tanggal' => $waiting['tanggal'], 'KEHADIRAN' => $waiting['KEHADIRAN'], 'CHECK_IN' => $waiting['CHECK_IN'], 'CHECK_OUT' => $waiting['CHECK_OUT'], 'LOKASI_CHECK_IN' => $waiting['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $waiting['LOKASI_CHECK_OUT'], 'KETERANGAN' => $waiting['KETERANGAN'], 'KEGIATAN' => $waiting['KEGIATAN'], 'STATUS' => $waiting['STATUS'], 'surat' => $waiting['surat'], 'srt' =>$waiting['srt'], 'surat' => $waiting['surat'], 'srt' => $waiting['srt'], 'LOKASI_KIRIM_SURAT' => $waiting['LOKASI_KIRIM_SURAT'], 'url' => $waiting['url'], 'STATUS_SURAT' => $waiting['STATUS_SURAT'], 'KATEGORI_ASAL' => $waiting['KATEGORI_ASAL']);
                                }
                
                                foreach ($kosongform as $kosong) {
                                    $datakosong[] = array('KD_PST' => $kosong['KD_PST'], 'NAMA_PST' => ucwords($kosong['NAMA_PST']), 'NAMA_ASAL' => ucwords($kosong['NAMA_ASAL']), 'tanggal' => $tanggal, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => $kosong['url'], 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => $kosong['KATEGORI_ASAL']);
                                }
                
                                $nama_mhs = array_column($datakosong, 'NAMA_PST');
                                array_multisort($nama_mhs, SORT_ASC, $datakosong);

                                if($datapulang){
                                    $pulang = array(
                                        array('KD_PST' => 'pulang', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
            
                                    foreach ($datapulang as $data) {
                                        $pulang[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }
            
            
                                if($datawaiting){
                                    $menunggu = array(
                                        array('KD_PST' => 'menunggu', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
            
                                    foreach ($datawaiting as $data) {
                                        $menunggu[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }
            
                                if($datakosong){
                                    $pembatas = array(
                                        array('KD_PST' => 'pembatas', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
                                    
                                    foreach ($datakosong as $data) {
                                        $pembatas[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }

                                foreach ($pulang as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
            
                                foreach ($menunggu as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
                
                                foreach ($pembatas as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
                
                                echo json_encode($datapresensi);
                                exit();
                            }
                        }
                    } else {
                        $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            
                        date_default_timezone_set("Asia/Jakarta");
                        date_default_timezone_get();
            
                        $resultAkun = mysqli_query($conn, $sqlAkun);
                        if(mysqli_num_rows($resultAkun) > 0){
                            $dataAkun = mysqli_fetch_assoc($resultAkun);
                            $kodeKaryawan = $dataAkun['KD_KAWAN'];
                            $hari = date("Y-m-d");
            
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$hari'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $data = array();
            
                                echo json_encode($data);
                                exit();
                            }else{
                                $sqlabsen = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT != 'waiting'
                                AND STATUS_SURAT != 'disapprove'
                                AND STATUS_PST = 'aktif'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST";
                                $resultabsen = mysqli_query($conn, $sqlabsen);
                                $absenform = array();
                                if (mysqli_num_rows($resultabsen) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultabsen)) {
                                        array_push($absenform, $frm);
                                    }
                                }
                
                                $sqlwaiting = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND KD_KAWAN = '$kodeKaryawan'
                                AND STATUS_SURAT = 'waiting'
                                AND STATUS_PST = 'aktif'
                                AND TGL = '$hari'
                                ORDER BY NAMA_PST ASC";
                                $resultwaiting = mysqli_query($conn, $sqlwaiting);
                                $waitingform = array();
                                if (mysqli_num_rows($resultwaiting) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultwaiting)) {
                                        array_push($waitingform, $frm);
                                    }
                                }
                
                                $sqlkosong = "SELECT KATEGORI_ASAL, tabel_peserta.KD_PST, tabel_peserta.KD_AKUN, NAMA_PST, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NOHP_PST, NAMA_ASAL
                                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                                WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$hari' AND STATUS_SURAT != 'disapprove')
                                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                                AND tabel_tim_peserta.KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                                AND STATUS_PST = 'aktif'
                                AND TGL_SELESAI_TIM >= '$hari'
                                AND KD_KAWAN = '$kodeKaryawan'
                                ORDER BY NAMA_PST ASC";
                                $resultkosong = mysqli_query($conn, $sqlkosong);
                                $kosongform = array();
                                if (mysqli_num_rows($resultkosong) > 0) {
                                    while ($frm = mysqli_fetch_assoc($resultkosong)) {
                                        array_push($kosongform, $frm);
                                    }
                                }
                
                                $datapresensi = array();
                                $datawaiting = array();
                                $datakosong = array();
                                $pembatas = array();
                                $menunggu = array();
                                $tanggal = date("d/m/y");
                
                                foreach ($absenform as $absen) {
                                    $datapresensi[] = array('KD_PST' => $absen['KD_PST'], 'NAMA_PST' => ucwords($absen['NAMA_PST']), 'NAMA_ASAL' => ucwords($absen['NAMA_ASAL']), 'tanggal' => $absen['tanggal'], 'KEHADIRAN' => $absen['KEHADIRAN'], 'CHECK_IN' => $absen['CHECK_IN'], 'CHECK_OUT' => $absen['CHECK_OUT'], 'LOKASI_CHECK_IN' => $absen['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $absen['LOKASI_CHECK_OUT'], 'KETERANGAN' => $absen['KETERANGAN'], 'KEGIATAN' => $absen['KEGIATAN'], 'STATUS' => $absen['STATUS'], 'surat' => $absen['surat'], 'srt' =>$absen['srt'], 'surat' => $absen['surat'], 'srt' => $absen['srt'], 'LOKASI_KIRIM_SURAT' => $absen['LOKASI_KIRIM_SURAT'], 'url' => $absen['url'], 'STATUS_SURAT' => $absen['STATUS_SURAT'], 'KATEGORI_ASAL' => $absen['KATEGORI_ASAL']);
                                }
                
                                foreach ($waitingform as $waiting) {
                                    $datawaiting[] = array('KD_PST' => $waiting['KD_PST'], 'NAMA_PST' => ucwords($waiting['NAMA_PST']), 'NAMA_ASAL' => ucwords($waiting['NAMA_ASAL']), 'tanggal' => $waiting['tanggal'], 'KEHADIRAN' => $waiting['KEHADIRAN'], 'CHECK_IN' => $waiting['CHECK_IN'], 'CHECK_OUT' => $waiting['CHECK_OUT'], 'LOKASI_CHECK_IN' => $waiting['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $waiting['LOKASI_CHECK_OUT'], 'KETERANGAN' => $waiting['KETERANGAN'], 'KEGIATAN' => $waiting['KEGIATAN'], 'STATUS' => $waiting['STATUS'], 'surat' => $waiting['surat'], 'srt' =>$waiting['srt'], 'surat' => $waiting['surat'], 'srt' => $waiting['srt'], 'LOKASI_KIRIM_SURAT' => $waiting['LOKASI_KIRIM_SURAT'], 'url' => $waiting['url'], 'STATUS_SURAT' => $waiting['STATUS_SURAT'], 'KATEGORI_ASAL' => $waiting['KATEGORI_ASAL']);
                                }
                
                                foreach ($kosongform as $kosong) {
                                    $datakosong[] = array('KD_PST' => $kosong['KD_PST'], 'NAMA_PST' => ucwords($kosong['NAMA_PST']), 'NAMA_ASAL' => ucwords($kosong['NAMA_ASAL']), 'tanggal' => $tanggal, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => $kosong['url'], 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => $kosong['KATEGORI_ASAL']);
                                }
                
                                $nama_mhs = array_column($datakosong, 'NAMA_PST');
                                array_multisort($nama_mhs, SORT_ASC, $datakosong);
            
            
                                if($datawaiting){
                                    $menunggu = array(
                                        array('KD_PST' => 'menunggu', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
            
                                    foreach ($datawaiting as $data) {
                                        $menunggu[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }
            
                                if($datakosong){
                                    $pembatas = array(
                                        array('KD_PST' => 'pembatas', 'NAMA_PST' => null, 'NAMA_ASAL' => null, 'tanggal' => null, 'KEHADIRAN' => null, 'CHECK_IN' => null, 'CHECK_OUT' => null, 'LOKASI_CHECK_IN' => null, 'LOKASI_CHECK_OUT' => null, 'KETERANGAN' => null, 'KEGIATAN' => null, 'STATUS' => null, 'surat' => null, 'srt' => null, 'surat' => null, 'srt' => null, 'LOKASI_KIRIM_SURAT' => null, 'url' => null, 'STATUS_SURAT' => null, 'KATEGORI_ASAL' => null)
                                    );
                                    
                                    foreach ($datakosong as $data) {
                                        $pembatas[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                    }
                                }
            
                                foreach ($menunggu as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
                
                                foreach ($pembatas as $data) {
                                    $datapresensi[] = array('KD_PST' => $data['KD_PST'], 'NAMA_PST' => ucwords($data['NAMA_PST']), 'NAMA_ASAL' => ucwords($data['NAMA_ASAL']), 'tanggal' => $data['tanggal'], 'KEHADIRAN' => $data['KEHADIRAN'], 'CHECK_IN' => $data['CHECK_IN'], 'CHECK_OUT' => $data['CHECK_OUT'], 'LOKASI_CHECK_IN' => $data['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $data['LOKASI_CHECK_OUT'], 'KETERANGAN' => $data['KETERANGAN'], 'KEGIATAN' => $data['KEGIATAN'], 'STATUS' => $data['STATUS'], 'surat' => $data['surat'], 'srt' =>$data['srt'], 'surat' => $data['surat'], 'srt' => $data['srt'], 'LOKASI_KIRIM_SURAT' => $data['LOKASI_KIRIM_SURAT'], 'url' => $data['url'], 'STATUS_SURAT' => $data['STATUS_SURAT'], 'KATEGORI_ASAL' => $data['KATEGORI_ASAL']);
                                }
                
                                echo json_encode($datapresensi);
                                exit();
                            }
                        }
                    }
                }
            }
            break;
        case "viewPembimbingKode":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";

            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");
                $kode = $_POST['kode'];

                $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$hari'";
                $resultlibur = mysqli_query($conn, $libur);
                if(mysqli_num_rows($resultlibur) > 0){
                    $data = array();

                    echo json_encode($data);
                    exit();
                }else{
                    $sqlabsen = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                    WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                    AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                    AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                    AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                    AND KD_KAWAN = '$kodeKaryawan'
                    AND STATUS_SURAT != 'waiting'
                    AND STATUS_SURAT != 'disapprove'
                    AND STATUS_PST = 'aktif'
                    AND TGL = '$hari'
                    AND tabel_peserta.KD_PST = '$kode'
                    ORDER BY NAMA_PST";
                    $resultabsen = mysqli_query($conn, $sqlabsen);
                    $absenform = array();
                    if (mysqli_num_rows($resultabsen) > 0) {
                        while ($frm = mysqli_fetch_assoc($resultabsen)) {
                            array_push($absenform, $frm);
                        }
                    }

                    $datapresensi = array();
                    $tanggal = date("d/m/y");
    
                    foreach ($absenform as $absen) {
                        $datapresensi[] = array('KD_PST' => $absen['KD_PST'], 'NAMA_PST' => ucwords($absen['NAMA_PST']), 'NAMA_ASAL' => ucwords($absen['NAMA_ASAL']), 'tanggal' => $absen['tanggal'], 'KEHADIRAN' => $absen['KEHADIRAN'], 'CHECK_IN' => $absen['CHECK_IN'], 'CHECK_OUT' => $absen['CHECK_OUT'], 'LOKASI_CHECK_IN' => $absen['LOKASI_CHECK_IN'], 'LOKASI_CHECK_OUT' => $absen['LOKASI_CHECK_OUT'], 'KETERANGAN' => $absen['KETERANGAN'], 'KEGIATAN' => $absen['KEGIATAN'], 'STATUS' => $absen['STATUS'], 'surat' => $absen['surat'], 'srt' =>$absen['srt'], 'surat' => $absen['surat'], 'srt' => $absen['srt'], 'LOKASI_KIRIM_SURAT' => $absen['LOKASI_KIRIM_SURAT'], 'url' => $absen['url'], 'STATUS_SURAT' => $absen['STATUS_SURAT'], 'KATEGORI_ASAL' => $absen['KATEGORI_ASAL']);
                    }
    
                    echo json_encode($datapresensi);
                    exit();
                }
            }
            break;
        case "jumlahViewPembimbing":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";

            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $day = date("l");
            $tanggal = date("Y-m-d");

            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");

                if($day == "Saturday" || $day == "Sunday"){
                    $data['absensi'] = "0";

                    echo json_encode($data);
                    exit();
                }else{
                    $sqlcek = "SELECT COUNT(*) AS jumlah FROM tabel_tim_peserta WHERE STATUS_TIM = 'aktif'
                    AND KD_KAWAN = '$akunKaryawan' AND KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal') AND TGL_SELESAI_TIM >= '$tanggal'";
                    $resultcek = mysqli_query($conn, $sqlcek);
                    if (mysqli_num_rows($resultcek) > 0) {
                        $datacek = mysqli_fetch_assoc($resultcek);
        
                        if($datacek['jumlah'] == 0){
                            $data['absensi'] = "0";

                            echo json_encode($data);
                            exit();
                        }else{
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $data['absensi'] = "0";
        
                                echo json_encode($data);
                                exit();
                            }else{
                                $data['absensi'] = "1";
            
                                echo json_encode($data);
                                exit();
                            }
                        }

                    }
                }
            }
            break;
        case "jumlahViewPembimbingKode":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";

            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $day = date("l");
            $tanggal = date("Y-m-d");
            $kode = $_POST['kode'];

            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $hari = date("Y-m-d");

                $sqlabsen = "SELECT*, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, SURAT AS srt FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta,tabel_asal,tabel_absensi
                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_PST = tabel_absensi.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS_SURAT != 'waiting'
                AND STATUS_SURAT != 'disapprove'
                AND STATUS_PST = 'aktif'
                AND TGL = '$tanggal'
                AND tabel_peserta.KD_PST = '$kode'
                ORDER BY NAMA_PST";

                $resultabsen = mysqli_query($conn, $sqlabsen);
                $absenform = array();
                if (mysqli_num_rows($resultabsen) > 0) {
                    $data['absensi'] = "1";

                    echo json_encode($data);
                    exit();
                }else{
                    $data['absensi'] = "0";

                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "cekjamkerja":
            $sqlKonf = "SELECT*FROM tabel_konfigurasi";
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $hari = date("l");
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                if ($hari == "Saturday" || $hari == "Sunday") {
                    $respon['respon'] = "0";
    
                    echo json_encode($respon);
                    exit();
                } else if ($hari == "Friday") {
                    if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_SELESAI']) {
                        $respon['respon'] = "1";
    
                        echo json_encode($respon);
                        exit();
                    } else {
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);
                        exit();
                    }
                } else {
                    if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_SELESAI']) {
                        $respon['respon'] = "1";
    
                        echo json_encode($respon);
                        exit();
                    } else {
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);
                        exit();
                    }
                }
            }

            break; 
        case "cekjampulangkerja":
            $sqlKonf = "SELECT*FROM tabel_konfigurasi";
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $hari = date("l");
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            $resultKonf = mysqli_query($conn, $sqlKonf);
            if (mysqli_num_rows($resultKonf) > 0) {
                $dataKonf = mysqli_fetch_assoc($resultKonf);

                if ($hari == "Saturday" || $hari == "Sunday") {
                    $respon['respon'] = "0";
    
                    echo json_encode($respon);
                    exit();
                } else if ($hari == "Friday") {
                    if ($jam > $dataKonf['PRE_JUM_OUT']) {
                        $respon['respon'] = "1";
    
                        echo json_encode($respon);
                        exit();
                    } else {
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);
                        exit();
                    }
                } else {
                    if ($jam > $dataKonf['PRE_SEKAM_OUT']) {
                        $respon['respon'] = "1";
    
                        echo json_encode($respon);
                        exit();
                    } else {
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);
                        exit();
                    }
                }
            }

            break; 
    }
}
