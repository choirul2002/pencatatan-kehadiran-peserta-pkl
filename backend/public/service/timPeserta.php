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
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta,tabel_dtl_tim_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];
                $kodeTim = $dataAkun['KD_TIM'];

                $sql = "SELECT *,CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_dtl_tim_peserta, tabel_peserta, tabel_akun WHERE tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN AND tabel_peserta.KD_PST != '$kodeMahasiswa' AND KD_TIM = '$kodeTim'";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['NAMA_PST'] = ucfirst($frm['NAMA_PST']);
                        $frm['JK_PST'] = ucfirst($frm['JK_PST']);
                        $frm['ALAMAT_PST'] = ucfirst($frm['ALAMAT_PST']);
                        $frm['AGAMA_PST'] = ucfirst($frm['AGAMA_PST']);
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
        case "dataTimPesertaPembimbing":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT * FROM tabel_tim_peserta, tabel_asal WHERE STATUS_TIM = 'aktif' AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND KD_KAWAN = '$akunKaryawan'
                AND KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                AND TGL_SELESAI_TIM >= '$tanggal'
                ORDER BY NAMA_TIM";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $masuk = new \DateTime($frm['TGL_MULAI_TIM']);
                        $hari_masuk = $masuk->format('D');
                        $frm['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($frm['TGL_MULAI_TIM']);

                        $keluar = new \DateTime($frm['TGL_SELESAI_TIM']);
                        $hari_keluar = $keluar->format('D');
                        $frm['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($frm['TGL_SELESAI_TIM']);

                        $frm['NAMA_TIM'] = ucwords($frm['NAMA_TIM']);
                        $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
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
        case "dataTimPesertaPembimbingMaps":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT * FROM tabel_tim_peserta, tabel_asal, tabel_dtl_tim_peserta, tabel_peserta WHERE STATUS_TIM = 'aktif' AND tabel_tim_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
                AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
                AND KD_KAWAN = '$akunKaryawan'
                AND tabel_tim_peserta.KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                AND TGL_SELESAI_TIM >= '$tanggal'
                ORDER BY NAMA_PST";
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
            }
            break;
        case "jumlahDataTimPesertaPembimbing":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT COUNT(*) AS jumlah FROM tabel_tim_peserta WHERE STATUS_TIM = 'aktif'
                AND KD_KAWAN = '$akunKaryawan' 
                AND KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                AND TGL_SELESAI_TIM >= '$tanggal'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    echo json_encode($data);
                    exit();
                }
            }
            break;
        case "dataAnggota":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];
                $tim = $_POST['tim'];

                $sql = "SELECT *, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url FROM tabel_dtl_tim_peserta, tabel_peserta WHERE tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST AND KD_TIM = '$tim' ORDER BY NAMA_PST ASC";
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
            }
            break;
        case "dataDetailAnggota":
            $kodeMahasiswa = $_POST['mahasiswa'];
            $sql = "SELECT CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NAMA_PST, tabel_asal.KD_ASAL, NAMA_ASAL, JK_PST, NOHP_PST, AGAMA_PST, ALAMAT_PST, DATE_FORMAT(TGL_MULAI_TIM,'%W, %d %M %Y') AS masuk, DATE_FORMAT(TGL_SELESAI_TIM,'%W, %d %M %Y') AS keluar, tabel_tim_peserta.KD_KAWAN, NAMA_KAWAN, EMAIL, PASSWORD, STATUS_PST, NAMA_TIM, tabel_tim_peserta.KD_TIM
                FROM tabel_peserta,tabel_asal,tabel_karyawan,tabel_akun,tabel_dtl_tim_peserta,tabel_tim_peserta
                WHERE tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
                AND tabel_peserta.KD_PST = '$kodeMahasiswa'
                GROUP BY tabel_peserta.KD_PST";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);
    
                    function encrypt_decrypt($action, $string)
                    {
                        $output = false;
                        $encrypt_method = "AES-256-CBC";
                        $secret_key = 'key_one';
                        $secret_iv = 'key_two';
                        // hash
                        $key = hash('sha256', $secret_key);
                        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
                        $iv = substr(hash('sha256', $secret_iv), 0, 16);
                        if ($action == 'encrypt') {
                            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                            $output = base64_encode($output);
                        } else if ($action == 'decrypt') {
                            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
                        }
                        return $output;
                    }
    
                    $data['PASSWORD'] = encrypt_decrypt('decrypt', $data['PASSWORD']);
                    $data['NAMA_PST'] =  ucwords($data['NAMA_PST']);
                    $data['NAMA_TIM'] =  ucwords($data['NAMA_TIM']);
                    $data['JK_PST'] =  ucwords($data['JK_PST']);
                    $data['AGAMA_PST'] =  ucwords($data['AGAMA_PST']);
                    $data['NAMA_ASAL'] =  ucwords($data['NAMA_ASAL']);
    
                    echo json_encode($data);    //data all
                    exit();
                }
            break;
    }
}

//konversi tgl ke bahasa indonesia
function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

//konfersi hari ke bahasa indonesia
function hari($tgl)
{
    $hari = $tgl;

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return $hari_ini;
}
