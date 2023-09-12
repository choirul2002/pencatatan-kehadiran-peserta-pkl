<?php
$DB_NAME = "simaptapkl";
$DB_USER = "root";
$DB_PASS = "";
$DB_SERVER_LOC = "localhost";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = mysqli_connect($DB_SERVER_LOC, $DB_USER, $DB_PASS, $DB_NAME);
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

    $respon = array();
    $email = $_POST['email'];
    $password = encrypt_decrypt('encrypt', $_POST['password']);

    $sql = "SELECT KD_AKUN, LEVEL FROM tabel_akun WHERE EMAIL = '$email' AND PASSWORD = '$password'";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        $id = $data['KD_AKUN'];

        if ($data['LEVEL'] == "peserta") {
            $sqldata = "SELECT*FROM tabel_peserta NATURAL JOIN tabel_dtl_tim_peserta WHERE KD_AKUN = '$id'";

            $resultdata = mysqli_query($conn, $sqldata);
            if (mysqli_num_rows($resultdata) > 0) {
                $respon['respon'] = "1";    //login akun berLEVEL mahasiswa
                $respon['id'] = $data['KD_AKUN'];
    
                echo json_encode($respon);
                exit();
            }else{
                $respon['respon'] = "4";    //login akaun berLEVEL selain mahasiswa

                echo json_encode($respon);
                exit();
            }
        } else if($data['LEVEL'] == "karyawan"){
            $respon['respon'] = "2";    //login akaun berlevel selain mahasiswa
            $respon['id'] = $data['KD_AKUN'];

            echo json_encode($respon);
            exit();
        } else {
            $respon['respon'] = "3";    //login akaun berlevel selain mahasiswa

            echo json_encode($respon);
            exit();
        }
    } else {
        $respon['respon'] = "0"; //akun login tidak ada di sistem

        echo json_encode($respon);
        exit();
    }
}
