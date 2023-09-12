<?php

namespace App\Controllers\Admin;
use App\Models\Model_konfigurasi;
use App\Models\Model_pendaftaran;
use App\Models\Model_mahasiswa;
use App\Models\Model_karyawan;
use App\Models\Model_testimoni;
use App\Models\Model_tim_mahasiswa;

class Testimoni extends BaseController
{
    //halaman utama jabatan
    public function index()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kon               = new Model_konfigurasi();
            $konf_pdf               = new Model_Pendaftaran();
            $konf_tim               = new Model_tim_mahasiswa();
            $konf_kry               = new Model_karyawan();
            $konf_mhs               = new Model_mahasiswa();
            $konf_tst               = new Model_testimoni();
            $konfigurasi            = $konf_kon->listening();
            $jumlah_pendaftaran     = $konf_pdf->jumlah_notifikasi();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $pendaftaran            = $konf_pdf->notifikasi();
            $data_pendaftar         = $konf_pdf->listening();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $jumlah                 = $konf_tst->jumlah_testimoni();
            $data_testi                 = $konf_tst->listening_kelola();
            
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
            
            // konversi data
            for ($i = 0; $i < count($data_testi); $i++) {
                $tanggal = new \DateTime(substr($data_testi[$i]['tanggal'],0,10));
                $konfersi = $tanggal->format('D');
                $data_testi[$i]['tanggal'] = hari($konfersi) . ", " . tgl_indo(substr($data_testi[$i]['tanggal'],0,10));
            }

            $karyawan[0]['nama_kawan'] = ucwords($karyawan[0]['nama_kawan']);
            $karyawan[1]  = $data_testi;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[30] = $pendaftaran;
            $karyawan[31] = $jumlah_pendaftaran->jumlah;

            $data = [
                'title'             => 'Testimoni (' . $jumlah->jumlah . ')',
                'layout'            => 'Testimoni',
                'sub_layout'        => '-',
                'menu_active'       => 'testimoni',
                'submenu_active'    => '-',
                'database'          => $karyawan,
                'content'           => 'admin/testimoni/index'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }
    
    //menampilkan foto mahasiswa
    public function viewTestimoni()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $data = [
                'foto'          => $this->request->getGet('foto')
            ];

            return view('admin/testimoni/foto', $data);
        }
    }

    //menampilkan foto mahasiswa
    public function viewPublish()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tst               = new Model_testimoni();
            $testimoni              = $konf_tst->filter_listening($this->request->getGet('testi'));

            $data = [
                'testi'          => $testimoni
            ];

            return view('admin/testimoni/publish', $data);
        }
    }
    
    //menampilkan foto mahasiswa
    public function simpanPublish()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_tst               = new Model_testimoni();
            
            $data = [
                'id_testi'          => $this->request->getPost('id_testi'),
                'publish'           => $this->request->getPost('publish')
            ];

            $konf_tst->edit_data($data);

            session()->setFlashdata('flash', 'testimoni');
            return redirect()->to(base_url('/adt'));
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
