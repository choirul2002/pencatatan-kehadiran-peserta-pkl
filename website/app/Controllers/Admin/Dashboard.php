<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_peserta;
use App\Models\Model_tim_peserta;
use App\Models\Model_konfigurasi;
use App\Models\Model_asal;

class Dashboard extends BaseController
{
    // halaman utama dashboard
    public function index()
    {
        session();
        date_default_timezone_set('Asia/Jakarta');
        if (!isset($_SESSION['akun_admin'])) {
            echo "<script>javascript:history.go(-1)</script>";
        } else {
            $konf_kry               = new Model_karyawan();
            $konf_mhs               = new Model_peserta();
            $konf_kmps               = new Model_asal();
            $konf_tim               = new Model_tim_peserta();
            $konf_kon               = new Model_konfigurasi();
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
            $mhs_masa_habis         = $konf_mhs->filter_mahasiswa_masa_habis_limit(date('Y-m-d'));
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $tim_masa_habis         = $konf_tim->filter_tim_masa_habis_limit(date('Y-m-d'));
            $jumlah_tim_masa_habis  = $konf_tim->jumlah_tim_masa_habis(date('Y-m-d'));
            $jumlah_mahasiswa       = $konf_mhs->jumlah_mahasiswa_pkl();
            $jumlah_kampus          = $konf_kmps->jumlah_kampus();
            $jumlah_tim             = $konf_tim->jumlah_tim_mahasiswa();
            $jumlah_karyawan        = $konf_kry->jumlah_karyawan();
            $konfigurasi            = $konf_kon->listening();
            $lakilaki              =$konf_mhs->mahasiswa_lakilaki();
            $perempuan              =$konf_mhs->mahasiswa_perempuan();
            $kelaminkaryawan        =$konf_kry->kelamin();
            $jumlahaktifdantidakaktif   =$konf_mhs->statusmahasiswa(date('Y-m-d'));
            $jumlahtimaktifa        =$konf_tim->jumlahtimaktif(date('Y-m-d'));
            $jumlahtimkampus   =$konf_kmps->jumlahtimsetiapkampus();
            $jumlahmahasiswa  =$konf_kmps->jumlahmahasiswa();
            $jumlahtimdibimbing = $konf_kry->jumlahtimdibimbing();
            $datapembimbing = $konf_kry->datapembimbing();
            $jumlahagamakaryawan = $konf_kry->agamakaryawan();
            $jumlahagamamahasiswa = $konf_mhs->agamamahasiswa();

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
            

            $tahunMulai = date('Y') - 5;
            $tahunSelesai = date('Y');

            $jmlViews = ['0','0','0','0','0','0','0'];
            $tahun = ['0', '0', '0', '0', '0'];
            $tahunViews = ['0', '0', '0', '0', '0'];
            $diagramTahun = ['0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'];
            $nameBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];


            $dataDiagramViews = $diagramTahun;

            $thnViews = $tahunViews;

            $urutTahun = 0;
            for($i = date('Y')-4; $i<=date('Y'); $i++){
                $tahun[$urutTahun] = $i;
                $urutTahun++;
            }

            $thn = $tahun;

            $tanggalHari = array();
            if(date('d') < 8){
                for($i = 1; $i<=date('d'); $i++){
                    $hari = $i;
                    $bulan = date('m');
                    $tahun = date('Y');
                    $tanggal = $tahun.'-'.$bulan.'-'.$hari;
                    $date = date_create($tanggal);
                    $tglHasil =  date_format($date, 'D');
                    $hari = date('D');
      
                    if($i < 10){
                        array_push($tanggalHari, '0'.$i.' | '.hr($tglHasil));
                    }else{
                        array_push($tanggalHari, $i.' | '.hr($tglHasil));
                    }
                }
            }else{
                for($i = date('d')-6; $i<=date('d'); $i++){
                    $hari = $i;
                    $bulan = date('m');
                    $tahun = date('Y');
                    $tanggal = $tahun.'-'.$bulan.'-'.$hari;
                    $date = date_create($tanggal);
                    $tglHasil =  date_format($date, 'D');
                    $hari = date('D');
      
                    if($i < 10){
                        array_push($tanggalHari, '0'.$i.' | '.hr($tglHasil));
                    }else{
                        array_push($tanggalHari, $i.' | '.hr($tglHasil));
                    }
                }
            }

            date_default_timezone_set("Asia/Jakarta");
            date_default_timezone_get();

            $tahun = date("Y");
            $intTahun = intval($tahun);
            $formTahun = array();
            $formTahun[0] = $intTahun;

            for ($i = 1; $i <= 4; $i++) {
                $intTahun = $intTahun - 1;
                array_push($formTahun, $intTahun);
            }
            
            $formlakilaki = array();
            foreach ($formTahun as $tahun) {
              $jumlah = 0;
              foreach ($lakilaki as $data) {
                if ($data['TAHUN_PST'] == $tahun) {
                  $jumlah = $data['jumlah'];
                  break;
                }
              }
              $formlakilaki[] = array('tahun' => $tahun, 'jumlah' => $jumlah);
            }

            $formperempuan = array();
            foreach ($formTahun as $tahun) {
              $jumlah = 0;
              foreach ($perempuan as $data) {
                if ($data['TAHUN_PST'] == $tahun) {
                  $jumlah = $data['jumlah'];
                  break;
                }
              }
              $formperempuan[] = array('tahun' => $tahun, 'jumlah' => $jumlah);
            }

            $formpembimbing = array();
            foreach ($datapembimbing as $pembimbing) {
                $jumlah = 0;
                foreach ($jumlahtimdibimbing as $data) {
                  if ($data['KD_KAWAN'] == $pembimbing['KD_KAWAN']) {
                    $jumlah = $data['jumlah'];
                    break;
                  }
                }
                $formpembimbing[] = array('nama_kawan' => $pembimbing['NAMA_KAWAN'], 'jumlah' => $jumlah);
            }

            $arrayagama = ["Budha","Hindu","Islam","Konghucu","Kristen"];
            $formagamakaryawan = array();
            foreach ($arrayagama as $agama) {
                $jumlah = 0;
                foreach ($jumlahagamakaryawan as $data) {
                  if ($data['AGAMA_KAWAN'] == $agama) {
                    $jumlah = $data['jumlah'];
                    break;
                  }
                }
                $formagamakaryawan[] = array('agama' => $agama, 'jumlah' => $jumlah);
            }

            $formagamamahasiswa = array();
            foreach ($arrayagama as $agama) {
                $jumlah = 0;
                foreach ($jumlahagamamahasiswa as $data) {
                  if ($data['AGAMA_PST'] == $agama) {
                    $jumlah = $data['jumlah'];
                    break;
                  }
                }
                $formagamamahasiswa[] = array('agama' => $agama, 'jumlah' => $jumlah);
            }

            $warnapembimbing = array();
            for($i = 1; $i<=count($datapembimbing); $i++){
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                $color = sprintf("#%02x%02x%02x", $red, $green, $blue);

                array_push($warnapembimbing, $color);
            }

            $warna1 = array();
            for($i = 1; $i<=count($jumlahtimkampus); $i++){
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                $color = sprintf("#%02x%02x%02x", $red, $green, $blue);

                array_push($warna1, $color);
            }

            $warna2 = array();
            for($i = 1; $i<=count($jumlahmahasiswa); $i++){
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                $color = sprintf("#%02x%02x%02x", $red, $green, $blue);

                array_push($warna2, $color);
            }

            rsort($formTahun);
            $formTahun = array_reverse($formTahun);
            rsort($formlakilaki);
            $formlakilaki = array_reverse($formlakilaki);
            rsort($formperempuan);
            $formperempuan = array_reverse($formperempuan);

            $warnapeserta = array();
            for($i = 1; $i<=2; $i++){
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                $color = sprintf("#%02x%02x%02x", $red, $green, $blue);

                array_push($warnapeserta, $color);
            }

            $warnakaryawan = array();
            for($i = 1; $i<=2; $i++){
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                $color = sprintf("#%02x%02x%02x", $red, $green, $blue);

                array_push($warnakaryawan, $color);
            }

            $warnastatuspeserta = array();
            for($i = 1; $i<=2; $i++){
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                $color = sprintf("#%02x%02x%02x", $red, $green, $blue);

                array_push($warnastatuspeserta, $color);
            }

            $warnastatustim = array();
            for($i = 1; $i<=2; $i++){
                $red = rand(0, 255);
                $green = rand(0, 255);
                $blue = rand(0, 255);
                $color = sprintf("#%02x%02x%02x", $red, $green, $blue);

                array_push($warnastatustim, $color);
            }

            $karyawan[0]['NAMA_KAWAN'] = ucwords($karyawan[0]['NAMA_KAWAN']);
            $karyawan[3] = $jumlah_mahasiswa;
            $karyawan[4] = $jumlah_kampus;
            $karyawan[5] = $jumlah_tim;
            $karyawan[6] = $jumlah_karyawan;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;
            $karyawan[20] = $konfigurasi;
            $karyawan[34]  = $tanggalHari;
            $karyawan[35]  = $jmlViews;
            $karyawan[36]  = $thn;
            $karyawan[41]  = $thnViews;
            $karyawan[42]  = $nameBulan;
            $karyawan[43]  = $dataDiagramViews;
            $karyawan[80] = $jumlah_tim_masa_habis->jumlah;
            $karyawan[81] = $tim_masa_habis;
            $karyawan[60] = $kelaminkaryawan;
            $karyawan[61] = $jumlahaktifdantidakaktif;
            $karyawan[62] = $jumlahtimaktifa;
            $karyawan[63] = $jumlahtimkampus;
            $karyawan[64] = $warna1;
            $karyawan[65] = $formlakilaki;
            $karyawan[66] = $formperempuan;
            $karyawan[67] = $formTahun;
            $karyawan[68] = $jumlahmahasiswa;
            $karyawan[69] = $warna2;
            $karyawan[70] = $warnapeserta;
            $karyawan[71] = $warnakaryawan;
            $karyawan[72] = $warnastatuspeserta;
            $karyawan[73] = $warnastatustim;
            $karyawan[74] = $formpembimbing;
            $karyawan[75] = $warnapembimbing;
            $karyawan[76] = $formagamamahasiswa;
            $karyawan[77] = $formagamakaryawan;

            $data = [
                'title'             => 'Dashboard',
                'menu_active'       => 'dashboard',
                'submenu_active'    => '-',
                'database'          => $karyawan,
                'content'           => 'admin/dashboard/index'
            ];
            return view('admin/layout/wrapper', $data);
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


function hr($date)
{
    $hari = $date;

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Min";
            break;

        case 'Mon':
            $hari_ini = "Sen";
            break;

        case 'Tue':
            $hari_ini = "Sel";
            break;

        case 'Wed':
            $hari_ini = "Rab";
            break;

        case 'Thu':
            $hari_ini = "Kam";
            break;

        case 'Fri':
            $hari_ini = "Jum";
            break;

        case 'Sat':
            $hari_ini = "Sab";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return $hari_ini;
}
