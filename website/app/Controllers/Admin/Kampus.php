<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_asal;
use App\Models\Model_peserta;
use App\Models\Model_konfigurasi;
use App\Models\Model_tim_peserta;
use Dompdf\Dompdf;

class Kampus extends BaseController
{
    //halaman utama kampus
    public function index()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_kmps              = new Model_asal();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $kampus                 = $konf_kmps->listening();
            $konfigurasi            = $konf_kon->listening();
            $jumlah                 = $konf_kmps->jumlah_kampus();
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
                'title'             => 'Asal Peserta (' . $jumlah->jumlah . ')',
                'layout'            => 'Asal Peserta',
                'sub_layout'        => 'Data Asal Peserta',
                'menu_active'       => 'kampus',
                'submenu_active'    => 'data_kampus',
                'database'          => $karyawan,
                'content'           => 'admin/kampus/index'
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
            $konf_kmps              = new Model_asal();
            $konf_kon               = new Model_konfigurasi();
            $konf_tim               = new Model_tim_peserta();
            $konf_mhs               = new Model_peserta();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $jumlah                 = $konf_kmps->jumlah_kampus();
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $konfigurasi            = $konf_kon->listening();
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

            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;

            $data = [
                'title'             => 'Asal Peserta (' . $jumlah->jumlah . ')',
                'layout'            => 'Asal Peserta',
                'sub_layout'        => 'Tambah Asal Peserta',
                'menu_active'       => 'kampus',
                'submenu_active'    => 'tambah_kampus',
                'database'          => $karyawan,
                'content'           => 'admin/kampus/tambah'
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
            $konf_kmps              = new Model_asal();
            $max_kode_kmps          = $konf_kmps->max_kode_kampus();
            $kode_kmps_baru  = "";
            $data = [];

            if ($max_kode_kmps->KD_ASAL) {
                $kode_kmps       = substr($max_kode_kmps->KD_ASAL, 2) + 1;
                
                if ($kode_kmps < 10) {
                    $kode_kmps_baru = "KM00000" . $kode_kmps;
                } else if ($kode_kmps >= 10 && $kode_kmps < 100) {
                    $kode_kmps_baru = "KM0000" . $kode_kmps;
                } else if ($kode_kmps >= 100 && $kode_kmps < 1000) {
                    $kode_kmps_baru = "KM000" . $kode_kmps;
                } else if ($kode_kmps >= 1000 && $kode_kmps < 10000) {
                    $kode_kmps_baru = "KM00" . $kode_kmps;
                } else if ($kode_kmps >= 10000 && $kode_kmps < 100000) {
                    $kode_kmps_baru = "KM0" . $kode_kmps;
                } else if ($kode_kmps >= 100000 && $kode_kmps < 1000000) {
                    $kode_kmps_baru = "KM" . $kode_kmps;
                }
            }

            if ($max_kode_kmps->KD_ASAL) {
                $data = [
                    'KD_ASAL'               => $kode_kmps_baru,
                    'NAMA_ASAL'             => $this->request->getPost('kampus'),
                    'ALAMAT_ASAL'           => $this->request->getPost('alamat'),
                    'TELP_ASAL'              => $this->request->getPost('telp'),
                    'FAX_ASAL'              => $this->request->getPost('fax'),
                    'WEBSITE_ASAL'          => $this->request->getPost('website'),
                    'KATEGORI_ASAL'              => $this->request->getPost('kategori')
                ];
            } else {
                $data = [
                    'KD_ASAL'               => 'KM000001',
                    'NAMA_ASAL'             => $this->request->getPost('kampus'),
                    'ALAMAT_ASAL'           => $this->request->getPost('alamat'),
                    'TELP_ASAL'              => $this->request->getPost('telp'),
                    'FAX_ASAL'              => $this->request->getPost('fax'),
                    'WEBSITE_ASAL'          => $this->request->getPost('website'),
                    'KATEGORI_ASAL'              => $this->request->getPost('kategori')
                ];
            }

            $konf_kmps->tambah_data_kampus($data);

            session()->setFlashdata('flash', 'berhasil');
            return redirect()->to(base_url('ak'));
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
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $kampus                 = $konf_kmps->filter_data_kampus($_GET['id']);
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $konfigurasi            = $konf_kon->listening();
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
                'title'             => ucwords($kampus[0]['NAMA_ASAL']),
                'layout'            => 'Asal Peserta',
                'sub_layout'        => 'Edit Asal Peserta',
                'menu_active'       => 'kampus',
                'submenu_active'    => 'edit_kampus',
                'database'          => $karyawan,
                'content'           => 'admin/kampus/edit'
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
            $konf_kmps              = new Model_asal();

            $data = [
                'KD_ASAL'                   => $this->request->getPost('kode'),
                'NAMA_ASAL'                 => $this->request->getPost('kampus'),
                'ALAMAT_ASAL'               => $this->request->getPost('alamat'),
                'TELP_ASAL'                  => $this->request->getPost('telp'),
                'FAX_ASAL'                  => $this->request->getPost('fax'),
                'WEBSITE_ASAL'              => $this->request->getPost('website'),
                'KATEGORI_ASAL'              => $this->request->getPost('kategori')
            ];

            $konf_kmps->edit_data_kampus($data);

            session()->setFlashdata('flash', 'berhasil');
            return redirect()->to(base_url('ak'));
        }
    }

    //hapus data
    public function hapus()
    {
        session();
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kmps              = new Model_asal();

            $data = [
                'KD_ASAL'       => $this->request->getGet('id')
            ];

            $data_tidak_terelasi_mahasiswa = $konf_kmps->cek_data_relasi_mahasiswa($this->request->getGet('id'));
            $data_tidak_terelasi_tim_mahasiswa = $konf_kmps->cek_data_relasi_tim_mahasiswa($this->request->getGet('id'));

            if($data_tidak_terelasi_mahasiswa && $data_tidak_terelasi_tim_mahasiswa){
                $konf_kmps->hapus_data_kampus($data);
                session()->setFlashdata('flash', 'berhasil');
                return redirect()->to(base_url('ak'));
            }else{
                session()->setFlashdata('flash', 'terelasi');
                return redirect()->to(base_url('ak'));
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
            $konf_kmps              = new Model_asal();
            $konf_kon               = new Model_konfigurasi();
            $konfigurasi            = $konf_kon->listening();

            if($this->request->getPost('kategori') == "all"){
                $kampus                 = $konf_kmps->listening();
            }else{
                $kampus                 =$konf_kmps->listening_filter($this->request->getPost('kategori'));
            }

            $karyawan[1] = $kampus;
            $karyawan[20] = $konfigurasi;

            $data = [
                'database'          => $karyawan
            ];


            if($this->request->getPost('generate')[0] == "pdf"){
                date_default_timezone_set('Asia/Jakarta');
                $nama_file = $this->request->getPost('bulan').$this->request->getPost('tahun').'-'.date('Ymdhis').'-Asal';
                $html = view('admin/kampus/cetak', $data);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'potrait');
                $dompdf->set_option('isRemoteEnabled', true);
                $dompdf->render();
                $dompdf->stream($nama_file.'.pdf');
            }else{
                return view('admin/kampus/cetak',$data);
            }
        }
    }

    //menampilkan kampus
    public function viewKampus()
        {
            session();
            if (!isset($_SESSION['akun_admin'])) {
                echo "<script>javascript:history.go(-1)</script>";
            } else {
                $konf_kmps               = new Model_asal();
                $kampus                  = $konf_kmps->filter_data_kampus($this->request->getGet('id'));
    
                $data = [
                    'kampus'          => $kampus
                ];
    
                return view('admin/kampus/kampus', $data);
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
