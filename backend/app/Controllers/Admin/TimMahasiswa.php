<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_tim_peserta;
use App\Models\Model_asal;
use App\Models\Model_dtl_tim_peserta;
use App\Models\Model_peserta;
use App\Models\Model_konfigurasi;
use Dompdf\Dompdf;

class TimMahasiswa extends BaseController
{
    //halaman utama tim mahasiswa
    public function index()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_tim               = new Model_tim_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $konf_kmps              = new Model_asal();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $tim                    = $konf_tim->listening1(date('Y-m-d'));
            $konfigurasi            = $konf_kon->listening();
            $jumlah                 = $konf_tim->jumlah_tim_mahasiswa();
            $detail                 = $konf_dtl->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $kampus                 = $konf_kmps->listening();
            $tahun                  = $konf_tim->tahun();
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

            for ($i = 0; $i < count($tim); $i++) {
                $tim[$i]['CEK_TGL_MULAI'] = $tim[$i]['TGL_MULAI_TIM'];
                $mulai = new \DateTime($tim[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $mulai->format('D');
                $tim[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($tim[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($tim[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim[$i]['TGL_SELESAI_TIM']);
            }
        
            
            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $karyawan[1] = $tim;
            $karyawan[2] = $detail;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[21] = $kampus;
            $karyawan[22] = $tahun;

            $data = [
                'title'             => 'Tim Peserta (' . $jumlah->jumlah . ')',
                'layout'            => 'Tim Peserta',
                'sub_layout'        => 'Data Tim Peserta',
                'menu_active'       => 'tim',
                'submenu_active'    => 'data_tim',
                'database'          => $karyawan,
                'content'           => 'admin/timMahasiswa/index'
            ];

            return view('admin/layout/wrapper', $data);
            // dd($tim);
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
            $konf_tim               = new Model_tim_peserta();
            $konf_kmps              = new Model_asal();
            $konf_kon               = new Model_konfigurasi();
            $konf_mhs               = new Model_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $jumlah                 = $konf_tim->jumlah_tim_mahasiswa();
            $kampus                 = $konf_kmps->listening();
            $konfigurasi            = $konf_kon->listening();
            $mahasiswa              = $konf_mhs->listening_mahasiswa_aktif();
            $pembimbing             = $konf_kry->pembimbing_lapangan();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $detail                 = $konf_dtl->listening();
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
                    $arrayMhs[] = $mhs;
                }
            }

            foreach($detail as $dtl){
                array_push($arrayMhs, $dtl);
            }
            

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;
            $karyawan[1] = $kampus;
            $karyawan[2] = $pembimbing;
            $karyawan[3] = $arrayMhs;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[21] = $detail;

            $data = [
                'title'             => 'Tim Peserta (' . $jumlah->jumlah . ')',
                'layout'            => 'Tim Peserta',
                'sub_layout'        => 'Tambah Tim Peserta',
                'menu_active'       => 'tim',
                'bypas'             => 'tidak',
                'submenu_active'    => 'tambah_tim',
                'database'          => $karyawan,
                'content'           => 'admin/timMahasiswa/tambah'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //halaman tambah data
    public function tambahbypass()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_tim               = new Model_tim_peserta();
            $konf_kmps              = new Model_asal();
            $konf_kon               = new Model_konfigurasi();
            $konf_mhs               = new Model_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $jumlah                 = $konf_tim->jumlah_tim_mahasiswa();
            $kampus                 = $konf_kmps->listening();
            $konfigurasi            = $konf_kon->listening();
            $mahasiswa              = $konf_mhs->listening_mahasiswa_aktif();
            $pembimbing             = $konf_kry->pembimbing_lapangan();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $detail                 = $konf_dtl->listening();
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            $bypas              = $konf_mhs->filter_data_mahasiswa_edit($this->request->getGet('id'));

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
                    $arrayMhs[] = $mhs;
                }
            }

            foreach($detail as $dtl){
                array_push($arrayMhs, $dtl);
            }
            

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;
            $karyawan[1] = $kampus;
            $karyawan[2] = $pembimbing;
            $karyawan[3] = $arrayMhs;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[21] = $detail;
            $karyawan[31] = $bypas;

            $data = [
                'title'             => 'Tim Peserta (' . $jumlah->jumlah . ')',
                'layout'            => 'Tim Peserta',
                'sub_layout'        => 'Tambah Tim Peserta',
                'menu_active'       => 'tim',
                'bypas'             => 'iya',
                'dari'             => 'peserta',
                'submenu_active'    => 'tambah_tim',
                'database'          => $karyawan,
                'content'           => 'admin/timMahasiswa/tambah'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //halaman tambah data
    public function tambahbypasskar()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_tim               = new Model_tim_peserta();
            $konf_kmps              = new Model_asal();
            $konf_kon               = new Model_konfigurasi();
            $konf_mhs               = new Model_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $jumlah                 = $konf_tim->jumlah_tim_mahasiswa();
            $kampus                 = $konf_kmps->listening();
            $konfigurasi            = $konf_kon->listening();
            $mahasiswa              = $konf_mhs->listening_mahasiswa_aktif();
            $pembimbing             = $konf_kry->pembimbing_lapangan();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $detail                 = $konf_dtl->listening();
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            $bypas                    = $konf_kry->filter_kd_karyawan($_GET['id']);

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
                    $arrayMhs[] = $mhs;
                }
            }

            foreach($detail as $dtl){
                array_push($arrayMhs, $dtl);
            }
            

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;
            $karyawan[1] = $kampus;
            $karyawan[2] = $pembimbing;
            $karyawan[3] = $arrayMhs;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[21] = $detail;
            $karyawan[31] = $bypas;

            $data = [
                'title'             => 'Tim Peserta (' . $jumlah->jumlah . ')',
                'layout'            => 'Tim Peserta',
                'sub_layout'        => 'Tambah Tim Peserta',
                'menu_active'       => 'tim',
                'bypas'             => 'iya',
                'dari'             => 'karyawan',
                'submenu_active'    => 'tambah_tim',
                'database'          => $karyawan,
                'content'           => 'admin/timMahasiswa/tambah'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //simpat data
    public function simpan()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tim               = new Model_tim_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $max_kode_tim           = $konf_tim->max_kode_tim();
            $kode_tim_baru          = "";
            $data = [];
            $mahasiswa = $this->request->getPost('input_field');

            if($mahasiswa){
                if ($max_kode_tim->KD_TIM) {
                    $kode_tim               = substr($max_kode_tim->KD_TIM, 2) + 1;
                    
                    if ($kode_tim < 10) {
                        $kode_tim_baru = "T00000" . $kode_tim;
                    } else if ($kode_tim >= 10 && $kode_tim < 100) {
                        $kode_tim_baru = "T0000" . $kode_tim;
                    } else if ($kode_tim >= 100 && $kode_tim < 1000) {
                        $kode_tim_baru = "T000" . $kode_tim;
                    } else if ($kode_tim >= 1000 && $kode_tim < 10000) {
                        $kode_tim_baru = "T00" . $kode_tim;
                    } else if ($kode_tim >= 10000 && $kode_tim < 100000) {
                        $kode_tim_baru = "T0" . $kode_tim;
                    } else if ($kode_tim >= 100000 && $kode_tim < 1000000) {
                        $kode_tim_baru = "T" . $kode_tim;
                    }
                }
    
                if ($max_kode_tim->KD_TIM) {
                    $data = [
                        'KD_TIM'             => $kode_tim_baru,
                        'KD_KAWAN'           => $this->request->getPost('pkl'),
                        'NAMA_TIM'           => $this->request->getPost('tim'),
                        'KD_ASAL'            => $this->request->getPost('kampus'),
                        'TGL_MULAI_TIM'          => $this->request->getPost('mulai'),
                        'TGL_SELESAI_TIM'        => $this->request->getPost('selesai'),
                        'TAHUN_TIM'              => $this->request->getPost('tahun'),
                        'STATUS_TIM'         => 'aktif',
                    ];
                } else {
                    $data = [
                        'KD_TIM'             => 'T000001',
                        'KD_KAWAN'           => $this->request->getPost('pkl'),
                        'NAMA_TIM'           => $this->request->getPost('tim'),
                        'KD_ASAL'            => $this->request->getPost('kampus'),
                        'TGL_MULAI_TIM'          => $this->request->getPost('mulai'),
                        'TGL_SELESAI_TIM'        => $this->request->getPost('selesai'),
                        'TAHUN_TIM'              => $this->request->getPost('tahun'),
                        'STATUS_TIM'         => 'aktif',
                    ];
                }

                if($this->request->getPost('mulai') > $this->request->getPost('selesai')){
                    session()->setFlashdata('flash', 'mulaiselesai');
                    echo "<script>javascript:history.go(-1)</script>";
                }else{
                    $konf_tim->tambah_tim($data);
        
                    foreach ($mahasiswa as $data) {
                        $dt = [
                            'KD_TIM'    => $kode_tim_baru,
                            'KD_PST'    => $data,
                        ];
        
                        $konf_dtl->tambah_data_tim($dt);
                    }
        
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('at'));
                }
            }else{
                session()->setFlashdata('flash', 'kosong');
                echo "<script>javascript:history.go(-1)</script>";
            }

        }
    }

