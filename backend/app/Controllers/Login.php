<?php

namespace App\Controllers;

use App\Models\Model_login;
use App\Models\Model_karyawan;
use App\Models\Model_konfigurasi;

class Login extends BaseController
{
    //halaman login
    public function index()
    {
        session();
        if (isset($_SESSION['akun_admin'])) {
            return redirect()->to(base_url('ad'));
        } else if (isset($_SESSION['akun_karyawan'])) {
            return redirect()->to(base_url('kd'));
        } else {
            $konf_kon               = new Model_konfigurasi();
            $konfigurasi            = $konf_kon->listening();

            $data = [
                'database'          => $konfigurasi,
            ];

            return view('login', $data);
        }
    }

    //proses validasi akun
    public function validasi()
    {
        $email = $this->request->getPost('email');
        $enc = $this->request->getPost('password');
        $password = encrypt_decrypt('encrypt', $enc);
        
        $konf_kon         = new Model_konfigurasi();
        $konf_lgn         = new Model_login();
        $login            = $konf_lgn->validasi($email, $password);
        $konfigurasi      = $konf_kon->listening();

        if ($login) {
            if ($login[0]['LEVEL'] == "admin") {
                $konf_kry         = new Model_karyawan();
                $karyawan         = $konf_kry->filter_kd_akun($login[0]['KD_AKUN']);

                session()->set('akun_admin', $karyawan[0]['KD_KAWAN']);
                session()->setFlashdata('flash', 'berhasil_login');
                return redirect()->to(base_url('ad'));
            }else{
                session()->setFlashdata('flash', 'tidak_ada_hak_akses');
                return redirect()->to(base_url('/'));
            }
        } else {
            session()->setFlashdata('flash', 'user_tidak_ada');
            return redirect()->to(base_url('/'));
        }
    }

    //destroy session
    public function destroy()
    {
        session()->destroy();
        // return redirect()->to(base_url('/login'));
        return redirect()->to(base_url('/'));
    }
}

//enkripsi password
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
