<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_konfigurasi;
use App\Models\Model_peserta;
use App\Models\Model_tim_peserta;
use App\Models\Model_libur_nasional;

class Liburnasional extends BaseController
{
    //halaman utama konfigurasi
    public function index()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $konf_jdl               = new Model_libur_nasional();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $konfigurasi            = $konf_kon->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            $jadwal  = $konf_jdl->listening();
            $jumlah                 = $konf_jdl->jumlah_libur();

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


            $karyawan[20] = $konfigurasi;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[32] = $jadwal;

            $data = [
                'title'             => 'Libur Nasional (' . $jumlah->jumlah . ')',
                'menu_active'       => 'libur',
                'submenu_active'    => 'data_libur',
                'layout'            => 'Libur Nasional',
                'sub_layout'        => 'Data Libur Nasional',
                'database'          => $karyawan,
                'content'           => 'admin/libur/index'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //halaman utama konfigurasi
    public function tambah()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $konf_jdl               = new Model_libur_nasional();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $konfigurasi            = $konf_kon->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            $jumlah                 = $konf_jdl->jumlah_libur();

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


            $karyawan[20] = $konfigurasi;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;

            $data = [
                'title'             => 'Libur Nasional (' . $jumlah->jumlah . ')',
                'menu_active'       => 'libur',
                'submenu_active'    => 'tambah_libur',
                'layout'            => 'Libur Nasional',
                'sub_layout'        => 'Tambah Libur Nasional',
                'database'          => $karyawan,
                'content'           => 'admin/libur/tambah'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //halaman utama konfigurasi
    public function edit()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $konf_jdl               = new Model_libur_nasional();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $konfigurasi            = $konf_kon->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            $jumlah                 = $konf_jdl->jumlah_libur();
            $editdata                 = $konf_jdl->filter_data_libur($this->request->getGet('id'));

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


            $karyawan[20] = $konfigurasi;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[32] = $editdata;

            $data = [
                'title'             => $editdata[0]['KEGIATAN_LBR'],
                'menu_active'       => 'libur',
                'submenu_active'    => 'edit_libur',
                'layout'            => 'Libur Nasional',
                'sub_layout'        => 'Edit Libur Nasional',
                'database'          => $karyawan,
                'content'           => 'admin/libur/edit'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    //simpan data edit
    public function simpan()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_jdl       = new Model_libur_nasional;
            $cek = $konf_jdl->cek_libur($this->request->getPost('tanggal'));

            if($cek->jumlah > 0){
                session()->setFlashdata('flash', 'cek');
                echo "<script>javascript:history.go(-1)</script>";
            }else{
            $data = [
                'TANGGAL_LBR'               => $this->request->getPost('tanggal'),
                'KEGIATAN_LBR'             => $this->request->getPost('acara')
            ];
            $konf_jdl->tambah_data_libur($data);

            session()->setFlashdata('flash', 'berhasil');
            return redirect()->to(base_url('alni'));
            }
        }
    }

    //simpan data edit
    public function simpanedit()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_jdl       = new Model_libur_nasional;
            $cek = $konf_jdl->cek_libur($this->request->getPost('tanggal'));
            $cekData = $konf_jdl->filter_data_libur($this->request->getPost('id'));

            if($cekData[0]['TANGGAL_LBR'] == $this->request->getPost('tanggal')){
                $data = [
                    'ID_LBR'             => $this->request->getPost('id'),
                    'TANGGAL_LBR'               => $this->request->getPost('tanggal'),
                    'KEGIATAN_LBR'             => $this->request->getPost('acara')
                ];
                $konf_jdl->edit_data_libur($data);
    
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('alni'));
            }else{
                if($cek->jumlah > 0){
                    session()->setFlashdata('flash', 'cek');
                    echo "<script>javascript:history.go(-1)</script>";
                }else{
                    $data = [
                        'ID_LBR'             => $this->request->getPost('id'),
                        'TANGGAL_LBR'               => $this->request->getPost('tanggal'),
                        'KEGIATAN_LBR'             => $this->request->getPost('acara')
                    ];
                    $konf_jdl->edit_data_libur($data);
        
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('alni'));
                }
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
            $konf_jdl              = new Model_libur_nasional();
            
            $data = [
                'ID_LBR'     => $this->request->getGet('id')
            ];

            $konf_jdl->hapus_data_libur($data);

            session()->setFlashdata('flash', 'berhasil');
            return redirect()->to(base_url('alni'));
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