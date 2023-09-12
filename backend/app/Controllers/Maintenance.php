<?php

namespace App\Controllers;

use App\Models\Model_konfigurasi;

class Maintenance extends BaseController
{
    //halaman Maintenance
    public function index()
    {
        $konf_kon               = new Model_konfigurasi();
        $konfigurasi            = $konf_kon->listening();
    
        $data = [
            'database'          => $konfigurasi,
        ];
        
        return view('maintenance', $data);
    }

    public function sistem_aktif(){
        $konf_kon               = new Model_konfigurasi();

        $data = [
            'kd_konf'                 => '1',
            'simmapkl_admin'          => 'aktif'
        ];

        $konf_kon->edit_data($data);

        session()->set('akun_admin', $_SESSION['maintenance_admin']);
        session()->setFlashdata('flash', 'sistem_aktif');
        return redirect()->to(base_url('ad'));
    }
}
