<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_konfigurasi;
use App\Models\Model_mahasiswa;
use App\Models\Model_pendaftaran;
use App\Models\Model_tim_mahasiswa;

class Konfigurasi extends BaseController
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
            $konf_tim               = new Model_tim_mahasiswa();
            $konf_mhs               = new Model_mahasiswa();
            $konf_pdf               = new Model_Pendaftaran();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $konfigurasi            = $konf_kon->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $pendaftaran            = $konf_pdf->notifikasi();
            $jumlah_pendaftaran     = $konf_pdf->jumlah_notifikasi();
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));

            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['tgl_selesai']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['tgl_selesai'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['tgl_selesai']);
            }

            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            // konversi data
            for ($i = 0; $i < count($mhs_masa_habis); $i++) {
                $mulai = new \DateTime($mhs_masa_habis[$i]['tgl_mulai']);
                $hari_masuk = $mulai->format('D');
                $mhs_masa_habis[$i]['tgl_mulai'] = hari($hari_masuk) . ", " . tgl_indo($mhs_masa_habis[$i]['tgl_mulai']);

                $keluar = new \DateTime($mhs_masa_habis[$i]['tgl_selesai']);
                $hari_keluar = $keluar->format('D');
                $mhs_masa_habis[$i]['tgl_selesai'] = hari($hari_keluar) . ", " . tgl_indo($mhs_masa_habis[$i]['tgl_selesai']);
            }

            // konversi data
            for ($i = 0; $i < count($pendaftaran); $i++) {
                $tanggal = new \DateTime(substr($pendaftaran[$i]['tanggal'],0,10));
                $konfersi = $tanggal->format('D');
                $pendaftaran[$i]['tanggal'] = hari($konfersi) . ", " . tgl_indo(substr($pendaftaran[$i]['tanggal'],0,10));
            }

            $karyawan[20] = $konfigurasi;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[30] = $pendaftaran;
            $karyawan[31] = $jumlah_pendaftaran->jumlah;

            $data = [
                'title'             => 'Konfigurasi',
                'menu_active'       => 'konfigurasi',
                'submenu_active'    => 'umum',
                'layout'            => 'Konfigurasi',
                'sub_layout'        => 'Umum',
                'database'          => $karyawan,
                'content'           => 'admin/konfigurasi/index'
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
            $konf_kon               = new Model_konfigurasi();
            $simmapkl_admin = "";
            $simmapkl_karma = "";

            if($this->request->getPost('simma_pkl_admin')){
                $simmapkl_admin = "aktif";
            }else{
                $simmapkl_admin = "tidak aktif";
            }

            if($this->request->getPost('simma_pkl_karma')){
                $simmapkl_karma = "aktif";
            }else{
                $simmapkl_karma = "tidak aktif";
            }

            $data = [
                'kd_konf'                 => $this->request->getPost('kode'),
                'nama_sistem'             => $this->request->getPost('namaSistem'),
                'singkatan'               => $this->request->getPost('singkatan'),
                'versi'                   => $this->request->getPost('versi'),
                'facebook'                   => $this->request->getPost('facebook'),
                'instagram'                   => $this->request->getPost('instagram'),
                'email'                   => $this->request->getPost('email'),
                'twitter'                   => $this->request->getPost('twitter'),
                'telepon'                   => $this->request->getPost('telepon'),
                'linked'                   => $this->request->getPost('linked'),
                'simmapkl_admin'          => $simmapkl_admin,
                'simmapkl_karma'          => $simmapkl_karma
            ];

            $konf_kon->edit_data($data);

            if($simmapkl_admin == "tidak aktif"){
                return redirect()->to(base_url('ld'));
            }else{
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('akgi'));
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
