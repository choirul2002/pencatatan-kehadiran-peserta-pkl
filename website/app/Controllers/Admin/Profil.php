<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_jabatan;
use App\Models\Model_peserta;
use App\Models\Model_akun;
use App\Models\Model_konfigurasi;
use App\Models\Model_tim_peserta;

class Profil extends BaseController
{
    
    public function index()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tim               = new Model_tim_peserta();
            $konf_kry               = new Model_karyawan();
            $konf_jbtn              = new Model_jabatan();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jabatan                = $konf_jbtn->listening();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $konfigurasi            = $konf_kon->listening();
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));

            //konversi password
            $karyawan[0]['PASSWORD'] = encrypt_decrypt('decrypt', $karyawan[0]['PASSWORD']);

            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            // konversi data
            for ($i = 0; $i < count($mhs_masa_habis); $i++) {
                $mulai = new \DateTime($mhs_masa_habis[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $mulai->format('D');
                $mhs_masa_habis[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $mhs_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
            }
            

            $karyawan[1]  = $jabatan;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $data = [
                'title'             => $karyawan[0]['NAMA_KAWAN'],
                'layout'            => 'Edit Profil',
                'sub_layout'        => '',
                'menu_active'       => 'edit profil',
                'submenu_active'    => '-',
                'database'          => $karyawan,
                'content'           => 'admin/profil/index'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //simpan edit data
    public function simpan()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_akn               = new Model_akun();
            $cek_email              = $konf_akn->cek_email($this->request->getPost('email'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $password = encrypt_decrypt('encrypt', $this->request->getPost('password'));

            if($cek_email){
                if($cek_email[0]['KD_AKUN'] == $karyawan[0]['KD_AKUN']){
                    if (!$_FILES['foto']['name']) {
                        $data = [
                            'KD_KAWAN'              => $_SESSION['akun_admin'],
                            'NAMA_KAWAN'            => $this->request->getPost('karyawan'),
                            'NOHP_KAWAN'            => $this->request->getPost('wa'),
                            'JK_KAWAN'              => $this->request->getPost('kelamin'),
                            'KD_JBTN'               => $this->request->getPost('jabatan'),
                            'AGAMA_KAWAN'           => $this->request->getPost('agama'),
                            'ALAMAT_KAWAN'          => $this->request->getPost('alamat')
                        ];
        
                        $data2 = [
                            'KD_AKUN'               => $karyawan[0]['KD_AKUN'],
                            'EMAIL'                 => $this->request->getPost('email'),
                            'PASSWORD'              => $password
                        ];
        
                        $konf_kry->edit_profil($data);
                        $konf_akn->edit_akun($data2);
        
                        session()->setFlashdata('flash', 'berhasil');
                        return redirect()->to(base_url('p'));
                    } else {
        
                        $profil         = $this->request->getFile('foto');
        
                        if ($karyawan[0]['FOTO_KAWAN'] == "profil.png") {
                            $profil->move(ROOTPATH . 'public/service/profil');
                        } else {
                            if (file_exists("service/profil/" . $karyawan[0]['FOTO_KAWAN'])) {
                                unlink("service/profil/" . $karyawan[0]['FOTO_KAWAN']);
                            }
                            $profil->move(ROOTPATH . 'public/service/profil');
                        }
        
                        $data = [
                            'KD_KAWAN'              => $_SESSION['akun_admin'],
                            'NAMA_KAWAN'            => $this->request->getPost('karyawan'),
                            'NOHP_KAWAN'            => $this->request->getPost('wa'),
                            'JK_KAWAN'              => $this->request->getPost('kelamin'),
                            'KD_JBTN'               => $this->request->getPost('jabatan'),
                            'AGAMA_KAWAN'           => $this->request->getPost('agama'),
                            'ALAMAT_KAWAN'          => $this->request->getPost('alamat'),
                            'FOTO_KAWAN'            => $profil->getName()
                        ];
        
                        $data2 = [
                            'KD_AKUN'               => $karyawan[0]['KD_AKUN'],
                            'EMAIL'                 => $this->request->getPost('email'),
                            'PASSWORD'              => $password
                        ];
        
                        $konf_kry->edit_profil($data);
                        $konf_akn->edit_akun($data2);
        
                        session()->setFlashdata('flash', 'berhasil');
                        return redirect()->to(base_url('p'));
                    }
                }else{
                    session()->setFlashdata('flash', 'email');
                    echo "<script>javascript:history.go(-1)</script>";
                }
            }else{
                if (!$_FILES['foto']['name']) {
                    $data = [
                        'KD_KAWAN'              => $_SESSION['akun_admin'],
                        'NAMA_KAWAN'            => $this->request->getPost('karyawan'),
                        'NOHP_KAWAN'            => $this->request->getPost('wa'),
                        'JK_KAWAN'              => $this->request->getPost('kelamin'),
                        'KD_JBTN'               => $this->request->getPost('jabatan'),
                        'AGAMA_KAWAN'           => $this->request->getPost('agama'),
                        'ALAMAT_KAWAN'          => $this->request->getPost('alamat')
                    ];
    
                    $data2 = [
                        'KD_AKUN'               => $karyawan[0]['KD_AKUN'],
                        'EMAIL'                 => $this->request->getPost('email'),
                        'PASSWORD'              => $password
                    ];
    
                    $konf_kry->edit_profil($data);
                    $konf_akn->edit_akun($data2);
    
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('p'));
                } else {
    
                    $profil         = $this->request->getFile('foto');
    
                    if ($karyawan[0]['FOTO_KAWAN'] == "profil.png") {
                        $profil->move(ROOTPATH . 'public/service/profil');
                    } else {
                        if (file_exists("service/profil/" . $karyawan[0]['FOTO_KAWAN'])) {
                            unlink("service/profil/" . $karyawan[0]['FOTO_KAWAN']);
                        }
                        $profil->move(ROOTPATH . 'public/service/profil');
                    }
    
                    $data = [
                        'KD_KAWAN'              => $_SESSION['akun_admin'],
                        'NAMA_KAWAN'            => $this->request->getPost('karyawan'),
                        'NOHP_KAWAN'            => $this->request->getPost('wa'),
                        'JK_KAWAN'              => $this->request->getPost('kelamin'),
                        'KD_JBTN'               => $this->request->getPost('jabatan'),
                        'AGAMA_KAWAN'           => $this->request->getPost('agama'),
                        'ALAMAT_KAWAN'          => $this->request->getPost('alamat'),
                        'FOTO_KAWAN'            => $profil->getName()
                    ];
    
                    $data2 = [
                        'KD_AKUN'               => $karyawan[0]['KD_AKUN'],
                        'EMAIL'                 => $this->request->getPost('email'),
                        'PASSWORD'              => $password
                    ];
    
                    $konf_kry->edit_profil($data);
                    $konf_akn->edit_akun($data2);
    
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('p'));
                }
            }
        }
    }
}

//enkripsi password
function encrypt_decrypt($action, $string)
{
    $output = false;
    $encrypt_method                 = "AES-256-CBC";
    $secret_key                     = 'key_one';
    $secret_iv                      = 'key_two';
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
