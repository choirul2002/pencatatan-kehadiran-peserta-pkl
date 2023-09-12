<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_jabatan;
use App\Models\Model_peserta;
use App\Models\Model_akun;
use App\Models\Model_konfigurasi;
use App\Models\Model_tim_peserta;
use Dompdf\Dompdf;

class Karyawan extends BaseController
{
    //halaman utama karyawan
    public function index()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $list                   = $konf_kry->listening();
            $konfigurasi            = $konf_kon->listening();
            $jumlah                 = $konf_kry->jumlah_karyawan();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $konf_tim               = new Model_tim_peserta();
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));

            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            // konversi data
            for ($i = 0; $i < count($mhs_masa_habis); $i++) {
                $mulai = new \DateTime($mhs_masa_habis[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $mulai->format('D');
                $mhs_masa_habis[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $mhs_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
            }


            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['PASSWORD'] = encrypt_decrypt('decrypt', $list[$i]['PASSWORD']);
            }

            $karyawan[1] = $list;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;

            $data = [
                'title'             => 'Karyawan (' . $jumlah->jumlah . ')',
                'layout'            => 'Karyawan',
                'sub_layout'        => 'Data Karyawan',
                'menu_active'       => 'karyawan',
                'submenu_active'    => 'data_karyawan',
                'database'          => $karyawan,
                'content'           => 'admin/karyawan/index'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //halaman tambah data
    public function tambah()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_jbtn              = new Model_jabatan();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();

            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $jumlah                 = $konf_kry->jumlah_karyawan();
            $jabatan                = $konf_jbtn->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $konfigurasi            = $konf_kon->listening();
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));

            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            // konversi data
            for ($i = 0; $i < count($mhs_masa_habis); $i++) {
                $mulai = new \DateTime($mhs_masa_habis[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $mulai->format('D');
                $mhs_masa_habis[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $mhs_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
            }



            $karyawan[1] = $jabatan;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;

            $data = [
                'title'             => 'Karyawan (' . $jumlah->jumlah . ')',
                'layout'            => 'Karyawan',
                'sub_layout'        => 'Tambah Karyawan',
                'menu_active'       => 'karyawan',
                'submenu_active'    => 'tambah_karyawan',
                'database'          => $karyawan,
                'content'           => 'admin/karyawan/tambah'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //simpan tambah data
    public function simpan()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_akn               = new Model_akun();
            $cek_email               = $konf_akn->cek_email($this->request->getPost('email'));
            $cek_nip               = $konf_kry->cek_nip($this->request->getPost('nip'));
            $max_kode_kry               = $konf_kry->max_kode_karyawan();
            $max_kode_akn               = $konf_akn->max_kode_akun();
            $kode_kry_baru  = "";
            $kode_akn_baru  = "";
            $data = [];
            $data2 = [];

            if($cek_email){
                session()->setFlashdata('flash', 'email');
                return redirect()->to(base_url('akrt'));
            }else{
                if($cek_nip){
                    if($cek_nip[0]['NIP_KAWAN'] == ""){
                        if ($max_kode_kry->KD_KAWAN) {
                            $kode_kry       = substr($max_kode_kry->KD_KAWAN, 2) + 1;
                            
                            if ($kode_kry < 10) {
                                $kode_kry_baru = "KY00000" . $kode_kry;
                            } else if ($kode_kry >= 10 && $kode_kry < 100) {
                                $kode_kry_baru = "KY0000" . $kode_kry;
                            } else if ($kode_kry >= 100 && $kode_kry < 1000) {
                                $kode_kry_baru = "KY000" . $kode_kry;
                            } else if ($kode_kry >= 1000 && $kode_kry < 10000) {
                                $kode_kry_baru = "KY00" . $kode_kry;
                            } else if ($kode_kry >= 10000 && $kode_kry < 100000) {
                                $kode_kry_baru = "KY0" . $kode_kry;
                            } else if ($kode_kry >= 100000 && $kode_kry < 1000000) {
                                $kode_kry_baru = "KY" . $kode_kry;
                            }
                        }
            
                        if ($max_kode_akn->KD_AKUN) {
                            $kode_akn       = substr($max_kode_akn->KD_AKUN, 2) + 1;
            
                            if ($kode_akn < 10) {
                                $kode_akn_baru = "AK00000" . $kode_akn;
                            } else if ($kode_akn >= 10 && $kode_akn < 100) {
                                $kode_akn_baru = "AK0000" . $kode_akn;
                            } else if ($kode_akn >= 100 && $kode_akn < 1000) {
                                $kode_akn_baru = "AK000" . $kode_akn;
                            } else if ($kode_akn >= 1000 && $kode_akn < 10000) {
                                $kode_akn_baru = "AK00" . $kode_akn;
                            } else if ($kode_akn >= 10000 && $kode_akn < 100000) {
                                $kode_akn_baru = "AK0" . $kode_akn;
                            } else if ($kode_akn >= 100000 && $kode_akn < 1000000) {
                                $kode_akn_baru = "AK" . $kode_akn;
                            }
                        }
            
                        $potong_kata = substr($this->request->getPost('email'), 0, 5) . rand(1, 10000);
                        $password = encrypt_decrypt('encrypt', $potong_kata);
            
                        if ($max_kode_akn->KD_AKUN) {
                            $data2 = [
                                'KD_AKUN'       => $kode_akn_baru,
                                'EMAIL'         => $this->request->getPost('email'),
                                'PASSWORD'      => $password,
                                'LEVEL'         => $this->request->getPost('level')
                            ];
                        } else {
                            $data2 = [
                                'KD_AKUN'       => 'AK000001',
                                'EMAIL'         => $this->request->getPost('email'),
                                'PASSWORD'      => $password,
                                'LEVEL'         => $this->request->getPost('level')
                            ];
                        }
            
                        if ($max_kode_kry->KD_KAWAN) {
                            $data = [
                                'KD_KAWAN'            => $kode_kry_baru,
                                'KD_AKUN'             => $kode_akn_baru,
                                'KD_JBTN'             => $this->request->getPost('jabatan'),
                                'NIP_KAWAN'             => $this->request->getPost('nip'),
                                'NAMA_KAWAN'          => $this->request->getPost('karyawan'),
                                'JK_KAWAN'            => $this->request->getPost('kelamin'),
                                'AGAMA_KAWAN'         => $this->request->getPost('agama'),
                                'ALAMAT_KAWAN'        => $this->request->getPost('alamat'),
                                'NOHP_KAWAN'          => $this->request->getPost('wa'),
                                'FOTO_KAWAN'          => 'profil.png',
                            ];
                        } else {
                            $data = [
                                'KD_KAWAN'            => 'KY000001',
                                'KD_AKUN'             => $kode_akn_baru,
                                'KD_JBTN'             => $this->request->getPost('jabatan'),
                                'NIP_KAWAN'             => $this->request->getPost('nip'),
                                'NAMA_KAWAN'          => $this->request->getPost('karyawan'),
                                'JK_KAWAN'            => $this->request->getPost('kelamin'),
                                'AGAMA_KAWAN'         => $this->request->getPost('agama'),
                                'ALAMAT_KAWAN'        => $this->request->getPost('alamat'),
                                'NOHP_KAWAN'          => $this->request->getPost('wa'),
                                'FOTO_KAWAN'          => 'profil.png',
                            ];
                        }
            
                        $konf_akn->tambah_data_akun($data2);
                        $konf_kry->tambah_data_karyawan($data);
            
                        session()->setFlashdata('flash', 'berhasil');
                        return redirect()->to(base_url('akr'));
                    }else{
                        session()->setFlashdata('flash', 'nip');
                        return redirect()->to(base_url('akrt'));
                    }
                }else{
                    if ($max_kode_kry->KD_KAWAN) {
                        $kode_kry       = substr($max_kode_kry->KD_KAWAN, 2) + 1;
                        
                        if ($kode_kry < 10) {
                            $kode_kry_baru = "KY00000" . $kode_kry;
                        } else if ($kode_kry >= 10 && $kode_kry < 100) {
                            $kode_kry_baru = "KY0000" . $kode_kry;
                        } else if ($kode_kry >= 100 && $kode_kry < 1000) {
                            $kode_kry_baru = "KY000" . $kode_kry;
                        } else if ($kode_kry >= 1000 && $kode_kry < 10000) {
                            $kode_kry_baru = "KY00" . $kode_kry;
                        } else if ($kode_kry >= 10000 && $kode_kry < 100000) {
                            $kode_kry_baru = "KY0" . $kode_kry;
                        } else if ($kode_kry >= 100000 && $kode_kry < 1000000) {
                            $kode_kry_baru = "KY" . $kode_kry;
                        }
                    }
        
                    if ($max_kode_akn->KD_AKUN) {
                        $kode_akn       = substr($max_kode_akn->KD_AKUN, 2) + 1;
        
                        if ($kode_akn < 10) {
                            $kode_akn_baru = "AK00000" . $kode_akn;
                        } else if ($kode_akn >= 10 && $kode_akn < 100) {
                            $kode_akn_baru = "AK0000" . $kode_akn;
                        } else if ($kode_akn >= 100 && $kode_akn < 1000) {
                            $kode_akn_baru = "AK000" . $kode_akn;
                        } else if ($kode_akn >= 1000 && $kode_akn < 10000) {
                            $kode_akn_baru = "AK00" . $kode_akn;
                        } else if ($kode_akn >= 10000 && $kode_akn < 100000) {
                            $kode_akn_baru = "AK0" . $kode_akn;
                        } else if ($kode_akn >= 100000 && $kode_akn < 1000000) {
                            $kode_akn_baru = "AK" . $kode_akn;
                        }
                    }
        
                    $potong_kata = substr($this->request->getPost('email'), 0, 5) . rand(1, 10000);
                    $password = encrypt_decrypt('encrypt', $potong_kata);
        
                    if ($max_kode_akn->KD_AKUN) {
                        $data2 = [
                            'KD_AKUN'       => $kode_akn_baru,
                            'EMAIL'         => $this->request->getPost('email'),
                            'PASSWORD'      => $password,
                            'LEVEL'         => $this->request->getPost('level')
                        ];
                    } else {
                        $data2 = [
                            'KD_AKUN'       => 'AK000001',
                            'EMAIL'         => $this->request->getPost('email'),
                            'PASSWORD'      => $password,
                            'LEVEL'         => $this->request->getPost('level')
                        ];
                    }
        
                    if ($max_kode_kry->KD_KAWAN) {
                        $data = [
                            'KD_KAWAN'            => $kode_kry_baru,
                            'KD_AKUN'             => $kode_akn_baru,
                            'KD_JBTN'             => $this->request->getPost('jabatan'),
                            'NIP_KAWAN'             => $this->request->getPost('nip'),
                            'NAMA_KAWAN'          => $this->request->getPost('karyawan'),
                            'JK_KAWAN'            => $this->request->getPost('kelamin'),
                            'AGAMA_KAWAN'         => $this->request->getPost('agama'),
                            'ALAMAT_KAWAN'        => $this->request->getPost('alamat'),
                            'NOHP_KAWAN'          => $this->request->getPost('wa'),
                            'FOTO_KAWAN'          => 'profil.png',
                        ];
                    } else {
                        $data = [
                            'KD_KAWAN'            => 'KY000001',
                            'KD_AKUN'             => $kode_akn_baru,
                            'KD_JBTN'             => $this->request->getPost('jabatan'),
                            'NIP_KAWAN'             => $this->request->getPost('nip'),
                            'NAMA_KAWAN'          => $this->request->getPost('karyawan'),
                            'JK_KAWAN'            => $this->request->getPost('kelamin'),
                            'AGAMA_KAWAN'         => $this->request->getPost('agama'),
                            'ALAMAT_KAWAN'        => $this->request->getPost('alamat'),
                            'NOHP_KAWAN'          => $this->request->getPost('wa'),
                            'FOTO_KAWAN'          => 'profil.png',
                        ];
                    }
        
                    $konf_akn->tambah_data_akun($data2);
                    $konf_kry->tambah_data_karyawan($data);
        
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('akr'));
                }
            }
        }
    }

    //halaman edit data
    public function edit()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_jbtn              = new Model_jabatan();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();


            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $kar                    = $konf_kry->filter_kd_karyawan($_GET['id']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $jabatan                = $konf_jbtn->listening();
            $konfigurasi            = $konf_kon->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));

            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            // konversi data
            for ($i = 0; $i < count($mhs_masa_habis); $i++) {
                $mulai = new \DateTime($mhs_masa_habis[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $mulai->format('D');
                $mhs_masa_habis[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $mhs_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            $kar[0]['PASSWORD'] = encrypt_decrypt('decrypt', $kar[0]['PASSWORD']);



            $karyawan[1] = $kar;
            $karyawan[2] = $jabatan;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;

            $data = [
                'title'             => ucwords($kar[0]['NAMA_KAWAN']),
                'layout'            => 'Karyawan',
                'sub_layout'        => 'Edit Karyawan',
                'menu_active'       => 'karyawan',
                'submenu_active'    => 'edith_karyawan',
                'database'          => $karyawan,
                'content'           => 'admin/karyawan/edit'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //simpan edit data
    public function simpanedit()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_akn               = new Model_akun();
            $cek_email               = $konf_akn->cek_email($this->request->getPost('email'));
            $cek_nip               = $konf_kry->cek_nip($this->request->getPost('nip'));
            $karyawan               = $konf_kry->filter_kd_karyawan($this->request->getPost('kode'));
            $password = encrypt_decrypt('encrypt', $this->request->getPost('password'));

            if($cek_email){
                if($cek_email[0]['KD_AKUN'] == $karyawan[0]['KD_AKUN']){
                    if($cek_nip){
                        if($this->request->getPost('nip') == $karyawan[0]['NIP_KAWAN']){
                            $data = [
                                'KD_KAWAN'                  => $this->request->getPost('kode'),
                                'NIP_KAWAN'             => $this->request->getPost('nip'),
                                'NAMA_KAWAN'                => $this->request->getPost('karyawan'),
                                'NOHP_KAWAN'                => $this->request->getPost('wa'),
                                'JK_KAWAN'                  => $this->request->getPost('kelamin'),
                                'KD_JBTN'                   => $this->request->getPost('jabatan'),
                                'AGAMA_KAWAN'               => $this->request->getPost('agama'),
                                'ALAMAT_KAWAN'              => $this->request->getPost('alamat'),
                            ];
                
                            $data2 = [
                                'KD_AKUN'               => $karyawan[0]['KD_AKUN'],
                                'EMAIL'                 => $this->request->getPost('email'),
                                'PASSWORD'              => $password,
                                'LEVEL'                 => $this->request->getPost('level'),
                            ];
                
                            $konf_kry->edit_data_karyawan($data);
                            $konf_akn->edit_akun($data2);
                
                            session()->setFlashdata('flash', 'berhasil');
                            return redirect()->to(base_url('akr'));
                        }else{
                            if($cek_nip[0]['NIP_KAWAN'] == ""){
                                $data = [
                                    'KD_KAWAN'                  => $this->request->getPost('kode'),
                                    'NIP_KAWAN'             => $this->request->getPost('nip'),
                                    'NAMA_KAWAN'                => $this->request->getPost('karyawan'),
                                    'NOHP_KAWAN'                => $this->request->getPost('wa'),
                                    'JK_KAWAN'                  => $this->request->getPost('kelamin'),
                                    'KD_JBTN'                   => $this->request->getPost('jabatan'),
                                    'AGAMA_KAWAN'               => $this->request->getPost('agama'),
                                    'ALAMAT_KAWAN'              => $this->request->getPost('alamat'),
                                ];
                    
                                $data2 = [
                                    'KD_AKUN'               => $karyawan[0]['KD_AKUN'],
                                    'EMAIL'                 => $this->request->getPost('email'),
                                    'PASSWORD'              => $password,
                                    'LEVEL'                 => $this->request->getPost('level'),
                                ];
                    
                                $konf_kry->edit_data_karyawan($data);
                                $konf_akn->edit_akun($data2);
                    
                                session()->setFlashdata('flash', 'berhasil');
                                return redirect()->to(base_url('akr'));
                            }else{
                                session()->setFlashdata('flash', 'nip');
                                echo "<script>javascript:history.go(-1)</script>";
                            }
                        }
                    }else{
                        $data = [
                            'KD_KAWAN'                  => $this->request->getPost('kode'),
                            'NIP_KAWAN'             => $this->request->getPost('nip'),
                            'NAMA_KAWAN'                => $this->request->getPost('karyawan'),
                            'NOHP_KAWAN'                => $this->request->getPost('wa'),
                            'JK_KAWAN'                  => $this->request->getPost('kelamin'),
                            'KD_JBTN'                   => $this->request->getPost('jabatan'),
                            'AGAMA_KAWAN'               => $this->request->getPost('agama'),
                            'ALAMAT_KAWAN'              => $this->request->getPost('alamat'),
                        ];
            
                        $data2 = [
                            'KD_AKUN'               => $karyawan[0]['KD_AKUN'],
                            'EMAIL'                 => $this->request->getPost('email'),
                            'PASSWORD'              => $password,
                            'LEVEL'                 => $this->request->getPost('level'),
                        ];
            
                        $konf_kry->edit_data_karyawan($data);
                        $konf_akn->edit_akun($data2);
            
                        session()->setFlashdata('flash', 'berhasil');
                        return redirect()->to(base_url('akr'));
                    }
                }else{
                    session()->setFlashdata('flash', 'email');
                    echo "<script>javascript:history.go(-1)</script>";
                }
            }else{
                $data = [
                    'KD_KAWAN'                  => $this->request->getPost('kode'),
                    'NIP_KAWAN'             => $this->request->getPost('nip'),
                    'NAMA_KAWAN'                => $this->request->getPost('karyawan'),
                    'NOHP_KAWAN'                => $this->request->getPost('wa'),
                    'JK_KAWAN'                  => $this->request->getPost('kelamin'),
                    'KD_JBTN'                   => $this->request->getPost('jabatan'),
                    'AGAMA_KAWAN'               => $this->request->getPost('agama'),
                    'ALAMAT_KAWAN'              => $this->request->getPost('alamat'),
                ];
    
                $data2 = [
                    'KD_AKUN'               => $karyawan[0]['KD_AKUN'],
                    'EMAIL'                 => $this->request->getPost('email'),
                    'PASSWORD'              => $password,
                    'LEVEL'                 => $this->request->getPost('level'),
                ];
    
                $konf_kry->edit_data_karyawan($data);
                $konf_akn->edit_akun($data2);
    
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('akr'));
            }
        }
    }

    //hapus data
    public function hapus()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_akn               = new Model_akun();
            $karyawan               = $konf_kry->filter_kd_karyawan($this->request->getGet('id'));
            $data_tidak_terelasi_tim_mahasiswa = $konf_kry->cek_data_relasi_tim_mahasiswa($this->request->getGet('id'));
            $adm               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);

            if($karyawan[0]['KD_AKUN'] == $adm[0]['KD_AKUN']){
                session()->setFlashdata('flash', 'aktif');
                return redirect()->to(base_url('akr'));
            }else{
                if($data_tidak_terelasi_tim_mahasiswa){
                    $data = [
                        'KD_AKUN'      => $karyawan[0]['KD_AKUN']
                    ];
        
                    $konf_kry->hapus_data_karyawan($data);
                    $konf_akn->hapus_data_akun($data);
        
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('akr'));
                }else{
                    session()->setFlashdata('flash', 'terelasi');
                    return redirect()->to(base_url('akr'));
                }
            }

        }
    }

    //generate data pdf
    public function generate()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $dompdf = new Dompdf();
            $konf_kry               = new Model_karyawan();
            $konf_kon               = new Model_konfigurasi();
            $konfigurasi            = $konf_kon->listening();
            $list                   = $konf_kry->listening();
            $title = "";

            if($this->request->getPost('level') == "all"){
                $list                = $konf_kry->generate_all();
                $title = "All";
            }else{
                $list                = $konf_kry->generate_person($this->request->getPost('level'));
                $title = ucwords($this->request->getPost('level'));
            }

            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['PASSWORD'] = encrypt_decrypt('decrypt', $list[$i]['PASSWORD']);
            }

            $karyawan[1] = $list;
            $karyawan[20] = $konfigurasi;

            $data = [
                'title'             => $title,
                'database'          => $karyawan,
            ];

            if($this->request->getPost('generate')[0] == "pdf"){
                date_default_timezone_set('Asia/Jakarta');
                $nama_file = $this->request->getPost('bulan').$this->request->getPost('tahun').'-'.date('Ymdhis').'- Karyawan';
                $html = view('admin/karyawan/cetak', $data);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'potrait');
                $dompdf->set_option('isRemoteEnabled', true);
                $dompdf->render();
                $dompdf->stream($nama_file.'.pdf');
            }else{
                return view('admin/karyawan/cetak', $data);
            }
        }
    }

    //menampilkan foto karyawan
    public function viewKaryawan()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $data = [
                'foto'          => $_GET['foto']
            ];

            return view('admin/karyawan/foto', $data);
        }
    }

    public function viewPembimbing()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $karyawan               = $konf_kry->filter_kd_karyawan($this->request->getGet('id'));

            $data = [
                'pembimbing'          => $karyawan
            ];

            return view('admin/karyawan/pembimbing', $data);
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