    public function notifikasi()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tim                   = new Model_tim_peserta();
            $konf_dtl_tim               = new Model_dtl_tim_peserta();
            $konf_kon                   = new Model_konfigurasi();
            $konf_mhs                   = new Model_peserta();
            $konf_kry               = new Model_karyawan();
            $detail                     = $konf_dtl_tim->listening();
            $tim                        = $konf_tim->filter_tim_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $konfigurasi            = $konf_kon->listening();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));

            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $tim_habis              = $konf_tim->filter_tim_masa_habis(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));

            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            // konversi data
            for ($i = 0; $i < count($tim_habis); $i++) {
                $masuk = new \DateTime($tim_habis[$i]['TGL_MULAI_TIM']);
                $hari_masuk = $masuk->format('D');
                $tim_habis[$i]['TGL_MULAI_TIM'] = hari($hari_masuk) . ", " . tgl_indo($tim_habis[$i]['TGL_MULAI_TIM']);

                $keluar = new \DateTime($tim_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_habis[$i]['TGL_SELESAI_TIM']);
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
            

            $totalPeserta = count($tim_habis);
            
            $totalA = ceil($totalPeserta / 2);
            $totalB = $totalPeserta - $totalA;
            
            $arrayA = array_slice($tim_habis, 0, $totalA);
            $arrayB = array_slice($tim_habis, $totalA);

            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;
            $karyawan[82] = $detail;
            $karyawan[83] = $arrayA;
            $karyawan[84] = $arrayB;

            $data = [
                'title'            => 'Masa PKL Habis',
                'jumlah'           => $jumlah_tim_masa_habis->jumlah,
                'menu_active'      => '-',
                'submenu_active'   => '-',
                'database'         => $karyawan,
                'content'          => 'admin/timMahasiswa/notifikasi'
            ];

            return view('admin/layout/wrapper', $data);
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
            $konf_tim               = new Model_tim_peserta();
            $konf_tim_dtl           = new Model_dtl_tim_peserta();
            $konf_kmps              = new Model_asal();
            $konf_mhs               = new Model_peserta();
            $konf_kon               = new Model_konfigurasi();
            $detail                 = $konf_tim_dtl->listening();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $tim                    = $konf_tim->filter_data_tim_mahasiswa($_GET['id']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $kampus                 = $konf_kmps->listening();
            $konfigurasi            = $konf_kon->listening();
            $pembimbing             = $konf_kry->pembimbing_lapangan();
            $mahasiswa_tim          = $konf_tim_dtl->filter_data_dtl_tim_mahasiswa($_GET['id']);
            $mahasiswa_aktif        = $konf_mhs->listening_mahasiswa_aktif();
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

            $arrayMhs = array();
            foreach ($mahasiswa_aktif as $mhs) {
                $found = false;
                foreach ($detail as $dtl) {
                    if ($mhs['KD_PST'] == $dtl['KD_PST']) {
                        $found = true;
                        break;
                    }
                }
            
                if (!$found) {
                    $arrayMhs[] = $mhs;
                }
            }

            foreach($detail as $dtl){
                array_push($arrayMhs, $dtl);
            }

            $dtlMahasiswa = array();

            foreach($detail as $dataDetail){
                if($dataDetail['KD_TIM'] != $_GET['id']){
                    array_push($dtlMahasiswa, $dataDetail);
                }
            }

                        // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['TGL_SELESAI_TIM'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['TGL_SELESAI_TIM']);
            }

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $karyawan[1] = $tim;
            $karyawan[2] = $kampus;
            $karyawan[3] = $pembimbing;
            $karyawan[4] = $mahasiswa_tim;
            $karyawan[5] = $arrayMhs;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[21] = $dtlMahasiswa;

            $data = [
                'title'             => ucwords($tim[0]['NAMA_TIM']),
                'layout'            => 'Tim Peserta',
                'sub_layout'        => 'Edit Tim Peserta',
                'menu_active'       => 'tim',
                'submenu_active'    => 'edit_tim',
                'database'          => $karyawan,
                'content'           => 'admin/timMahasiswa/edit'
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
            $konf_tim               = new Model_tim_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $mahasiswa = $this->request->getPost('input_field');

            if($mahasiswa){
                $data = [
                    'KD_TIM'                => $this->request->getPost('kode'),
                    'KD_KAWAN'              => $this->request->getPost('pembimbing'),
                    'NAMA_TIM'              => $this->request->getPost('tim'),
                    'KD_ASAL'               => $this->request->getPost('kampus'),
                    'TGL_MULAI_TIM'             => $this->request->getPost('mulai'),
                    'TGL_SELESAI_TIM'           => $this->request->getPost('selesai'),
                    'TAHUN_TIM'                 => $this->request->getPost('tahun')
                ];

                if($this->request->getPost('mulai') > $this->request->getPost('selesai')){
                    session()->setFlashdata('flash', 'mulaiselesai');
                    echo "<script>javascript:history.go(-1)</script>";
                }else{
                    $konf_tim->edit_data_tim($data);
                    $konf_dtl->hapus_data_tim($data);
        
                    foreach ($mahasiswa as $data) {
                        $dt = [
                            'KD_TIM'    => $this->request->getPost('kode'),
                            'KD_PST'    => $data,
                        ];
        
                        $konf_dtl->tambah_data_tim($dt);
                    }
        
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('at'));
                }
    
            }else{
                session()->setFlashdata('flash', 'kosong');
                echo "<script>javascript:history.go(-1)</script>";
            }

        }
    }

    //hapus data
    public function hapus()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tim               = new Model_tim_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $cek                    = $konf_tim->filter($this->request->getGet('id'));

            if(date('Y-m-d') < $cek[0]['TGL_MULAI_TIM']){
                $data = [
                    'KD_TIM'    => $this->request->getGet('id')
                ];
    
                $konf_dtl->hapus_data_tim($data);
                $konf_tim->hapus_tim($data);
    
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('at'));
            }else if(date('Y-m-d') >= $cek[0]['TGL_MULAI_TIM'] && date('Y-m-d') <= $cek[0]['TGL_SELESAI_TIM']){
                session()->setFlashdata('flash', 'masih aktif');
                return redirect()->to(base_url('at'));
            }else if(date('Y-m-d') > $cek[0]['TGL_SELESAI_TIM']){
                session()->setFlashdata('flash', 'tidak aktif');
                return redirect()->to(base_url('at'));
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
            $konf_tim               = new Model_tim_peserta();
            $konf_dtl               = new Model_dtl_tim_peserta();
            $konf_kon               = new Model_konfigurasi();
            $konf_kmps              = new Model_asal();
            $konfigurasi            = $konf_kon->listening();

            $kampus = "";

            if($this->request->getPost('kampus') == "all"){
                $kampus = "All";
            }else{
                $konf = $konf_kmps->filter_data_kampus($this->request->getPost('kampus'));
                $kampus = ucwords($konf[0]['NAMA_ASAL']);
            }

            if($this->request->getPost('kampus') == "all"){
                if($this->request->getPost('statusTim') == "all"){
                    if($this->request->getPost('tahun') == "all"){
                        $karyawan[3] = $konf_tim->generate_all_all_all();
                        foreach($karyawan[3] as &$kry){
                            if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                $kry['STATUS_TIM'] = "menunggu";
                            }
                        }
                    }else{
                        $karyawan[3] = $konf_tim->generate_all_all_person($this->request->getPost('tahun'));
                        foreach($karyawan[3] as &$kry){
                            if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                $kry['STATUS_TIM'] = "menunggu";
                            }
                        }
                    }
                }else{
                    if($this->request->getPost('statusTim') == "menunggu"){
                        if($this->request->getPost('tahun') == "all"){
                            $karyawan[3] = $konf_tim->menunggu_all_person_all(date('Y-m-d'));
                            foreach($karyawan[3] as &$kry){
                                if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                    $kry['STATUS_TIM'] = "menunggu";
                                }
                            }
                        }else{
                            $karyawan[3] = $konf_tim->menunggu_all_person_person(date('Y-m-d'), $this->request->getPost('tahun'));
                            foreach($karyawan[3] as &$kry){
                                if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                    $kry['STATUS_TIM'] = "menunggu";
                                }
                            }
                        }
                    }else{
                        if($this->request->getPost('statusTim') == "aktif"){
                            if($this->request->getPost('tahun') == "all"){
                                $karyawan[3] = $konf_tim->generate_all_person_all($this->request->getPost('statusTim'));
                                foreach($karyawan[3] as &$kry){
                                    if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                        $kry['STATUS_TIM'] = "menunggu";
                                    }
                                }
    
                                for ($i = 0; $i < count($karyawan[3]); $i++) {
                                    if ($karyawan[3][$i]['STATUS_TIM'] !== "aktif") {
                                        unset($karyawan[3][$i]);
                                    }
                                }
                                
                                $karyawan[3] = array_values($karyawan[3]);
                            }else{
                                $karyawan[3] = $konf_tim->generate_all_person_person($this->request->getPost('statusTim'),$this->request->getPost('tahun'));
                                foreach($karyawan[3] as &$kry){
                                    if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                        $kry['STATUS_TIM'] = "menunggu";
                                    }
                                }
    
                                for ($i = 0; $i < count($karyawan[3]); $i++) {
                                    if ($karyawan[3][$i]['STATUS_TIM'] !== "aktif") {
                                        unset($karyawan[3][$i]);
                                    }
                                }
                                
                                $karyawan[3] = array_values($karyawan[3]);
                            }
                        }else{
                            if($this->request->getPost('tahun') == "all"){
                                $karyawan[3] = $konf_tim->generate_all_person_all($this->request->getPost('statusTim'));
                                foreach($karyawan[3] as &$kry){
                                    if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                        $kry['STATUS_TIM'] = "menunggu";
                                    }
                                }
                            }else{
                                $karyawan[3] = $konf_tim->generate_all_person_person($this->request->getPost('statusTim'),$this->request->getPost('tahun'));
                                foreach($karyawan[3] as &$kry){
                                    if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                        $kry['STATUS_TIM'] = "menunggu";
                                    }
                                }

                            }
                        }
                    }
                }
            }else{
                if($this->request->getPost('statusTim') == "all"){
                    if($this->request->getPost('tahun') == "all"){
                        $karyawan[3] = $konf_tim->generate_person_all_all($this->request->getPost('kampus'));
                        foreach($karyawan[3] as &$kry){
                            if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                $kry['STATUS_TIM'] = "menunggu";
                            }
                        }
                    }else{
                        $karyawan[3] = $konf_tim->generate_person_all_person($this->request->getPost('kampus'),$this->request->getPost('tahun'));
                        foreach($karyawan[3] as &$kry){
                            if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                $kry['STATUS_TIM'] = "menunggu";
                            }
                        }
                    }
                }else{
                    if($this->request->getPost('statusTim') == "menunggu"){
                        if($this->request->getPost('tahun') == "all"){
                            $karyawan[3] = $konf_tim->menunggu_person_person_all($this->request->getPost('kampus'), date('Y-m-d'));
                            foreach($karyawan[3] as &$kry){
                                if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                    $kry['STATUS_TIM'] = "menunggu";
                                }
                            }
                        }else{
                            $karyawan[3] = $konf_tim->menunggu_person_person_person($this->request->getPost('kampus'), date('Y-m-d'), $this->request->getPost('tahun'));
                            foreach($karyawan[3] as &$kry){
                                if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                    $kry['STATUS_TIM'] = "menunggu";
                                }
                            }
                        }
                    }else{
                        if($this->request->getPost('statusTim') == "aktif"){
                            if($this->request->getPost('tahun') == "all"){
                                //person, person, all
                                $karyawan[3] = $konf_tim->generate_person_person_all($this->request->getPost('kampus'),$this->request->getPost('statusTim'));
                                foreach($karyawan[3] as &$kry){
                                    if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                        $kry['STATUS_TIM'] = "menunggu";
                                    }
                                }

                                for ($i = 0; $i < count($karyawan[3]); $i++) {
                                    if ($karyawan[3][$i]['STATUS_TIM'] !== "aktif") {
                                        unset($karyawan[3][$i]);
                                    }
                                }
                                
                                $karyawan[3] = array_values($karyawan[3]);
                            }else{
                                $karyawan[3] = $konf_tim->generate_person_person_person($this->request->getPost('kampus'),$this->request->getPost('statusTim'),$this->request->getPost('tahun'));
                                foreach($karyawan[3] as &$kry){
                                    if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                        $kry['STATUS_TIM'] = "menunggu";
                                    }
                                }

                                for ($i = 0; $i < count($karyawan[3]); $i++) {
                                    if ($karyawan[3][$i]['STATUS_TIM'] !== "aktif") {
                                        unset($karyawan[3][$i]);
                                    }
                                }
                                
                                $karyawan[3] = array_values($karyawan[3]);
                            }
                        }else{
                            if($this->request->getPost('tahun') == "all"){
                                //person, person, all
                                $karyawan[3] = $konf_tim->generate_person_person_all($this->request->getPost('kampus'),$this->request->getPost('statusTim'));
                                foreach($karyawan[3] as &$kry){
                                    if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                        $kry['STATUS_TIM'] = "menunggu";
                                    }
                                }
                            }else{
                                $karyawan[3] = $konf_tim->generate_person_person_person($this->request->getPost('kampus'),$this->request->getPost('statusTim'),$this->request->getPost('tahun'));
                                foreach($karyawan[3] as &$kry){
                                    if(date('Y-m-d') < $kry['TGL_MULAI_TIM']){
                                        $kry['STATUS_TIM'] = "menunggu";
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $karyawan[20] = $konfigurasi;

            $data = [
                'title'             => $kampus,
                'database'          => $karyawan
            ];

            if($this->request->getPost('generate')[0] == "pdf"){
                date_default_timezone_set('Asia/Jakarta');
                $nama_file = $this->request->getPost('bulan').$this->request->getPost('tahun').'-'.date('Ymdhis').'-Tim';
                $html = view('admin/timMahasiswa/cetak', $data);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->set_option('isRemoteEnabled', true);
                $dompdf->render();
                $dompdf->stream($nama_file.'.pdf');
            }else{
                return view('admin/timMahasiswa/cetak',$data);
            }
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
                'foto'          => $_GET['foto']
            ];

            return view('admin/timMahasiswa/foto', $data);
        }
    }

    //menampilkan foto mahasiswa
    public function viewModal()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tim               = new Model_tim_peserta();
            $konf_dtl_tim               = new Model_dtl_tim_peserta();
            $tim                    = $konf_tim->filter_data_tim_mahasiswa($this->request->getGet('id'));
            $detail                 = $konf_dtl_tim->filter_data_dtl_tim_mahasiswa($this->request->getGet('id'));
            
            // // // konversi data
            for ($i = 0; $i < count($tim); $i++) {
                $tanggal = new \DateTime(substr($tim[$i]['TGL_SELESAI_TIM'],0,10));
                $konfersi = $tanggal->format('D');
                $tim[$i]['TGL_SELESAI_TIM'] = hari($konfersi) . ", " . tgl_indo(substr($tim[$i]['TGL_SELESAI_TIM'],0,10));
            }

            for ($i = 0; $i < count($tim); $i++) {
                $tanggal = new \DateTime(substr($tim[$i]['TGL_MULAI_TIM'],0,10));
                $konfersi = $tanggal->format('D');
                $tim[$i]['TGL_MULAI_TIM'] = hari($konfersi) . ", " . tgl_indo(substr($tim[$i]['TGL_MULAI_TIM'],0,10));
            }

            $database[0]     = $tim;
            $database[1]     = $detail;

            $data = [
                'database'          =>  $database
            ];

            return view('admin/timMahasiswa/nonAKtif', $data);
        }
    }

    //simpan nonaktif person
    public function nonAktif_tim()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tim               = new Model_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $konf_dtl_tim           = new Model_dtl_tim_peserta();

            $detail                 = $konf_dtl_tim->filter_data_dtl_tim_mahasiswa($this->request->getPost('id'));
            for ($i = 0; $i < count($detail); $i++) {
                $mahasiswa = [
                    'KD_PST'        => $detail[$i]['KD_PST'],
                    'STATUS_PST'    => "tidak aktif"
                ];
    
                $konf_mhs->nonaktif_mahasiswa($mahasiswa);
            }

            $data = [
                'KD_TIM'        => $this->request->getPost('id'),
                'STATUS_TIM'    => "tidak aktif"
            ];

            $konf_tim->nonaktif_tim($data);

            session()->setFlashdata('flash', 'berhasil');
            echo "<script>javascript:history.go(-1)</script>";
        }
    }

    public function notNonaktif()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tim               = new Model_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $konf_dtl_tim           = new Model_dtl_tim_peserta();

            $detail                 = $konf_dtl_tim->filter_data_dtl_tim_mahasiswa($this->request->getGet('id'));
            for ($i = 0; $i < count($detail); $i++) {
                $mahasiswa = [
                    'KD_PST'        => $detail[$i]['KD_PST'],
                    'STATUS_PST'    => "tidak aktif"
                ];
    
                $konf_mhs->nonaktif_mahasiswa($mahasiswa);
            }

            $data = [
                'KD_TIM'        => $this->request->getGet('id'),
                'STATUS_TIM'    => "tidak aktif"
            ];

            $konf_tim->nonaktif_tim($data);

            session()->setFlashdata('flash', 'berhasil');
            echo "<script>javascript:history.go(-1)</script>";
        }
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
