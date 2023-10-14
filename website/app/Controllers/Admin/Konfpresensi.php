<?php

namespace App\Controllers\Admin;

use App\Models\Model_karyawan;
use App\Models\Model_konfigurasi;
use App\Models\Model_peserta;
use App\Models\Model_tim_peserta;

class Konfpresensi extends BaseController
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
            $jumlah_mhs_masa_habis  = $konf_mhs->jumlah_mahasiswa_masa_habis(date('Y-m-d'));
            $karyawan               = $konf_kry->filter_kd_karyawan($_SESSION['akun_admin']);
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


            $karyawan[20] = $konfigurasi;
            $karyawan[10] = $jumlah_mhs_masa_habis->jumlah;
            $karyawan[11] = $mhs_masa_habis;

            $data = [
                'title'             => 'Konfigurasi',
                'menu_active'       => 'konfigurasi',
                'submenu_active'    => 'presensi',
                'database'          => $karyawan,
                'content'           => 'admin/konfigurasi/indexPresensi'
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
            $cek                    = $konf_kon->listening();


            if($this->request->getPost('sekam_mulai') < $this->request->getPost('sekam_selesai') && $this->request->getPost('sekam_selesai') < $this->request->getPost('sekam_out') && $this->request->getPost('jum_mulai') < $this->request->getPost('jum_selesai') && $this->request->getPost('jum_selesai') < $this->request->getPost('jum_out')){
                if (!$_FILES['logoSistem']['name']) {
                    $koonfigurasi = [
                        'KD_KONF'                 => $this->request->getPost('kode'),
                        'PRE_SEKAM_MULAI'         => $this->request->getPost('sekam_mulai'),
                        'PRE_SEKAM_SELESAI'       => $this->request->getPost('sekam_selesai'),
                        'PRE_JUM_MULAI'           => $this->request->getPost('jum_mulai'),
                        'PRE_JUM_SELESAI'         => $this->request->getPost('jum_selesai'),
                        'PRE_SEKAM_OUT'           => $this->request->getPost('sekam_out'),
                        'PRE_JUM_OUT'             => $this->request->getPost('jum_out'),
                        'LATITUDE_KONF'             => $this->request->getPost('latitude'),
                        'LONGITUDE_KONF'             => $this->request->getPost('longitude'),
                        'RADIUS_KONF'             => $this->request->getPost('radius'),
                        'JUDUL_RADIUS'             => $this->request->getPost('judul_radius'),
                        'NAMA_SISTEM'             => $this->request->getPost('namaSistem'),
                        'SINGKATAN'             => $this->request->getPost('singkatan'),
                        'VERSI'             => $this->request->getPost('versi')
                    ];
    
                    $konf_kon->edit_data($koonfigurasi);
                }else{
                    $logo         = $this->request->getFile('logoSistem');
            
                        if (file_exists("service/icon/" . $cek[0]['LOGO_SISTEM'])) {
                            unlink("service/icon/" . $cek[0]['LOGO_SISTEM']);
                            $logo->move(ROOTPATH . 'public/service/icon');
                        }
    
                    $koonfigurasi = [
                        'KD_KONF'                 => $this->request->getPost('kode'),
                        'PRE_SEKAM_MULAI'         => $this->request->getPost('sekam_mulai'),
                        'PRE_SEKAM_SELESAI'       => $this->request->getPost('sekam_selesai'),
                        'PRE_JUM_MULAI'           => $this->request->getPost('jum_mulai'),
                        'PRE_JUM_SELESAI'         => $this->request->getPost('jum_selesai'),
                        'PRE_SEKAM_OUT'           => $this->request->getPost('sekam_out'),
                        'PRE_JUM_OUT'             => $this->request->getPost('jum_out'),
                        'LATITUDE_KONF'             => $this->request->getPost('latitude'),
                        'LONGITUDE_KONF'             => $this->request->getPost('longitude'),
                        'RADIUS_KONF'             => $this->request->getPost('radius'),
                        'JUDUL_RADIUS'             => $this->request->getPost('judul_radius'),
                        'NAMA_SISTEM'             => $this->request->getPost('namaSistem'),
                        'SINGKATAN'             => $this->request->getPost('singkatan'),
                        'VERSI'             => $this->request->getPost('versi'),
                        'LOGO_SISTEM'            => $logo->getName()
                    ];
    
                    $konf_kon->edit_data($koonfigurasi);
                }

                if($cek[0]['LATITUDE_KONF'] == $this->request->getPost('latitude') && $cek[0]['LONGITUDE_KONF'] == $this->request->getPost('longitude') && $cek[0]['RADIUS_KONF'] == $this->request->getPost('radius') && $cek[0]['JUDUL_RADIUS'] == $this->request->getPost('judul_radius')){
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('akgip'));
                }else{
                    $options = array(
                        'cluster' => 'ap1',
                        'useTLS' => true
                      );
                      $pusher = new \Pusher\Pusher(
                        'c3100747ee53df61dca0',
                        '50fa6b7e45eeed3b1ee2',
                        '1460777',
                        $options
                      );
                    
                      $data['message'] = 'Service radius telah dirubah. Silahkan reset service';
                      $pusher->trigger('my-channel', 'service', $data);
                    
                    session()->setFlashdata('flash', 'berhasil');
                    return redirect()->to(base_url('akgip'));
                }
            }else{
                session()->setFlashdata('flash', 'datawaktu');
                return redirect()->to(base_url('akgip'));
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
