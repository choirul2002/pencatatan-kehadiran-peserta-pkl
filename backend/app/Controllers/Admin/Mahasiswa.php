<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_peserta;
use App\Models\Model_asal;
use App\Models\Model_akun;
use App\Models\Model_konfigurasi;
use App\Models\Model_dtl_tim_peserta;
use Dompdf\Dompdf;
use App\Models\Model_tim_peserta;
use Dompdf\Options;

class Mahasiswa extends BaseController
{
    //halaman utama mahasiswa
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
            $konf_tim               = new Model_tim_peserta();
            $konf_kmps              = new Model_asal();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $mahasiswa              = $konf_mhs->list_data(date('Y-m-d'));
            $konfigurasi            = $konf_kon->listening();
            $jumlah                 = $konf_mhs->jumlah_mahasiswa_pkl();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $kampus                 = $konf_kmps->listening();
            $tahun                  = $konf_mhs->tahun();
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            $detail                 = $konf_dtl->listening();
            // dd($mahasiswa);
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

            for ($i = 0; $i < count($mahasiswa); $i++) {
                $mahasiswa[$i]['PASSWORD'] = encrypt_decrypt('decrypt', $mahasiswa[$i]['PASSWORD']);
            }


            $arrayMhs = array();
            
            foreach ($mahasiswa as $mhs) {
                $found = false;
                foreach ($detail as $dtl) {
                    if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                        $found = true;
                        break;
                    }
                }
            
