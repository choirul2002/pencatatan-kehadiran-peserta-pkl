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
                date_default_timezone_set("Asia/Jakarta");
                date_default_timezone_get();
                $tanggal = date("Y-m-d");

                $sql = "SELECT ID, DATE_FORMAT(TGL,'%d/%m/%y') AS tanggal, STATUS_SURAT, KETERANGAN, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS url FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND STATUS = 'izin' AND STATUS_SURAT != 'approve' AND TGL = '$tanggal' ORDER BY TGL DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['KETERANGAN'] = ucfirst($frm['KETERANGAN']);
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
        case "tambah":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "SELECT NAMA_PST, NAMA_ASAL FROM tabel_peserta NATURAL JOIN tabel_asal WHERE KD_PST = '$kodeMahasiswa'";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "kirim":
            $tgl = date("Y-m-d");
            $keterangan = $_POST['keterangan'];
            $surat = $_POST['surat'];
            $imstr = $_POST['image'];
            $status_surat = "waiting";
            $status = "izin";
            $path = "uploads/";
            $jalan = substr($_POST['jalan'],6);  
            $desa = $_POST['desa'];
            $kecamatan = substr($_POST['kecamatan'],10);
            $kabupaten = substr($_POST['kabupaten'],10);
            $provinsi = $_POST['provinsi'];
            $kodepos = $_POST['kodepos'];
            $lokasi = "Jln. ".$jalan.", Ds. ".$desa.", Kec. ".$kecamatan.", Kab. ".$kabupaten.", Prov. ".$provinsi.", ".$kodepos;

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.kd_akun AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "INSERT INTO tabel_absensi(KD_PST, TGL, KETERANGAN, surat, LOKASI_KIRIM_SURAT, STATUS_SURAT, STATUS) VALUES('$kodeMahasiswa','$tgl','$keterangan','$surat', '$lokasi','$status_surat','$status')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    if (file_put_contents($path . $surat, base64_decode($imstr)) == false) {
                        $respon['respon'] = "0";
    
                        echo json_encode($respon);  //kesalahan data
                        exit();
                    } else {
                        $respon['respon'] = "1";
                        echo json_encode($respon); //data berhasil dikirim
                        exit();
                    }
                }
            }

            break;
        case "cekizin":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tgl = date("Y-m-d");
            $hari = date("l");
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
    
                    if($tgl < $datamasapkl['TGL_MULAI_TIM']){
                        $respon['respon'] = "10";
            
                        echo json_encode($respon);
                        exit();
                    }else{
                    if($tgl <= $datamasapkl['TGL_SELESAI_TIM']){
                        if ($hari == "Saturday" || $hari == "Sunday") {
                            $respon['respon'] = "10";
            
                            echo json_encode($respon);
                            exit();
                        } else if ($hari == "Friday") {
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tgl'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $respon['respon'] = "10";
            
                                echo json_encode($respon);
                                exit();
                            }else{
                                if ($jam < $dataKonf['PRE_JUM_MULAI']) {
                                    $respon['respon'] = "1";
                
                                    echo json_encode($respon);
                                    exit();
                                } else if ($jam > $dataKonf['PRE_JUM_MULAI'] && $jam < $dataKonf['PRE_JUM_SELESAI']) {
                                    $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
                                    $resultAkun = mysqli_query($conn, $sqlAkun);
                                    if(mysqli_num_rows($resultAkun) > 0){
                                        $dataAkun = mysqli_fetch_assoc($resultAkun);
                                        $kodeMahasiswa = $dataAkun['KD_PST'];
                        
                                        $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tgl'";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $data = mysqli_fetch_assoc($result);
                                            if ($data['STATUS'] == "hadir") {
                                                $respon['respon'] = "1";
                    
                                                echo json_encode($respon);
                                                exit();
                                            } else {
                                                $respon['respon'] = "2";
                    
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
                                    $respon['respon'] = "2";
                
                                    echo json_encode($respon);
                                    exit();
                                }
                            }
                        } else {
                            $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tgl'";
                            $resultlibur = mysqli_query($conn, $libur);
                            if(mysqli_num_rows($resultlibur) > 0){
                                $respon['respon'] = "10";
            
                                echo json_encode($respon);
                                exit();
                            }else{
                                if ($jam < $dataKonf['PRE_SEKAM_MULAI']) {
                                    $respon['respon'] = "1";
                
                                    echo json_encode($respon);
                                    exit();
                                } else if ($jam > $dataKonf['PRE_SEKAM_MULAI'] && $jam < $dataKonf['PRE_SEKAM_SELESAI']) {
                                    $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
                                    $resultAkun = mysqli_query($conn, $sqlAkun);
                                    if(mysqli_num_rows($resultAkun) > 0){
                                        $dataAkun = mysqli_fetch_assoc($resultAkun);
                                        $kodeMahasiswa = $dataAkun['KD_PST'];
                        
                                        $sql = "SELECT*FROM tabel_absensi WHERE KD_PST = '$kodeMahasiswa' AND TGL = '$tgl'";
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            $data = mysqli_fetch_assoc($result);
                                            if ($data['STATUS'] == "hadir") {
                                                $respon['respon'] = "1";
                    
                                                echo json_encode($respon);
                                                exit();
                                            } else {
                                                $respon['respon'] = "2";
                    
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
                                    $respon['respon'] = "2";
                
                                    echo json_encode($respon);
                                    exit();
                                }
                            }
                        }
                    }else{
                        $respon['respon'] = "10";
            
                        echo json_encode($respon);
                        exit();
                    }
                    }
                }
            }

            break;

        case "kirimEdit":
            $absen = $_POST['kode'];
            $keterangan = $_POST['keterangan'];
            $surat = $_POST['surat'];
            $imstr = $_POST['image'];
            $status_surat = "waiting";
            $path = "uploads/";
            
            $sql1 = "SELECT*FROM tabel_absensi WHERE ID = '$absen'";
            $result1 = mysqli_query($conn, $sql1);
            $data = mysqli_fetch_assoc($result1);

            if (file_exists("uploads/" . $data['SURAT'])) {
                unlink("uploads/" . $data['SURAT']);
            }

            $sql = "UPDATE tabel_absensi SET KETERANGAN = '$keterangan', SURAT = '$surat', STATUS_SURAT = '$status_surat' WHERE ID = '$absen'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                if (file_put_contents($path . $surat, base64_decode($imstr)) == false) {
                    $respon['respon'] = "0";

                    echo json_encode($respon);  //kesalahan data
                    exit();
                } else {
                    $respon['respon'] = "1";

                    echo json_encode($respon);
                    exit();
                }
            }
            break;
        case "lihat":
            $absen = $_POST['id_absen'];
            $sql = "SELECT DATE_FORMAT(TGL,'%W, %d %M %Y') AS tanggal, STATUS_SURAT, NAMA_PST, NAMA_ASAL, SURAT, KETERANGAN, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS url FROM tabel_absensi NATURAL JOIN tabel_peserta NATURAL JOIN tabel_asal WHERE ID = '$absen'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);

                echo json_encode($data);
                exit();
            }
            break;
        case "hapus":
            $kode = $_POST['id_absen'];

            $sql1 = "SELECT*FROM tabel_absensi WHERE ID = '$kode'";
            $result1 = mysqli_query($conn, $sql1);
            $data = mysqli_fetch_assoc($result1);

            if (file_exists("uploads/" . $data['SURAT'])) {
                unlink("uploads/" . $data['SURAT']);
            }

            $sql = "DELETE FROM tabel_absensi WHERE ID = '$kode'";
            $result = mysqli_query($conn, $sql);
            if ($result) {

                $respon['respon'] = "1";

                echo json_encode($respon);
                exit();
            }
            break;
        case "formEdit":
            $idAbsen = $_POST['kode'];
            $sql = "SELECT KETERANGAN FROM tabel_absensi WHERE ID = '$idAbsen'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);

                echo json_encode($data);    //data all
                exit();
            }
            break;
        case "viewPembimbing":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.kd_akun AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT ID, KD_AKUN, NAMA_PST, NAMA_ASAL, KD_KAWAN, STATUS, DATE_FORMAT(TGL,'%d/%m/%y') AS tgl, STATUS_SURAT, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS foto, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, KATEGORI_ASAL
                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_absensi, tabel_asal
                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_absensi.KD_PST = tabel_peserta.KD_PST
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS = 'izin'
                AND STATUS_PST = 'aktif'
                AND STATUS_SURAT = 'waiting'
                AND TGL = '$tanggal'
                ORDER BY TGL DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
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
            }
            break;
        case "viewPembimbingKode":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $mahasiswa = $_POST['kode'];

                $sql = "SELECT ID, KD_AKUN, NAMA_PST, NAMA_ASAL, KD_KAWAN, STATUS, DATE_FORMAT(TGL,'%d/%m/%y') AS tgl, STATUS_SURAT, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS foto, CONCAT('http://192.168.43.57/simaptapkl/public/service/uploads/',SURAT) AS surat, KATEGORI_ASAL
                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_absensi, tabel_asal
                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_absensi.KD_PST = tabel_peserta.KD_PST
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS = 'izin'
                AND STATUS_PST = 'aktif'
                AND tabel_peserta.KD_PST = '$mahasiswa'
                AND STATUS_SURAT = 'waiting'
                AND TGL = '$tanggal'
                ORDER BY TGL DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
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
            }
            break;
        case "validasiSurat":
            $absen = $_POST['idSurat'];
            $statusSurat = $_POST['statusSurat'];

            $sql = "UPDATE tabel_absensi SET STATUS_SURAT = '$statusSurat' WHERE ID = '$absen'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $respon['respon'] = "1";

                echo json_encode($respon);
                exit();
            }
            break;
        case "jumlahIzinWaiting":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT COUNT(*) AS jumlah
                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_absensi, tabel_asal
                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_absensi.KD_PST = tabel_peserta.KD_PST
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS = 'izin'
                AND STATUS_PST = 'aktif'
                AND STATUS_SURAT = 'waiting'
                AND TGL = '$tanggal'
                ORDER BY TGL DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "jumlahIzinWaitingKode":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];
                $mahasiswa = $_POST['kode'];

                $sql = "SELECT COUNT(*) AS jumlah
                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_absensi, tabel_asal
                WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_absensi.KD_PST = tabel_peserta.KD_PST
                AND KD_KAWAN = '$kodeKaryawan'
                AND STATUS = 'izin'
                AND STATUS_PST = 'aktif'
                AND tabel_peserta.KD_PST = '$mahasiswa'
                AND STATUS_SURAT = 'waiting'
                AND TGL = '$tanggal'
                ORDER BY TGL DESC";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "jumlahIzin":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];
                date_default_timezone_set("Asia/Jakarta");
                date_default_timezone_get();
                $tanggal = date("Y-m-d");

                $sql = "SELECT COUNT(*) AS jumlah FROM tabel_absensi WHERE STATUS = 'izin' AND TGL = '$tanggal' AND KD_PST = '$kodeMahasiswa' AND STATUS_SURAT != 'approve'";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);    //data full
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
    }
}
