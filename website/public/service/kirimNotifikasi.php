<?php

$DB_NAME = "simaptapkl";
$DB_USER = "root";
$DB_PASS = "";
$DB_SERVER_LOC = "localhost";

$conn = mysqli_connect($DB_SERVER_LOC, $DB_USER, $DB_PASS, $DB_NAME);
require __DIR__ . '/vendor/autoload.php';

$options = array(
    'cluster' => 'ap1',
    'useTLS' => true
);

$pusher = new Pusher\Pusher(
    'c3100747ee53df61dca0',
    '50fa6b7e45eeed3b1ee2',
    '1460777',
    $options
);

$pilihan = $_POST['pilihan'];
$akun = $_POST['id'];
switch ($pilihan) {
    case "radius":
        $sql1 = "SELECT tabel_akun.KD_AKUN, NAMA_PST, tabel_tim_peserta.KD_TIM, KD_KAWAN FROM tabel_akun, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM AND tabel_akun.KD_AKUN = '$akun'";
        $result1 = mysqli_query($conn, $sql1);
        $data1 = mysqli_fetch_assoc($result1);

        $pembimbing = $data1['KD_KAWAN'];
        $sql2 = "SELECT KD_AKUN FROM tabel_karyawan WHERE KD_KAWAN = '$pembimbing'";
        $result2 = mysqli_query($conn, $sql2);
        $data2 = mysqli_fetch_assoc($result2);

        $data['nama'] = ucwords($data1['NAMA_PST']);
        $data['pembimbing'] = $data2['KD_AKUN'];
        $pusher->trigger('my-channel', $_POST['event'], $data);

        break;
    case "cekHari":
        date_default_timezone_set("Asia/Jakarta");
        date_default_timezone_get();

        $hari = date("l");
        $tanggal = date("Y-m-d");
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
        
                        if($tanggal <= $datamasapkl['TGL_SELESAI_TIM']){
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
                                        $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
                                        $resultAkun = mysqli_query($conn, $sqlAkun);
                                        if(mysqli_num_rows($resultAkun) > 0){
                                            $dataAkun = mysqli_fetch_assoc($resultAkun);
                                            $kodeMahasiswa = $dataAkun['KD_PST'];
                                            date_default_timezone_set("Asia/Jakarta");
                                            date_default_timezone_get();
                                            $waktu = date('Y-m-d H:i:s');
                                            $jam = date('H:i:s');
                                            $keterangan = "Pada jam " . $jam . " peserta berada diluar radius DISKOMINFO.";
                            
                                            $sql = "INSERT INTO tabel_logpos(TGL_LOG, KD_PST, KETERANGAN_LOG) VALUES('$waktu','$kodeMahasiswa','$keterangan')";
                                
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                $respon['respon'] = "1";
                                
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
                                        $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
                                        $resultAkun = mysqli_query($conn, $sqlAkun);
                                        if(mysqli_num_rows($resultAkun) > 0){
                                            $dataAkun = mysqli_fetch_assoc($resultAkun);
                                            $kodeMahasiswa = $dataAkun['KD_PST'];
                                            date_default_timezone_set("Asia/Jakarta");
                                            date_default_timezone_get();
                                            $waktu = date('Y-m-d H:i:s');
                                            $jam = date('H:i:s');
                                            $keterangan = "Pada jam " . $jam . " peserta berada diluar radius DISKOMINFO.";
                            
                                            $sql = "INSERT INTO tabel_logpos(TGL_LOG, KD_PST, KETERANGAN_LOG) VALUES('$waktu','$kodeMahasiswa','$keterangan')";
                                
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                $respon['respon'] = "1";
                                
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
    case "izin":
        $sql1 = "SELECT NAMA_KAWAN FROM tabel_akun, tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
        $result1 = mysqli_query($conn, $sql1);

        $data1 = mysqli_fetch_assoc($result1);
        $data['nama'] = ucwords($data1['NAMA_KAWAN']);
        $data['kodePeserta'] = $_POST['kodePeserta'];
        $data['surat'] = $_POST['surat'];
        $data['statusSurat'] = $_POST['statusSurat'];
        $data['alasan'] = $_POST['alasan'];
        $pusher->trigger('my-channel', $_POST['event'], $data);

        break;
    case "pesertaIzin":
        $sql1 = "SELECT tabel_akun.KD_AKUN, NAMA_PST, tabel_tim_peserta.KD_TIM, KD_KAWAN FROM tabel_akun, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM AND tabel_akun.KD_AKUN = '$akun'";
        $result1 = mysqli_query($conn, $sql1);
        $data1 = mysqli_fetch_assoc($result1);

        $pembimbing = $data1['KD_KAWAN'];
        $sql2 = "SELECT KD_AKUN FROM tabel_karyawan WHERE KD_KAWAN = '$pembimbing'";
        $result2 = mysqli_query($conn, $sql2);
        $data2 = mysqli_fetch_assoc($result2);

        $data['nama'] = ucwords($data1['NAMA_PST']);
        $data['surat'] = $_POST['surat'];
        $data['pembimbing'] = $data2['KD_AKUN'];
        $pusher->trigger('my-channel', $_POST['event'], $data);

        break;
    case "perbaruiabsenpulang":
        $sql1 = "SELECT tabel_peserta.KD_PST, tabel_akun.KD_AKUN, NAMA_PST, tabel_tim_peserta.KD_TIM, KD_KAWAN FROM tabel_akun, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM AND tabel_akun.KD_AKUN = '$akun'";
        $result1 = mysqli_query($conn, $sql1);
        $data1 = mysqli_fetch_assoc($result1);

        $pembimbing = $data1['KD_KAWAN'];
        $sql2 = "SELECT KD_AKUN FROM tabel_karyawan WHERE KD_KAWAN = '$pembimbing'";
        $result2 = mysqli_query($conn, $sql2);
        $data2 = mysqli_fetch_assoc($result2);

        $data['kode'] = $data1['KD_PST'];
        $data['nama'] = ucwords($data1['NAMA_PST']);
        $data['pembimbing'] = $data2['KD_AKUN'];
        $pusher->trigger('my-channel', $_POST['event'], $data);

        break;
    case "berhasilperbaruiabsenpulang":
        $mahasiswa = $_POST['kode'];
        $sql1 = "SELECT tabel_peserta.KD_PST, tabel_akun.KD_AKUN, NAMA_PST, tabel_tim_peserta.KD_TIM, KD_KAWAN FROM tabel_akun, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta WHERE tabel_peserta.kd_akun = tabel_akun.KD_AKUN AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM AND tabel_peserta.KD_PST = '$mahasiswa'";
        $result1 = mysqli_query($conn, $sql1);
        $data1 = mysqli_fetch_assoc($result1);

        $pembimbing = $data1['KD_KAWAN'];
        $sql2 = "SELECT KD_AKUN, NAMA_KAWAN FROM tabel_karyawan WHERE KD_KAWAN = '$pembimbing'";
        $result2 = mysqli_query($conn, $sql2);
        $data2 = mysqli_fetch_assoc($result2);

        $data['akun'] = $data1['KD_AKUN'];
        $data['pembimbing'] = ucwords($data2['NAMA_KAWAN']);
        $pusher->trigger('my-channel', $_POST['event'], $data);

        break;
    case "notifikasiPeserta":
        $data['peserta'] = $_POST['peserta'];
        $pusher->trigger('my-channel', $_POST['event'], $data);

        break;
    case "destroy":
            $sql1 = "SELECT tabel_akun.KD_AKUN, NAMA_PST, tabel_tim_peserta.KD_TIM, KD_KAWAN FROM tabel_akun, tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta WHERE tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM AND tabel_akun.KD_AKUN = '$akun'";
        $result1 = mysqli_query($conn, $sql1);
        $data1 = mysqli_fetch_assoc($result1);

        $pembimbing = $data1['KD_KAWAN'];
        $sql2 = "SELECT KD_AKUN FROM tabel_karyawan WHERE KD_KAWAN = '$pembimbing'";
        $result2 = mysqli_query($conn, $sql2);
        $data2 = mysqli_fetch_assoc($result2);

        $data['nama'] = ucwords($data1['NAMA_PST']);
        $data['kode'] = $_POST['kode'];
        $data['pembimbing'] = $data2['KD_AKUN'];
        $pusher->trigger('my-channel', $_POST['event'], $data);
        break;
}