                if (!$found) {
                    $mhs['KELOMPOK'] = 'tidak';
                    $arrayMhs[] = $mhs;
                }
            }
            
            foreach ($mahasiswa as &$data1) {
                $found = false;
                foreach ($arrayMhs as $data2) {
                    if ($data1['KD_PST'] == $data2['KD_PST']) {
                        $found = true;
                        $data1 = $data2;
                        break;
                    }
                }

                if (!$found) {
                    $data1['KELOMPOK'] = 'iya';
                }
            }

            usort($mahasiswa, function($a, $b) {
                if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                    return -1; // $a sebelum $b
                } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                    return 1; // $a setelah $b
                } else {
                    return 0; // tidak ada perubahan urutan
                }
            });

            foreach($mahasiswa as &$mhs){
                $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                if(!$detail){
                    $mhs['STATUS_PST'] = "kosong";
                }else{
                    if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                        $mhs['STATUS_PST'] = "menunggu";
                    }
                }
            }

            usort($mahasiswa, function($a, $b) {
                $order = array(
                    'kosong' => 1,
                    'menunggu' => 2,
                    'aktif' => 3,
                    'tidak aktif' => 4
                );
            
                return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
            });

            $karyawan[1]  = $mahasiswa;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[21] = $kampus;
            $karyawan[22] = $tahun;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $data = [
                'title'             => 'Peserta PKL ( ' . $jumlah->jumlah . ' )',
                'layout'            => 'Peserta PKL',
                'sub_layout'        => 'Data Peserta',
                'menu_active'       => 'mahasiswa',
                'submenu_active'    => 'data_mahasiswa',
                'database'          => $karyawan,
                'content'           => 'admin/mahasiswa/index'
            ];

            return view('admin/layout/wrapper', $data);
            // dd($mhs_masa_habis);
        
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
            $konf_mhs               = new Model_peserta();
            $konf_kmps              = new Model_asal();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $jumlah                 = $konf_mhs->jumlah_mahasiswa_pkl();
            $kampus                 = $konf_kmps->listening();
            $konfigurasi            = $konf_kon->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));

            // konversi data
            for ($i = 0; $i < count($mhs_masa_habis); $i++) {
                $mulai = new \DateTime($mhs_masa_habis[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $mulai->format('D');
                $mhs_masa_habis[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $mhs_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
            }
            

                        // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            $karyawan[1] = $kampus;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $data = [
                'title'             => 'Peserta PKL ( ' . $jumlah->jumlah . ' )',
                'layout'            => 'Peserta PKL',
                'sub_layout'        => 'Tambah Peserta',
                'menu_active'       => 'mahasiswa',
                'submenu_active'    => 'tambah_mahasiswa',
                'database'          => $karyawan,
                'content'           => 'admin/mahasiswa/tambah'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //simpan data
    public function simpan()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_mhs               = new Model_peserta();
            $konf_akn               = new Model_akun();
            $max_kode_mhs               = $konf_mhs->max_kode_mahasiswa();
            $max_kode_akn               = $konf_akn->max_kode_akun();
            $cek_email               = $konf_akn->cek_email($this->request->getPost('email'));
            $kode_mhs_baru  = "";
            $kode_akn_baru  = "";
            $data = [];
            $data2 = [];

            if($cek_email){
                session()->setFlashdata('flash', 'email');
                return redirect()->to(base_url('amt'));
            }else{
                if ($max_kode_mhs->KD_PST) {
                    $kode_mhs       = substr($max_kode_mhs->KD_PST, 2) + 1;
    
                    if ($kode_mhs < 10) {
                        $kode_mhs_baru = "MH00000" . $kode_mhs;
                    } else if ($kode_mhs >= 10 && $kode_mhs < 100) {
                        $kode_mhs_baru = "MH0000" . $kode_mhs;
                    } else if ($kode_mhs >= 100 && $kode_mhs < 1000) {
                        $kode_mhs_baru = "MH000" . $kode_mhs;
                    } else if ($kode_mhs >= 1000 && $kode_mhs < 10000) {
                        $kode_mhs_baru = "MH00" . $kode_mhs;
                    } else if ($kode_mhs >= 10000 && $kode_mhs < 100000) {
                        $kode_mhs_baru = "MH0" . $kode_mhs;
                    } else if ($kode_mhs >= 100000 && $kode_mhs < 1000000) {
                        $kode_mhs_baru = "MH" . $kode_mhs;
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
                        'LEVEL'         => 'peserta'
                    ];
                } else {
                    $data2 = [
                        'KD_AKUN'       => 'AK000001',
                        'EMAIL'         => $this->request->getPost('email'),
                        'PASSWORD'      => $password,
                        'LEVEL'         => 'peserta'
                    ];
                }
    
                if ($max_kode_mhs->KD_PST) {
                    $data = [
                        'KD_PST'            => $kode_mhs_baru,
                        'KD_ASAL'           => $this->request->getPost('kampus'),
                        'KD_AKUN'           => $data2['KD_AKUN'],
                        'NAMA_PST'          => $this->request->getPost('mahasiswa'),
                        'JK_PST'            => $this->request->getPost('kelamin'),
                        'AGAMA_PST'         => $this->request->getPost('agama'),
                        'ALAMAT_PST'        => $this->request->getPost('alamat'),
                        'NOHP_PST'          => $this->request->getPost('wa'),
                        'TAHUN_PST'             => $this->request->getPost('tahun'),
                        'FOTO_pst'          => 'profil.png',
                        'STATUS_PST'        => 'aktif',
                    ];
                } else {
                    $data = [
                        'KD_PST'            => 'MH000001',
                        'KD_ASAL'           => $this->request->getPost('kampus'),
                        'KD_AKUN'           => $data2['KD_AKUN'],
                        'NAMA_PST'          => $this->request->getPost('mahasiswa'),
                        'JK_PST'            => $this->request->getPost('kelamin'),
                        'AGAMA_PST'         => $this->request->getPost('agama'),
                        'ALAMAT_PST'        => $this->request->getPost('alamat'),
                        'NOHP_PST'          => $this->request->getPost('wa'),
                        'TAHUN_PST'             => $this->request->getPost('tahun'),
                        'FOTO_pst'          => 'profil.png',
                        'STATUS_PST'        => 'aktif',
                    ];
                }
    
    
                $konf_akn->tambah_data_akun($data2);
                $konf_mhs->tambah_data_mahasiswa($data);
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('am'));
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
            $konf_kmps              = new Model_asal();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $mahasiswa              = $konf_mhs->filter_data_mahasiswa_edit($this->request->getGet('id'));
            $kampus                 = $konf_kmps->listening();
            $konfigurasi            = $konf_kon->listening();
            $detail                 = $konf_dtl->filter_detail1($this->request->getGet('id'));
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));

            // konversi data
            for ($i = 0; $i < count($mhs_masa_habis); $i++) {
                $mulai = new \DateTime($mhs_masa_habis[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $mulai->format('D');
                $mhs_masa_habis[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $mhs_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($mhs_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

                        // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            if($detail){
                if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                    $mahasiswa[0]['STATUS_PST'] = "Menunggu";
                }
            }else{
                $mahasiswa[0]['STATUS_PST'] = "-";
            }

            $mahasiswa[0]['PASSWORD'] = encrypt_decrypt('decrypt', $mahasiswa[0]['PASSWORD']);
            $karyawan[1] = $kampus;
            $karyawan[2] = $mahasiswa;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $data = [
                'title'             => ucwords($mahasiswa[0]['NAMA_PST']),
                'layout'            => 'Peserta PKL',
                'sub_layout'        => 'Edit Peserta',
                'menu_active'       => 'mahasiswa',
                'submenu_active'    => 'edit_mahasiswa',
                'database'          => $karyawan,
                'content'           => 'admin/mahasiswa/edit'
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
            $konf_mhs               = new Model_peserta();
            $konf_akn               = new Model_akun();
            $cek_email              = $konf_akn->cek_email($this->request->getPost('email'));
            $mahasiswa              = $konf_mhs->filter_data_mahasiswa_edit($this->request->getPost('kode'));
            $password = encrypt_decrypt('encrypt', $this->request->getPost('password'));

            if($cek_email){
                if($cek_email[0]['KD_AKUN'] == $mahasiswa[0]['KD_AKUN']){
                    $data = [
                        'KD_PST'                => $this->request->getPost('kode'),
                        'NAMA_PST'              => $this->request->getPost('mahasiswa'),
                        'JK_PST'                => $this->request->getPost('kelamin'),
                        'AGAMA_PST'             => $this->request->getPost('agama'),
                        'KD_ASAL'               => $this->request->getPost('kampus'),
                        'NOHP_PST'              => $this->request->getPost('wa'),
                        'ALAMAT_PST'            => $this->request->getPost('alamat'),
                        'TAHUN_PST'                 => $this->request->getPost('tahun')
                    ];
        
                    $data2 = [
                        'KD_AKUN'               => $mahasiswa[0]['KD_AKUN'],
                        'EMAIL'                 => $this->request->getPost('email'),
                        'PASSWORD'              => $password,
                    ];
        
                    $konf_mhs->edit_data_mahasiswa($data);
                    $konf_akn->edit_akun($data2);
        
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('am'));
                }else{
                    session()->setFlashdata('flash', 'email');
                    echo "<script>javascript:history.go(-1)</script>";
                }
            }else{
                $data = [
                    'KD_PST'                => $this->request->getPost('kode'),
                    'NAMA_PST'              => $this->request->getPost('mahasiswa'),
                    'JK_PST'                => $this->request->getPost('kelamin'),
                    'AGAMA_PST'             => $this->request->getPost('agama'),
                    'KD_ASAL'               => $this->request->getPost('kampus'),
                    'NOHP_PST'              => $this->request->getPost('wa'),
                    'ALAMAT_PST'            => $this->request->getPost('alamat'),
                    'TAHUN_PST'                 => $this->request->getPost('tahun')
                ];
    
                $data2 = [
                    'KD_AKUN'               => $mahasiswa[0]['KD_AKUN'],
                    'EMAIL'                 => $this->request->getPost('email'),
                    'PASSWORD'              => $password,
                ];
    
                $konf_mhs->edit_data_mahasiswa($data);
                $konf_akn->edit_akun($data2);
    
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('am'));
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
            $konf_mhs               = new Model_peserta();
            $konf_akn               = new Model_akun();
            $mahasiswa               = $konf_mhs->filter_data_mahasiswa_edit($this->request->getGet('id'));
            $data_tidak_terelasi_logpos  = $konf_mhs->cek_data_relasi_logpos($mahasiswa[0]['KD_AKUN']);
            $data_tidak_terelasi_absensi  = $konf_mhs->cek_data_relasi_absensi($mahasiswa[0]['KD_AKUN']);
            $data_tidak_terelasi_dtl_tim_mahasiswa  = $konf_mhs->cek_data_relasi_dtl_tim_mahasiswa($mahasiswa[0]['KD_AKUN']);

            if($data_tidak_terelasi_logpos && $data_tidak_terelasi_absensi && $data_tidak_terelasi_dtl_tim_mahasiswa){
                $data = [
                    'KD_AKUN'       => $mahasiswa[0]['KD_AKUN']
                ];
    
                $konf_akn->hapus_data_akun($data);
                $konf_mhs->hapus_data_mahasiswa($data);
    
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('am'));
            }else{
                session()->setFlashdata('flash', 'terelasi');
                return redirect()->to(base_url('am'));
            }
        }
    }

    //generate data pdf
    public function generate()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $dompdf = new Dompdf();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $konf_kmps              = new Model_asal();
            $konfigurasi            = $konf_kon->listening();
            $detail                 = $konf_dtl->listening();
            $kampus = "";

            if($this->request->getPost('kampus') == "all"){
                $kampus = "All";
            }else{
                $konf = $konf_kmps->filter_data_kampus($this->request->getPost('kampus'));
                $kampus = ucwords($konf[0]['NAMA_ASAL']);
            }

            if($this->request->getPost('kampus') == "all"){
                if($this->request->getPost('statusPKL') == "all"){
                    if($this->request->getPost('tahun') == "all"){
                        $mahasiswa = $konf_mhs->generate_all_all_all();
                        $arrayMhs = array();
                        
                        foreach ($mahasiswa as $mhs) {
                            $found = false;
                            foreach ($detail as $dtl) {
                                if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                    $found = true;
                                    break;
                                }
                            }
                        
                            if (!$found) {
                                $mhs['KELOMPOK'] = 'tidak';
                                $arrayMhs[] = $mhs;
                            }
                        }
                        
                        foreach ($mahasiswa as &$data1) {
                            $found = false;
                            foreach ($arrayMhs as $data2) {
                                if ($data1['KD_PST'] == $data2['KD_PST']) {
                                    $found = true;
                                    $data1 = $data2;
                                    break;
                                }
                            }
            
                            if (!$found) {
                                $data1['KELOMPOK'] = 'iya';
                            }
                        }
            
                        usort($mahasiswa, function($a, $b) {
                            if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                return -1; // $a sebelum $b
                            } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                return 1; // $a setelah $b
                            } else {
                                return 0; // tidak ada perubahan urutan
                            }
                        });
            
                        foreach($mahasiswa as &$mhs){
                            $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                            if(!$detail){
                                $mhs['STATUS_PST'] = "kosong";
                            }else{
                                if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                    $mhs['STATUS_PST'] = "menunggu";
                                }
                            }
                        }
            
                        usort($mahasiswa, function($a, $b) {
                            $order = array(
                                'kosong' => 1,
                                'menunggu' => 2,
                                'aktif' => 3,
                                'tidak aktif' => 4
                            );
                        
                            return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                        });
                    }else{
                        $mahasiswa = $konf_mhs->generate_all_all_person($this->request->getPost('tahun'));
                        $arrayMhs = array();
                        
                        foreach ($mahasiswa as $mhs) {
                            $found = false;
                            foreach ($detail as $dtl) {
                                if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                    $found = true;
                                    break;
                                }
                            }
                        
                            if (!$found) {
                                $mhs['KELOMPOK'] = 'tidak';
                                $arrayMhs[] = $mhs;
                            }
                        }
                        
                        foreach ($mahasiswa as &$data1) {
                            $found = false;
                            foreach ($arrayMhs as $data2) {
                                if ($data1['KD_PST'] == $data2['KD_PST']) {
                                    $found = true;
                                    $data1 = $data2;
                                    break;
                                }
                            }
            
                            if (!$found) {
                                $data1['KELOMPOK'] = 'iya';
                            }
                        }
            
                        usort($mahasiswa, function($a, $b) {
                            if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                return -1; // $a sebelum $b
                            } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                return 1; // $a setelah $b
                            } else {
                                return 0; // tidak ada perubahan urutan
                            }
                        });
            
                        foreach($mahasiswa as &$mhs){
                            $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                            if(!$detail){
                                $mhs['STATUS_PST'] = "kosong";
                            }else{
                                if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                    $mhs['STATUS_PST'] = "menunggu";
                                }
                            }
                        }
            
                        usort($mahasiswa, function($a, $b) {
                            $order = array(
                                'kosong' => 1,
                                'menunggu' => 2,
                                'aktif' => 3,
                                'tidak aktif' => 4
                            );
                        
                            return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                        });
                    }
                }else{
                    if($this->request->getPost('statusPKL') == "kosong"){
                        if($this->request->getPost('tahun') == "all"){
                            $mahasiswa = $konf_mhs->kosong_all_person_all();
                            foreach($mahasiswa as &$mhs){
                                $mhs['STATUS_PST'] = "Kosong";
                            }
                        }else{
                            $mahasiswa = $konf_mhs->kosong_all_person_person($this->request->getPost('tahun'));
                            foreach($mahasiswa as &$mhs){
                                $mhs['STATUS_PST'] = "Kosong";
                            }
                        }
                    }else if($this->request->getPost('statusPKL') == "menunggu"){
                        if($this->request->getPost('tahun') == "all"){
                            $mahasiswa = $konf_mhs->menunggu_all_person_all($this->request->getPost(date('Y-m-d')));
                            foreach($mahasiswa as &$mhs){
                                $mhs['STATUS_PST'] = "Menunggu";
                            }
                        }else{
                            $mahasiswa = $konf_mhs->menunggu_all_person_person(date('Y-m-d'),$this->request->getPost('tahun'));
                            foreach($mahasiswa as &$mhs){
                                $mhs['STATUS_PST'] = "Menunggu";
                            }
                        }
                    }else{
                        if($this->request->getPost('statusPKL') == "aktif"){
                            if($this->request->getPost('tahun') == "all"){
                                $mahasiswa = $konf_mhs->generate_all_person_all($this->request->getPost('statusPKL'));
                                $arrayMhs = array();
                                foreach ($mahasiswa as $mhs) {
                                    $found = false;
                                    foreach ($detail as $dtl) {
                                        if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                
                                    if (!$found) {
                                        $mhs['KELOMPOK'] = 'tidak';
                                        $arrayMhs[] = $mhs;
                                    }
                                }
                                
                                foreach ($mahasiswa as &$data1) {
                                    $found = false;
                                    foreach ($arrayMhs as $data2) {
                                        if ($data1['KD_PST'] == $data2['KD_PST']) {
                                            $found = true;
                                            $data1 = $data2;
                                            break;
                                        }
                                    }
                    
                                    if (!$found) {
                                        $data1['KELOMPOK'] = 'iya';
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                        return -1; // $a sebelum $b
                                    } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                        return 1; // $a setelah $b
                                    } else {
                                        return 0; // tidak ada perubahan urutan
                                    }
                                });
                    
                                foreach($mahasiswa as &$mhs){
                                    $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                                    if(!$detail){
                                        $mhs['STATUS_PST'] = "kosong";
                                    }else{
                                        if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                            $mhs['STATUS_PST'] = "menunggu";
                                        }
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    $order = array(
                                        'kosong' => 1,
                                        'menunggu' => 2,
                                        'aktif' => 3,
                                        'tidak aktif' => 4
                                    );
                                
                                    return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                                });
    
                                for ($i = 0; $i < count($mahasiswa); $i++) {
                                    if ($mahasiswa[$i]['STATUS_PST'] !== "aktif") {
                                        unset($mahasiswa[$i]);
                                    }
                                }
                                
                                $mahasiswa = array_values($mahasiswa);
                                
                            }else{
                                $mahasiswa = $konf_mhs->generate_all_person_person($this->request->getPost('statusPKL'),$this->request->getPost('tahun'));
                                $arrayMhs = array();
                                foreach ($mahasiswa as $mhs) {
                                    $found = false;
                                    foreach ($detail as $dtl) {
                                        if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                
                                    if (!$found) {
                                        $mhs['KELOMPOK'] = 'tidak';
                                        $arrayMhs[] = $mhs;
                                    }
                                }
                                
                                foreach ($mahasiswa as &$data1) {
                                    $found = false;
                                    foreach ($arrayMhs as $data2) {
                                        if ($data1['KD_PST'] == $data2['KD_PST']) {
                                            $found = true;
                                            $data1 = $data2;
                                            break;
                                        }
                                    }
                    
                                    if (!$found) {
                                        $data1['KELOMPOK'] = 'iya';
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                        return -1; // $a sebelum $b
                                    } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                        return 1; // $a setelah $b
                                    } else {
                                        return 0; // tidak ada perubahan urutan
                                    }
                                });
                    
                                foreach($mahasiswa as &$mhs){
                                    $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                                    if(!$detail){
                                        $mhs['STATUS_PST'] = "kosong";
                                    }else{
                                        if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                            $mhs['STATUS_PST'] = "menunggu";
                                        }
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    $order = array(
                                        'kosong' => 1,
                                        'menunggu' => 2,
                                        'aktif' => 3,
                                        'tidak aktif' => 4
                                    );
                                
                                    return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                                });

                                for ($i = 0; $i < count($mahasiswa); $i++) {
                                    if ($mahasiswa[$i]['STATUS_PST'] !== "aktif") {
                                        unset($mahasiswa[$i]);
                                    }
                                }
                                
                                $mahasiswa = array_values($mahasiswa);
                            }
                        }else{
                            if($this->request->getPost('tahun') == "all"){
                                $mahasiswa = $konf_mhs->generate_all_person_all($this->request->getPost('statusPKL'));
                                $arrayMhs = array();
                                foreach ($mahasiswa as $mhs) {
                                    $found = false;
                                    foreach ($detail as $dtl) {
                                        if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                
                                    if (!$found) {
                                        $mhs['KELOMPOK'] = 'tidak';
                                        $arrayMhs[] = $mhs;
                                    }
                                }
                                
                                foreach ($mahasiswa as &$data1) {
                                    $found = false;
                                    foreach ($arrayMhs as $data2) {
                                        if ($data1['KD_PST'] == $data2['KD_PST']) {
                                            $found = true;
                                            $data1 = $data2;
                                            break;
                                        }
                                    }
                    
                                    if (!$found) {
                                        $data1['KELOMPOK'] = 'iya';
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                        return -1; // $a sebelum $b
                                    } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                        return 1; // $a setelah $b
                                    } else {
                                        return 0; // tidak ada perubahan urutan
                                    }
                                });
                    
                                foreach($mahasiswa as &$mhs){
                                    $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                                    if(!$detail){
                                        $mhs['STATUS_PST'] = "kosong";
                                    }else{
                                        if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                            $mhs['STATUS_PST'] = "menunggu";
                                        }
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    $order = array(
                                        'kosong' => 1,
                                        'menunggu' => 2,
                                        'aktif' => 3,
                                        'tidak aktif' => 4
                                    );
                                
                                    return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                                });
                                
                            }else{
                                $mahasiswa = $konf_mhs->generate_all_person_person($this->request->getPost('statusPKL'),$this->request->getPost('tahun'));
                                $arrayMhs = array();
                                foreach ($mahasiswa as $mhs) {
                                    $found = false;
                                    foreach ($detail as $dtl) {
                                        if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                
                                    if (!$found) {
                                        $mhs['KELOMPOK'] = 'tidak';
                                        $arrayMhs[] = $mhs;
                                    }
                                }
                                
                                foreach ($mahasiswa as &$data1) {
                                    $found = false;
                                    foreach ($arrayMhs as $data2) {
                                        if ($data1['KD_PST'] == $data2['KD_PST']) {
                                            $found = true;
                                            $data1 = $data2;
                                            break;
                                        }
                                    }
                    
                                    if (!$found) {
                                        $data1['KELOMPOK'] = 'iya';
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                        return -1; // $a sebelum $b
                                    } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                        return 1; // $a setelah $b
                                    } else {
                                        return 0; // tidak ada perubahan urutan
                                    }
                                });
                    
                                foreach($mahasiswa as &$mhs){
                                    $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                                    if(!$detail){
                                        $mhs['STATUS_PST'] = "kosong";
                                    }else{
                                        if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                            $mhs['STATUS_PST'] = "menunggu";
                                        }
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    $order = array(
                                        'kosong' => 1,
                                        'menunggu' => 2,
                                        'aktif' => 3,
                                        'tidak aktif' => 4
                                    );
                                
                                    return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                                });
                            
                            }
                        }

                    }
                }
            }else{
                if($this->request->getPost('statusPKL') == "all"){
                    if($this->request->getPost('tahun') == "all"){
                        $mahasiswa = $konf_mhs->generate_person_all_all($this->request->getPost('kampus'));
                        $arrayMhs = array();
                        
                        foreach ($mahasiswa as $mhs) {
                            $found = false;
                            foreach ($detail as $dtl) {
                                if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                    $found = true;
                                    break;
                                }
                            }
                        
                            if (!$found) {
                                $mhs['KELOMPOK'] = 'tidak';
                                $arrayMhs[] = $mhs;
                            }
                        }
                        
                        foreach ($mahasiswa as &$data1) {
                            $found = false;
                            foreach ($arrayMhs as $data2) {
                                if ($data1['KD_PST'] == $data2['KD_PST']) {
                                    $found = true;
                                    $data1 = $data2;
                                    break;
                                }
                            }
            
                            if (!$found) {
                                $data1['KELOMPOK'] = 'iya';
                            }
                        }
            
                        usort($mahasiswa, function($a, $b) {
                            if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                return -1; // $a sebelum $b
                            } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                return 1; // $a setelah $b
                            } else {
                                return 0; // tidak ada perubahan urutan
                            }
                        });
            
                        foreach($mahasiswa as &$mhs){
                            $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                            if(!$detail){
                                $mhs['STATUS_PST'] = "kosong";
                            }else{
                                if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                    $mhs['STATUS_PST'] = "menunggu";
                                }
                            }
                        }
            
                        usort($mahasiswa, function($a, $b) {
                            $order = array(
                                'kosong' => 1,
                                'menunggu' => 2,
                                'aktif' => 3,
                                'tidak aktif' => 4
                            );
                        
                            return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                        });
                    }else{
                        $mahasiswa = $konf_mhs->generate_person_all_person($this->request->getPost('kampus'),$this->request->getPost('tahun'));
                        $arrayMhs = array();
                        
                        foreach ($mahasiswa as $mhs) {
                            $found = false;
                            foreach ($detail as $dtl) {
                                if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                    $found = true;
                                    break;
                                }
                            }
                        
                            if (!$found) {
                                $mhs['KELOMPOK'] = 'tidak';
                                $arrayMhs[] = $mhs;
                            }
                        }
                        
                        foreach ($mahasiswa as &$data1) {
                            $found = false;
                            foreach ($arrayMhs as $data2) {
                                if ($data1['KD_PST'] == $data2['KD_PST']) {
                                    $found = true;
                                    $data1 = $data2;
                                    break;
                                }
                            }
            
                            if (!$found) {
                                $data1['KELOMPOK'] = 'iya';
                            }
                        }
            
                        usort($mahasiswa, function($a, $b) {
                            if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                return -1; // $a sebelum $b
                            } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                return 1; // $a setelah $b
                            } else {
                                return 0; // tidak ada perubahan urutan
                            }
                        });
            
                        foreach($mahasiswa as &$mhs){
                            $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                            if(!$detail){
                                $mhs['STATUS_PST'] = "kosong";
                            }else{
                                if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                    $mhs['STATUS_PST'] = "menunggu";
                                }
                            }
                        }
            
                        usort($mahasiswa, function($a, $b) {
                            $order = array(
                                'kosong' => 1,
                                'menunggu' => 2,
                                'aktif' => 3,
                                'tidak aktif' => 4
                            );
                        
                            return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                        });
                    }
                }else{
                    if($this->request->getPost('statusPKL') == "kosong"){
                        if($this->request->getPost('tahun') == "all"){
                            $mahasiswa = $konf_mhs->kosong_person_person_all($this->request->getPost('kampus'));
                            foreach($mahasiswa as &$mhs){
                                $mhs['STATUS_PST'] = "Kosong";
                            }
                        }else{
                            $mahasiswa = $konf_mhs->kosong_person_person_person($this->request->getPost('kampus'),$this->request->getPost('tahun'));
                            foreach($mahasiswa as &$mhs){
                                $mhs['STATUS_PST'] = "Kosong";
                            }
                        }
                    }else if($this->request->getPost('statusPKL') == "menunggu"){
                        if($this->request->getPost('tahun') == "all"){
                            $mahasiswa = $konf_mhs->menunggu_person_person_all($this->request->getPost('kampus'),date('Y-m-d'));
                            foreach($mahasiswa as &$mhs){
                                $mhs['STATUS_PST'] = "Menunggu";
                            }
                        }else{
                            $mahasiswa = $konf_mhs->menunggu_person_person_person($this->request->getPost('kampus'),date('Y-m-d'),$this->request->getPost('tahun'));
                            foreach($mahasiswa as &$mhs){
                                $mhs['STATUS_PST'] = "Menunggu";
                            }
                        }
                    }else{
                        if($this->request->getPost('statusPKL') == "aktif"){

                            if($this->request->getPost('tahun') == "all"){
                                //person, person, all
                                $mahasiswa = $konf_mhs->generate_person_person_all($this->request->getPost('kampus'),$this->request->getPost('statusPKL'));
                                $arrayMhs = array();
                                foreach ($mahasiswa as $mhs) {
                                    $found = false;
                                    foreach ($detail as $dtl) {
                                        if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                
                                    if (!$found) {
                                        $mhs['KELOMPOK'] = 'tidak';
                                        $arrayMhs[] = $mhs;
                                    }
                                }
                                
                                foreach ($mahasiswa as &$data1) {
                                    $found = false;
                                    foreach ($arrayMhs as $data2) {
                                        if ($data1['KD_PST'] == $data2['KD_PST']) {
                                            $found = true;
                                            $data1 = $data2;
                                            break;
                                        }
                                    }
                    
                                    if (!$found) {
                                        $data1['KELOMPOK'] = 'iya';
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                        return -1; // $a sebelum $b
                                    } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                        return 1; // $a setelah $b
                                    } else {
                                        return 0; // tidak ada perubahan urutan
                                    }
                                });
                    
                                foreach($mahasiswa as &$mhs){
                                    $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                                    if(!$detail){
                                        $mhs['STATUS_PST'] = "kosong";
                                    }else{
                                        if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                            $mhs['STATUS_PST'] = "menunggu";
                                        }
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    $order = array(
                                        'kosong' => 1,
                                        'menunggu' => 2,
                                        'aktif' => 3,
                                        'tidak aktif' => 4
                                    );
                                
                                    return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                                });

                                for ($i = 0; $i < count($mahasiswa); $i++) {
                                    if ($mahasiswa[$i]['STATUS_PST'] !== "aktif") {
                                        unset($mahasiswa[$i]);
                                    }
                                }
                                
                                $mahasiswa = array_values($mahasiswa);
                            
                            }else{
                                $mahasiswa = $konf_mhs->generate_person_person_person($this->request->getPost('kampus'),$this->request->getPost('statusPKL'),$this->request->getPost('tahun'));
                                $arrayMhs = array();
                                foreach ($mahasiswa as $mhs) {
                                    $found = false;
                                    foreach ($detail as $dtl) {
                                        if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                
                                    if (!$found) {
                                        $mhs['KELOMPOK'] = 'tidak';
                                        $arrayMhs[] = $mhs;
                                    }
                                }
                                
                                foreach ($mahasiswa as &$data1) {
                                    $found = false;
                                    foreach ($arrayMhs as $data2) {
                                        if ($data1['KD_PST'] == $data2['KD_PST']) {
                                            $found = true;
                                            $data1 = $data2;
                                            break;
                                        }
                                    }
                    
                                    if (!$found) {
                                        $data1['KELOMPOK'] = 'iya';
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                        return -1; // $a sebelum $b
                                    } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                        return 1; // $a setelah $b
                                    } else {
                                        return 0; // tidak ada perubahan urutan
                                    }
                                });
                    
                                foreach($mahasiswa as &$mhs){
                                    $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                                    if(!$detail){
                                        $mhs['STATUS_PST'] = "kosong";
                                    }else{
                                        if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                            $mhs['STATUS_PST'] = "menunggu";
                                        }
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    $order = array(
                                        'kosong' => 1,
                                        'menunggu' => 2,
                                        'aktif' => 3,
                                        'tidak aktif' => 4
                                    );
                                
                                    return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                                });

                                for ($i = 0; $i < count($mahasiswa); $i++) {
                                    if ($mahasiswa[$i]['STATUS_PST'] !== "aktif") {
                                        unset($mahasiswa[$i]);
                                    }
                                }
                                
                                $mahasiswa = array_values($mahasiswa);
                            }
                        }else{
                            if($this->request->getPost('tahun') == "all"){
                                //person, person, all
                                $mahasiswa = $konf_mhs->generate_person_person_all($this->request->getPost('kampus'),$this->request->getPost('statusPKL'));
                                $arrayMhs = array();
                                foreach ($mahasiswa as $mhs) {
                                    $found = false;
                                    foreach ($detail as $dtl) {
                                        if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                
                                    if (!$found) {
                                        $mhs['KELOMPOK'] = 'tidak';
                                        $arrayMhs[] = $mhs;
                                    }
                                }
                                
                                foreach ($mahasiswa as &$data1) {
                                    $found = false;
                                    foreach ($arrayMhs as $data2) {
                                        if ($data1['KD_PST'] == $data2['KD_PST']) {
                                            $found = true;
                                            $data1 = $data2;
                                            break;
                                        }
                                    }
                    
                                    if (!$found) {
                                        $data1['KELOMPOK'] = 'iya';
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                        return -1; // $a sebelum $b
                                    } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                        return 1; // $a setelah $b
                                    } else {
                                        return 0; // tidak ada perubahan urutan
                                    }
                                });
                    
                                foreach($mahasiswa as &$mhs){
                                    $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                                    if(!$detail){
                                        $mhs['STATUS_PST'] = "kosong";
                                    }else{
                                        if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                            $mhs['STATUS_PST'] = "menunggu";
                                        }
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    $order = array(
                                        'kosong' => 1,
                                        'menunggu' => 2,
                                        'aktif' => 3,
                                        'tidak aktif' => 4
                                    );
                                
                                    return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                                });
                            
                            }else{
                                $mahasiswa = $konf_mhs->generate_person_person_person($this->request->getPost('kampus'),$this->request->getPost('statusPKL'),$this->request->getPost('tahun'));
                                $arrayMhs = array();
                                foreach ($mahasiswa as $mhs) {
                                    $found = false;
                                    foreach ($detail as $dtl) {
                                        if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                                            $found = true;
                                            break;
                                        }
                                    }
                                
                                    if (!$found) {
                                        $mhs['KELOMPOK'] = 'tidak';
                                        $arrayMhs[] = $mhs;
                                    }
                                }
                                
                                foreach ($mahasiswa as &$data1) {
                                    $found = false;
                                    foreach ($arrayMhs as $data2) {
                                        if ($data1['KD_PST'] == $data2['KD_PST']) {
                                            $found = true;
                                            $data1 = $data2;
                                            break;
                                        }
                                    }
                    
                                    if (!$found) {
                                        $data1['KELOMPOK'] = 'iya';
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    if ($a['KELOMPOK'] == 'tidak' && $b['KELOMPOK'] == 'iya') {
                                        return -1; // $a sebelum $b
                                    } elseif ($a['KELOMPOK'] == 'iya' && $b['KELOMPOK'] == 'tidak') {
                                        return 1; // $a setelah $b
                                    } else {
                                        return 0; // tidak ada perubahan urutan
                                    }
                                });
                    
                                foreach($mahasiswa as &$mhs){
                                    $detail   = $konf_dtl->filter_detail1($mhs['KD_PST']);
                                    if(!$detail){
                                        $mhs['STATUS_PST'] = "kosong";
                                    }else{
                                        if(date('Y-m-d') < $detail[0]['TGL_MULAI_TIM']){
                                            $mhs['STATUS_PST'] = "menunggu";
                                        }
                                    }
                                }
                    
                                usort($mahasiswa, function($a, $b) {
                                    $order = array(
                                        'kosong' => 1,
                                        'menunggu' => 2,
                                        'aktif' => 3,
                                        'tidak aktif' => 4
                                    );
                                
                                    return $order[$a['STATUS_PST']] - $order[$b['STATUS_PST']];
                                });
                            }
                        }
                    }
                }
            }

            // konversi data
            for ($i = 0; $i < count($mahasiswa); $i++) {
                $mahasiswa[$i]['PASSWORD'] = encrypt_decrypt('decrypt', $mahasiswa[$i]['PASSWORD']);
            }
            
                $karyawan[1]            = $mahasiswa;
                $karyawan[20] = $konfigurasi;
    
                $data = [
                    'title'             => $kampus,
                    'database'          => $karyawan,
                ];

                if($this->request->getPost('generate')[0] == "pdf"){
                    date_default_timezone_set('Asia/Jakarta');
                    $nama_file = $this->request->getPost('bulan').$this->request->getPost('tahun').'-'.date('Ymdhis').'-Peserta';
                    $html = view('admin/mahasiswa/cetak', $data);
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'landscape');
                    $dompdf->set_option('isRemoteEnabled', true);
                    $dompdf->render();
                    $dompdf->stream($nama_file.'.pdf');
                }else{
                    return view('admin/mahasiswa/cetak', $data);
                }
        }
    }

    //halaman notifikasi
    public function notifikasi()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $mahasiswa              = $konf_mhs->filter_mahasiswa_masa_habis(date('Y-m-d'));
            $jumlah_telat           = $konf_mhs->jumlah_terlambat_mahasiswa();
            $jumlah_hadir           = $konf_mhs->jumlah_hadir_mahasiswa();
            $jumlah_izin            = $konf_mhs->jumlah_izin_mahasiswa();
            $konfigurasi            = $konf_kon->listening();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));

            // konversi data
            for ($i = 0; $i < count($mahasiswa); $i++) {
                $mulai = new \DateTime($mahasiswa[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $mulai->format('D');
                $mahasiswa[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($mahasiswa[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($mahasiswa[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $mahasiswa[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($mahasiswa[$i]['TGL_SELESAI_TIM']);
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

            $karyawan[1] = $mahasiswa;
            $karyawan[2] = $jumlah_telat;
            $karyawan[3] = $jumlah_hadir;
            $karyawan[4] = $jumlah_izin;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[20] = $konfigurasi;

            $data = [
                'title'            => 'Notifikasi',
                'jumlah'           => $jumlah_mhs_masa_habis->jumlah,
                'menu_active'      => '-',
                'submenu_active'   => '-',
                'database'         => $karyawan,
                'content'          => 'admin/mahasiswa/notifikasi'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //halaman view nonaktif
    public function viewModal()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_mhs               = new Model_peserta();
            $mahasiswa              = $konf_mhs->filter_data_mahasiswa($this->request->getGet('id'));
            $jumlah_telat           = $konf_mhs->jumlah_terlambat_mahasiswa();
            $jumlah_hadir           = $konf_mhs->jumlah_hadir_mahasiswa();
            $jumlah_izin            = $konf_mhs->jumlah_izin_mahasiswa();

            $mulai = new \DateTime($mahasiswa[0]['TGL_MULAI_TIM']);
            $hari_masuk = $mulai->format('D');
            $mahasiswa[0]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($mahasiswa[0]['TGL_MULAI_TIM']);

            $keluar = new \DateTime($mahasiswa[0]['TGL_SELESAI_TIM']);
            $hari_keluar = $keluar->format('D');
            $mahasiswa[0]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($mahasiswa[0]['TGL_SELESAI_TIM']);

            $mahasiswa[1] = $jumlah_telat;
            $mahasiswa[2] = $jumlah_hadir;
            $mahasiswa[3] = $jumlah_izin;

            $data = [
                'filter_mahasiswa'          =>  $mahasiswa
            ];

            return view('admin/mahasiswa/nonAktif', $data);
        }
    }

    //menampilkan foto mahasiswa
    public function viewMahasiswa()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $data = [
                'foto'          => $this->request->getGet('foto')
            ];

            return view('admin/mahasiswa/foto', $data);
        }
    }

    public function viewDtlMahasiswa()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_mhs               = new Model_peserta();
            $mahasiswa               = $konf_mhs->filt_mhs($this->request->getGet('id'));

            $data = [
                'mahasiswa'          => $mahasiswa
            ];

            return view('admin/mahasiswa/mahasiswa', $data);
        }
    }

    //simpan nonaktif
    public function nonAktif()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_mhs               = new Model_peserta();
            $data = [
                'KD_PST'        => $this->request->getGet('id'),
                'STATUS_PST'    => "tidak aktif"
            ];

            $konf_mhs->nonaktif_mahasiswa($data);

            session()->setFlashdata('flash', 'berhasil');
            return redirect()->to(base_url('amn'));
        }
    }

    //simpan nonaktif person
    public function nonAktif_person()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_mhs               = new Model_peserta();
            $data = [
                'KD_PST'        => $this->request->getGet('id'),
                'STATUS_PST'    => "tidak aktif"
            ];

            $konf_mhs->nonaktif_mahasiswa($data);

            session()->setFlashdata('flash', 'berhasil');
            echo "<script>javascript:history.go(-1)</script>";
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
