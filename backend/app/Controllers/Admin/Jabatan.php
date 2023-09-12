<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_jabatan;
use App\Models\Model_peserta;
use App\Models\Model_konfigurasi;
use App\Models\Model_tim_peserta;

class Jabatan extends BaseController
{
    //halaman utama jabatan
    public function index()
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
            $jabatan                = $konf_jbtn->listening();
            $jumlah                 = $konf_jbtn->jumlah_jabatan();
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
                'title'             => 'Jabatan (' . $jumlah->jumlah . ')',
                'layout'            => 'Jabatan',
                'sub_layout'        => 'Data Jabatan',
                'menu_active'       => 'jabatan',
                'submenu_active'    => 'data_jabatan',
                'database'          => $karyawan,
                'content'           => 'admin/jabatan/index'
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
            $konf_tim               = new Model_tim_peserta();
            $konf_kon               = new Model_konfigurasi();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah                 = $konf_jbtn->jumlah_jabatan();
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


            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;

            $data = [
                'title'             => 'Jabatan (' . $jumlah->jumlah . ')',
                'layout'            => 'Jabatan',
                'sub_layout'        => 'Tambah Jabatan',
                'menu_active'       => 'jabatan',
                'submenu_active'    => 'tambah_jabatan',
                'database'          => $karyawan,
                'content'           => 'admin/jabatan/tambah'
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
            $konf_jbtn              = new Model_jabatan();
            $max_kode_jbtn          = $konf_jbtn->max_kode_jabatan();
            $kode_jbtn_baru  = "";
            $data = [];

            if ($max_kode_jbtn->KD_JBTN) {
                $kode_jbtn       = substr($max_kode_jbtn->KD_JBTN, 2) + 1;

                if ($kode_jbtn < 10) {
                    $kode_jbtn_baru = "JB000" . $kode_jbtn;
                } else if ($kode_jbtn >= 10 && $kode_jbtn < 100) {
                    $kode_jbtn_baru = "JB00" . $kode_jbtn;
                } else if ($kode_jbtn >= 100 && $kode_jbtn < 1000) {
                    $kode_jbtn_baru = "JB0" . $kode_jbtn;
                } else if ($kode_jbtn >= 1000 && $kode_jbtn < 10000) {
                    $kode_jbtn_baru = "JB" . $kode_jbtn;
                }
            }

            if ($max_kode_jbtn->KD_JBTN) {
                $data = [
                    'KD_JBTN'               => $kode_jbtn_baru,
                    'NAMA_JBTN'             => $this->request->getPost('jabatan'),
                ];
            } else {
                $data = [
                    'KD_JBTN'               => 'JB0001',
                    'NAMA_JBTN'             => $this->request->getPost('jabatan'),
                ];
            }

            $konf_jbtn->tambah_data_jabatan($data);

            session()->setFlashdata('flash', 'berhasil');
            return redirect()->to(base_url('aj'));
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
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jabatan                = $konf_jbtn->filter_data_jabatan($_GET['id']);
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
                'title'             => ucwords($jabatan[0]['NAMA_JBTN']),
                'layout'            => 'Jabatan',
                'sub_layout'        => 'Edit Jabatan',
                'menu_active'       => 'jabatan',
                'submenu_active'    => 'edit_jabatan',
                'database'          => $karyawan,
                'content'           => 'admin/jabatan/edit'
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
            $konf_jbtn              = new Model_jabatan();

            $data = [
                'KD_JBTN'             => $this->request->getPost('kode'),
                'NAMA_JBTN'           => $this->request->getPost('jabatan'),
            ];

            $konf_jbtn->edit_data_jabatan($data);

            session()->setFlashdata('flash', 'berhasil');
            return redirect()->to(base_url('aj'));
        }
    }

    //hapus data
    public function hapus()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_jbtn              = new Model_jabatan();
            $data_tidak_terelasi_karyawan = $konf_jbtn->cek_data_relasi_karyawan($this->request->getGet('id'));

            if($data_tidak_terelasi_karyawan){
                $data = [
                    'KD_JBTN'     => $this->request->getGet('id')
                ];
    
                $konf_jbtn->hapus_data_jabatan($data);
    
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('aj'));
            }else{
                session()->setFlashdata('flash', 'terelasi');
                return redirect()->to(base_url('aj'));
            }
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
