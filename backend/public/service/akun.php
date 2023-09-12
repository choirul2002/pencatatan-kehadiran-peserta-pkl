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
        case "main":
            $sql = "SELECT KD_AKUN, LEVEL FROM tabel_akun WHERE KD_AKUN = '$akun'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);


                if($data['LEVEL'] == "peserta"){
                    $respon['respon'] = "1";

                    echo json_encode($respon);    //data all
                    exit();
                }else{
                    $respon['respon'] = "2";

                    echo json_encode($respon);    //data all
                    exit();
                }
            }
            break;
        case "home":
            $sql = "SELECT CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NAMA_PST, NAMA_ASAL, STATUS_PST 
            FROM tabel_peserta,tabel_asal,tabel_akun WHERE tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                $data['NAMA_PST'] = ucwords($data['NAMA_PST']);
                $data['NAMA_ASAL'] = ucwords($data['NAMA_ASAL']);

                echo json_encode($data);    //data all
                exit();
            }
            break;
        case "homePembimbing":
            $sql = "SELECT CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_KAWAN) AS url, NAMA_KAWAN
            FROM tabel_karyawan,tabel_akun WHERE tabel_karyawan.KD_AKUN = tabel_akun.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                $data['NAMA_KAWAN'] = ucwords($data['NAMA_KAWAN']);

                echo json_encode($data);    //data all
                exit();
            }
            break;
        case "view":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "SELECT CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NAMA_PST, tabel_asal.KD_ASAL, NAMA_ASAL, JK_PST, NOHP_PST, AGAMA_PST, ALAMAT_PST, TGL_MULAI_TIM AS masuk, TGL_SELESAI_TIM AS keluar, tabel_tim_peserta.KD_KAWAN, NAMA_KAWAN, EMAIL, PASSWORD, STATUS_PST, NAMA_TIM, tabel_tim_peserta.KD_TIM
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

                    $masuk = new \DateTime($data['masuk']);
                    $hari_masuk = $masuk->format('D');
                    $data['masuk'] = hari($hari_masuk) . ", " . tgl_indo($data['masuk']);

                    $keluar = new \DateTime($data['keluar']);
                    $hari_keluar = $keluar->format('D');
                    $data['keluar'] = hari($hari_keluar) . ", " . tgl_indo($data['keluar']);
    
                    echo json_encode($data);    //data all
                    exit();
                }
            }
            break;
        case "viewPembimbing":
                $sql = "SELECT *,CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_KAWAN) AS url FROM tabel_karyawan, tabel_akun, tabel_jabatan WHERE tabel_karyawan.KD_AKUN = tabel_akun.KD_AKUN AND tabel_jabatan.kd_jbtn = tabel_karyawan.kd_jbtn AND tabel_akun.KD_AKUN = '$akun'";
    
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
                    $data['JK_KAWAN'] =  ucfirst($data['JK_KAWAN']);
                    $data['AGAMA_KAWAN'] =  ucfirst($data['AGAMA_KAWAN']);
                    $data['NAMA_KAWAN'] =  ucwords($data['NAMA_KAWAN']);
                    $data['ALAMAT_KAWAN'] =  ucfirst($data['ALAMAT_KAWAN']);
    
                    echo json_encode($data);    //data all
                    exit();
                }
            break;
        case "viewInformasiPembimbing":
                $karyawan = $_POST['kd_kawan'];
                $sql = "SELECT *,CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_KAWAN) AS url FROM tabel_karyawan, tabel_akun WHERE tabel_karyawan.KD_AKUN = tabel_akun.KD_AKUN AND KD_KAWAN = '$karyawan'";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);

                    $data['JK_KAWAN'] =  ucfirst($data['JK_KAWAN']);
                    $data['AGAMA_KAWAN'] =  ucfirst($data['AGAMA_KAWAN']);
                    $data['NAMA_KAWAN'] =  ucwords($data['NAMA_KAWAN']);
                    $data['ALAMAT_KAWAN'] =  ucfirst($data['ALAMAT_KAWAN']);
    
                    echo json_encode($data);    //data all
                    exit();
                }
            break;
        case "viewInformasiAsal":
                $kampus = $_POST['kd_kmps'];
                $sql = "SELECT * FROM tabel_asal WHERE KD_ASAL = '$kampus'";
    
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = mysqli_fetch_assoc($result);

                    $data['NAMA_ASAL'] =  ucwords($data['NAMA_ASAL']);
    
                    echo json_encode($data);    //data all
                    exit();
                }
            break;
        case "edit":
            $mahasiswa = $_POST['mahasiswa'];
            $kelamin = $_POST['kelamin'];
            $handphone = $_POST['handphone'];
            $agama = $_POST['agama'];
            $alamat = $_POST['alamat'];
            $email = $_POST['email'];

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

            $password = encrypt_decrypt('encrypt', $_POST['password']);

            $imstr = $_POST['image'];
            $foto = $_POST['foto'];
            $path = "profil/";

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];


                if ($imstr == "") {
                    $sql = "UPDATE tabel_peserta NATURAL JOIN tabel_akun SET NAMA_PST = '$mahasiswa', JK_PST = '$kelamin', AGAMA_PST = '$agama', ALAMAT_PST = '$alamat', NOHP_PST = '$handphone', EMAIL = '$email', PASSWORD = '$password' WHERE KD_PST = '$kodeMahasiswa'";
    
                    $result1 = mysqli_query($conn, $sql);
                    if ($result1) {
                        $respon['respon'] = '1';
    
                        echo json_encode($respon);  //data terkirim
                        exit();
                    } else {
                        $respon['respon'] = '0';
    
                        echo json_encode($respon);  //kesalahan data
                        exit();
                    }
                } else {
                    $sql = "SELECT*FROM tabel_peserta WHERE KD_PST = '$kodeMahasiswa'";
                    $result = mysqli_query($conn, $sql);
                    $data = mysqli_fetch_assoc($result);
    
                    if ($data['FOTO_PST'] == "profil.png") {
                        $sql1 = "UPDATE tabel_peserta NATURAL JOIN tabel_akun SET NAMA_PST = '$mahasiswa', JK_PST = '$kelamin', AGAMA_PST = '$agama', ALAMAT_PST = '$alamat', NOHP_PST = '$handphone', FOTO_PST = '$foto', EMAIL = '$email', PASSWORD = '$password' WHERE KD_PST = '$kodeMahasiswa'";
                        $result1 = mysqli_query($conn, $sql1);
                        if ($result1) {
                            if (file_put_contents($path . $foto, base64_decode($imstr)) == false) {
                                $respon['respon'] = "0";
    
                                echo json_encode($respon);  //kesalahan data
                                exit();
                            } else {
                                $respon['respon'] = '1';
    
                                echo json_encode($respon);  //data terkirim
                                exit();
                            }
                        }
                    } else {
                        if (file_exists("profil/" . $data['FOTO_PST'])) {
                            unlink("profil/" . $data['FOTO_PST']);
                        }
    
                        $sql1 = "UPDATE tabel_peserta NATURAL JOIN tabel_akun SET NAMA_PST = '$mahasiswa', JK_PST = '$kelamin', AGAMA_PST = '$agama', ALAMAT_PST = '$alamat', NOHP_PST = '$handphone', FOTO_PST = '$foto', EMAIL = '$email', PASSWORD = '$password' WHERE KD_PST = '$kodeMahasiswa'";
                        $result1 = mysqli_query($conn, $sql1);
                        if ($result1) {
                            if (file_put_contents($path . $foto, base64_decode($imstr)) == false) {
                                $respon['respon'] = "0";
    
                                echo json_encode($respon);  //kesalahan data
                                exit();
                            } else {
                                $respon['respon'] = '1';
    
                                echo json_encode($respon);  //data terkirim
                                exit();
                            }
                        }
                    }
                }
            }
            break;
        case "editPembimbing":
            $karyawan = $_POST['karyawan'];
            $kelamin = $_POST['kelamin'];
            $handphone = $_POST['handphone'];
            $agama = $_POST['agama'];
            $alamat = $_POST['alamat'];
            $email = $_POST['email'];

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

            $password = encrypt_decrypt('encrypt', $_POST['password']);

            $imstr = $_POST['image'];
            $foto = $_POST['foto'];
            $path = "profil/";

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];


                if ($imstr == "") {
                    $sql = "UPDATE tabel_karyawan NATURAL JOIN tabel_akun SET NAMA_KAWAN = '$karyawan', JK_KAWAN = '$kelamin', AGAMA_KAWAN = '$agama', ALAMAT_KAWAN = '$alamat', NOHP_KAWAN = '$handphone', EMAIL = '$email', PASSWORD = '$password' WHERE KD_KAWAN = '$kodeKaryawan'";
    
                    $result1 = mysqli_query($conn, $sql);
                    if ($result1) {
                        $respon['respon'] = '1';
    
                        echo json_encode($respon);  //data terkirim
                        exit();
                    } else {
                        $respon['respon'] = '0';
    
                        echo json_encode($respon);  //kesalahan data
                        exit();
                    }
                } else {
                    $sql = "SELECT*FROM tabel_karyawan WHERE KD_KAWAN = '$kodeKaryawan'";
                    $result = mysqli_query($conn, $sql);
                    $data = mysqli_fetch_assoc($result);
    
                    if ($data['FOTO_KAWAN'] == "profil.png") {
                        $sql1 = "UPDATE tabel_karyawan NATURAL JOIN tabel_akun SET NAMA_KAWAN = '$karyawan', JK_KAWAN = '$kelamin', AGAMA_KAWAN = '$agama', ALAMAT_KAWAN = '$alamat', NOHP_KAWAN = '$handphone', FOTO_KAWAN = '$foto', EMAIL = '$email', PASSWORD = '$password' WHERE KD_KAWAN = '$kodeKaryawan'";
                        $result1 = mysqli_query($conn, $sql1);
                        if ($result1) {
                            if (file_put_contents($path . $foto, base64_decode($imstr)) == false) {
                                $respon['respon'] = "0";
    
                                echo json_encode($respon);  //kesalahan data
                                exit();
                            } else {
                                $respon['respon'] = '1';
    
                                echo json_encode($respon);  //data terkirim
                                exit();
                            }
                        }
                    } else {
                        if (file_exists("profil/" . $data['FOTO_KAWAN'])) {
                            unlink("profil/" . $data['FOTO_KAWAN']);
                        }
    
                        $sql1 = "UPDATE tabel_karyawan NATURAL JOIN tabel_akun SET NAMA_KAWAN = '$karyawan', JK_KAWAN = '$kelamin', AGAMA_KAWAN = '$agama', ALAMAT_KAWAN = '$alamat', NOHP_KAWAN = '$handphone', FOTO_KAWAN = '$foto', EMAIL = '$email', PASSWORD = '$password' WHERE KD_KAWAN = '$kodeKaryawan'";
                        $result1 = mysqli_query($conn, $sql1);
                        if ($result1) {
                            if (file_put_contents($path . $foto, base64_decode($imstr)) == false) {
                                $respon['respon'] = "0";
    
                                echo json_encode($respon);  //kesalahan data
                                exit();
                            } else {
                                $respon['respon'] = '1';
    
                                echo json_encode($respon);  //data terkirim
                                exit();
                            }
                        }
                    }
                }
            }
            break;
        case "homePeserta":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();
    
            $tanggal = date("Y-m-d");

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                date_default_timezone_set("Asia/Jakarta");
                date_default_timezone_get();
                $akunKaryawan = $dataAkun['KD_KAWAN'];
                $tanggal = date("Y-m-d");
                $data['peserta'] = 0;

                $hari = date("D");
                if($hari == "Sun" || $hari == "Sat"){
                    echo json_encode($data);
                    exit();
                }else{
                    $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                    $resultlibur = mysqli_query($conn, $libur);
                    if(mysqli_num_rows($resultlibur) > 0){    
                        echo json_encode($data);
                        exit();
                    }else{
                        $sql = "SELECT COUNT(*) as peserta
                        FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                        WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$tanggal' AND STATUS_SURAT != 'disapprove')
                        AND tabel_tim_peserta.KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                        AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                        AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                        AND STATUS_PST = 'aktif'
                        AND TGL_SELESAI_TIM >= '$tanggal'
                        AND KD_KAWAN = '$akunKaryawan'";
                        $result = mysqli_query($conn, $sql);
                        $dataPeserta = mysqli_fetch_assoc($result);
        
                        echo json_encode($dataPeserta);
                        exit();
                    }
                }

            }
            break;
        case "hapusFoto":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_peserta WHERE tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeMahasiswa = $dataAkun['KD_PST'];

                $sql = "SELECT*FROM tabel_peserta WHERE KD_PST = '$kodeMahasiswa'";
                $result = mysqli_query($conn, $sql);
                $data = mysqli_fetch_assoc($result);

                if($data['FOTO_PST'] == "profil.png"){
                    $sql = "UPDATE tabel_peserta NATURAL JOIN tabel_akun SET FOTO_PST = 'profil.png' WHERE KD_PST = '$kodeMahasiswa'";
                    
                    $result1 = mysqli_query($conn, $sql);
                    if ($result1) {
                        $respon['respon'] = '1';
    
                        echo json_encode($respon);  //data terkirim
                        exit();
                    } else {
                        $respon['respon'] = '0';
    
                        echo json_encode($respon);  //kesalahan data
                        exit();
                    }
                }else{
                    if (file_exists("profil/" . $data['FOTO_PST'])) {
                        unlink("profil/" . $data['FOTO_PST']);
                    }
    
                    $sql = "UPDATE tabel_peserta NATURAL JOIN tabel_akun SET FOTO_PST = 'profil.png' WHERE KD_PST = '$kodeMahasiswa'";
                    
                    $result1 = mysqli_query($conn, $sql);
                    if ($result1) {
                        $respon['respon'] = '1';
    
                        echo json_encode($respon);  //data terkirim
                        exit();
                    } else {
                        $respon['respon'] = '0';
    
                        echo json_encode($respon);  //kesalahan data
                        exit();
                    }
                }
            }
            break;
        case "hapusFotoPembimbing":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $kodeKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT*FROM tabel_karyawan WHERE KD_KAWAN = '$kodeKaryawan'";
                $result = mysqli_query($conn, $sql);
                $data = mysqli_fetch_assoc($result);

                if($data['FOTO_KAWAN'] == "profil.png"){
                    $sql = "UPDATE tabel_karyawan NATURAL JOIN tabel_akun SET FOTO_KAWAN = 'profil.png' WHERE KD_KAWAN = '$kodeKaryawan'";
                    
                    $result1 = mysqli_query($conn, $sql);
                    if ($result1) {
                        $respon['respon'] = '1';
    
                        echo json_encode($respon);  //data terkirim
                        exit();
                    } else {
                        $respon['respon'] = '0';
    
                        echo json_encode($respon);  //kesalahan data
                        exit();
                    }
                }else{
                    if (file_exists("profil/" . $data['FOTO_KAWAN'])) {
                        unlink("profil/" . $data['FOTO_KAWAN']);
                    }
    
                    $sql = "UPDATE tabel_karyawan NATURAL JOIN tabel_akun SET FOTO_KAWAN = 'profil.png' WHERE KD_KAWAN = '$kodeKaryawan'";
                    
                    $result1 = mysqli_query($conn, $sql);
                    if ($result1) {
                        $respon['respon'] = '1';
    
                        echo json_encode($respon);  //data terkirim
                        exit();
                    } else {
                        $respon['respon'] = '0';
    
                        echo json_encode($respon);  //kesalahan data
                        exit();
                    }
                }
            }
            break;
        case "viewKosong":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $sql = "SELECT tabel_peserta.KD_AKUN, NAMA_PST, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NOHP_PST, NAMA_ASAL, KATEGORI_ASAL
                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$tanggal' AND STATUS_SURAT != 'disapprove')
                AND tabel_tim_peserta.KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND STATUS_PST = 'aktif'
                AND TGL_SELESAI_TIM >= '$tanggal'
                AND KD_KAWAN = '$akunKaryawan'
                ORDER BY NAMA_PST ASC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                    $resultlibur = mysqli_query($conn, $libur);
                    if(mysqli_num_rows($resultlibur) > 0){
                        $data = array();
        
                        echo json_encode($data);
                        exit();
                    }else{
                        $data = array();
        
                        while ($frm = mysqli_fetch_assoc($result)) {
                            $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
                            $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
                            $frm['tanggal'] = date("d/m/y");
                            array_push($data, $frm);
                        }
        
                        echo json_encode($data);
                        exit();
                    }
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
        case "viewKosongKode":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];
                $mahasiswa = $_POST['kode'];
                
                $sql = "SELECT tabel_peserta.KD_AKUN, NAMA_PST, CONCAT('http://192.168.43.57/simaptapkl/public/service/profil/',FOTO_PST) AS url, NOHP_PST, NAMA_ASAL, KATEGORI_ASAL
                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta, tabel_asal
                WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$tanggal' AND STATUS_SURAT != 'disapprove')
                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                AND STATUS_PST = 'aktif'
                AND TGL_SELESAI_TIM >= '$tanggal'
                AND tabel_peserta.KD_PST = '$mahasiswa'
                AND KD_KAWAN = '$akunKaryawan'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $data = array();
    
                    while ($frm = mysqli_fetch_assoc($result)) {
                        $frm['NAMA_PST'] = ucwords($frm['NAMA_PST']);
                        $frm['NAMA_ASAL'] = ucwords($frm['NAMA_ASAL']);
                        $frm['tanggal'] = date("d/m/y");
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
        case "jumlahDataKosong":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];

                $libur = "SELECT*FROM tabel_libur_nasional WHERE TANGGAL_LBR = '$tanggal'";
                $resultlibur = mysqli_query($conn, $libur);
                if(mysqli_num_rows($resultlibur) > 0){
                    $respon['peserta'] = "0";

                    echo json_encode($respon);
                    exit();
                }else{
                    $sql = "SELECT COUNT(*) as peserta
                    FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                    WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$tanggal' AND STATUS_SURAT != 'disapprove')
                    AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                    AND tabel_tim_peserta.KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM > '$tanggal')
                    AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                    AND STATUS_PST = 'aktif'
                    AND TGL_SELESAI_TIM >= '$tanggal'
                    AND KD_KAWAN = '$akunKaryawan'";
                    $result = mysqli_query($conn, $sql);
                    $dataPeserta = mysqli_fetch_assoc($result);
    
                    echo json_encode($dataPeserta);
                    exit();
                }
            }
            break;
        case "jumlahDataKosongKode":
            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tanggal = date("Y-m-d");

            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];
                $mahasiswa = $_POST['kode'];

                $sql = "SELECT COUNT(*) as peserta
                FROM tabel_peserta, tabel_dtl_tim_peserta, tabel_tim_peserta
                WHERE tabel_peserta.KD_PST NOT IN (SELECT KD_PST FROM tabel_absensi WHERE TGL = '$tanggal' AND STATUS_SURAT != 'disapprove')
                AND tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST
                AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM
                AND STATUS_PST = 'aktif'
                AND TGL_SELESAI_TIM >= '$tanggal'
                AND tabel_peserta.KD_PST = '$mahasiswa'
                AND KD_KAWAN = '$akunKaryawan'";
                $result = mysqli_query($conn, $sql);
                $dataPeserta = mysqli_fetch_assoc($result);

                echo json_encode($dataPeserta);
                exit();
            }
            break;
        case "cekhari":
            $sqlAkun = "SELECT*FROM tabel_akun,tabel_karyawan WHERE tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN AND tabel_akun.KD_AKUN = '$akun'";
            $resultAkun = mysqli_query($conn, $sqlAkun);
            if(mysqli_num_rows($resultAkun) > 0){
                $dataAkun = mysqli_fetch_assoc($resultAkun);
                $akunKaryawan = $dataAkun['KD_KAWAN'];
                $tanggal = date("Y-m-d");

                $hari = date("D");
                if($hari == "Sun" || $hari == "Sat"){
                    $data['respon'] = 0;

                    echo json_encode($data);
                    exit();
                }else{
                    $data['respon'] = 1;
                    
                    echo json_encode($data);
                    exit();
                }
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
