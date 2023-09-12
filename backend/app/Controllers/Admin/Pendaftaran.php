<?php

namespace App\Controllers\Admin;
use App\Models\Model_konfigurasi;
use App\Models\Model_mahasiswa;
use App\Models\Model_karyawan;
use App\Models\Model_pendaftaran;
use App\Models\Model_dtl_pendaftaran;
use App\Models\Model_tim_mahasiswa;
use Dompdf\Dompdf;

class Pendaftaran extends BaseController
{
    // halaman utama dashboard
    public function index()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_mahasiswa();
            $konf_kry               = new Model_karyawan();
            $konf_mhs               = new Model_mahasiswa();
            $konf_pdf               = new Model_Pendaftaran();
            $konf_pdtl              = new Model_dtl_pendaftaran();
            $konfigurasi            = $konf_kon->listening();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $pendaftaran            = $konf_pdf->notifikasi();
            $jumlah_pendaftaran     = $konf_pdf->jumlah_notifikasi();
            $data_pendaftar         = $konf_pdf->listening();
            $tahun                  = $konf_pdf->tahun();
            $dtl_pendaftaran        = $konf_pdtl->listening();
            $jumlah                 = $konf_pdf->jumlah_data();
            
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            
            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['tgl_selesai']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['tgl_selesai'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['tgl_selesai']);
            }

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
            for ($i = 0; $i < count($data_pendaftar); $i++) {
                $tanggal = new \DateTime(substr($data_pendaftar[$i]['tanggal'],0,10));
                $konfersi = $tanggal->format('D');
                $data_pendaftar[$i]['tanggal'] = hari($konfersi) . ", " . tgl_indo(substr($data_pendaftar[$i]['tanggal'],0,10));
            }

            $karyawan[0]['nama_kawan'] = ucwords($karyawan[0]['nama_kawan']);
            $karyawan[1]  = $data_pendaftar;
            $karyawan[2]  = $dtl_pendaftaran;
            $karyawan[3]  = $tahun;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[30] = $pendaftaran;
            $karyawan[31] = $jumlah_pendaftaran->jumlah;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $data = [
                'title'             => 'Pendaftaran (' . $jumlah->jumlah . ')',
                'layout'            => 'Pendaftaran',
                'sub_layout'        => '-',
                'menu_active'       => 'pendaftaran',
                'submenu_active'    => '-',
                'database'          => $karyawan,
                'content'           => 'admin/pendaftaran/index'
            ];

            return view('admin/layout/wrapper', $data);
        }
    }

    public function notifikasi()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_mahasiswa();
            $konf_kry               = new Model_karyawan();
            $konf_mhs               = new Model_mahasiswa();
            $konf_pdf               = new Model_Pendaftaran();
            $konf_pdtl              = new Model_dtl_pendaftaran();
            $konfigurasi            = $konf_kon->listening();
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $pendaftaran            = $konf_pdf->notifikasi();
            $jumlah_pendaftaran     = $konf_pdf->jumlah_notifikasi();
            $dtl_pendaftaran        = $konf_pdtl->listening();
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            
            // konversi data
            for ($i = 0; $i < count($tim_masa_habis); $i++) {
                $keluar = new \DateTime($tim_masa_habis[$i]['tgl_selesai']);
                $hari_keluar = $keluar->format('D');
                $tim_masa_habis[$i]['tgl_selesai'] = hari($hari_keluar) . ", " . tgl_indo($tim_masa_habis[$i]['tgl_selesai']);
            }

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

            $karyawan[0]['nama_kawan'] = ucwords($karyawan[0]['nama_kawan']);
            $karyawan[1]  = $dtl_pendaftaran;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[30] = $pendaftaran;
            $karyawan[31] = $jumlah_pendaftaran->jumlah;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $data = [
                'title'             => 'Notifikasi',
                'jumlah'           => $jumlah_pendaftaran->jumlah,
                'menu_active'       => '-',
                'submenu_active'    => '-',
                'database'          => $karyawan,
                'content'           => 'admin/pendaftaran/notifikasi'
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
            $konf_pdf               = new Model_Pendaftaran();
            $konf_pdtl              = new Model_dtl_pendaftaran();
            $pendaftaran            = $konf_pdf->filter_listening($this->request->getGet('id'));
            $detail                 = $konf_pdtl->filter_listening($this->request->getGet('id'));
            
            // konversi data
            for ($i = 0; $i < count($pendaftaran); $i++) {
                $tanggal = new \DateTime(substr($pendaftaran[$i]['tanggal'],0,10));
                $konfersi = $tanggal->format('D');
                $pendaftaran[$i]['tanggal'] = hari($konfersi) . ", " . tgl_indo(substr($pendaftaran[$i]['tanggal'],0,10));
            }

            $database[0]     = $pendaftaran;
            $database[1]     = $detail;

            $data = [
                'database'          =>  $database
            ];

            return view('admin/pendaftaran/viewNotifikasi', $data);
        }
    }

    public function terima()
    {
        $konf_pdf               = new Model_Pendaftaran();
        $data = [
            'kd_pendaftaran'        => $this->request->getGet('id'),
            'status_pendaftaran'    => 'diterima',
        ];

        $konf_pdf->notifikasiPerson($data);
        session()->setFlashdata('flash', 'berhasil');
        echo "<script>javascript:history.go(-1)</script>";
    }

    public function tidak_terima()
    {
        $konf_pdf               = new Model_Pendaftaran();
        $data = [
            'kd_pendaftaran'        => $this->request->getGet('id'),
            'status_pendaftaran'    => 'tidak diterima',
        ];

        $konf_pdf->notifikasiPerson($data);
        echo "<script>javascript:history.go(-1)</script>";
    }

    public function proses()
    {
        $konf_pdf               = new Model_Pendaftaran();

        if (isset($_POST['button'])) {
            $data = [
                'kd_pendaftaran'        => $this->request->getPost('id'),
                'status_pendaftaran'    => 'diterima',
            ];
    
            $konf_pdf->notifikasiPerson($data);
            session()->setFlashdata('flash', 'berhasil');
            echo "<script>javascript:history.go(-1)</script>";
          } else {
            $data = [
                'kd_pendaftaran'        => $this->request->getPost('id'),
                'status_pendaftaran'    => 'tidak diterima',
            ];
    
            $konf_pdf->notifikasiPerson($data);
            session()->setFlashdata('flash', 'berhasil');
            echo "<script>javascript:history.go(-1)</script>";
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
            $konf_pdf               = new Model_pendaftaran();
            $konf_kon               = new Model_konfigurasi();
            $konf_pdtl              = new Model_dtl_pendaftaran();
            $detail                 = $konf_pdtl->listening();
            $konfigurasi            = $konf_kon->listening();

            if($this->request->getPost('statusPendaftar') == 'all' && $this->request->getPost('tahun') == 'all'){
                $karyawan[1] = $konf_pdf->all_all();
            }else if($this->request->getPost('statusPendaftar') != 'all' && $this->request->getPost('tahun') != 'all'){
                $karyawan[1] = $konf_pdf->person_person($this->request->getPost('statusPendaftar'), $this->request->getPost('tahun'));
            }else if($this->request->getPost('statusPendaftar') == 'all' && $this->request->getPost('tahun') != 'all'){
                $karyawan[1] = $konf_pdf->all_person($this->request->getPost('tahun'));
            }else if($this->request->getPost('statusPendaftar') != 'all' && $this->request->getPost('tahun') == 'all'){
                $karyawan[1] = $konf_pdf->person_all($this->request->getPost('statusPendaftar'));
            }

            $karyawan[2]  = $detail;
            $karyawan[20] = $konfigurasi;

            $data = [
                'database'          => $karyawan
            ];

            if($this->request->getPost('generate')[0] == "pdf"){
                date_default_timezone_set('Asia/Jakarta');
                $nama_file = date('Ymdhis').'-Pendaftar';
                $html = view('admin/pendaftaran/cetak', $data);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->set_option('isRemoteEnabled', true);
                $dompdf->render();
                $dompdf->stream($nama_file.'.pdf');
            }else{
                return view('admin/pendaftaran/cetak',$data);
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