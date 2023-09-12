<?php

namespace App\Controllers;

use App\Models\Model_konfigurasi;

class Error extends BaseController
{
    //halaman Maintenance
    public function index()
    {
        $konf_kon               = new Model_konfigurasi();
        $konfigurasi            = $konf_kon->listening();
    
        $data = [
            'database'          => $konfigurasi,
        ];
        
        return view('errors/filter', $data);
    }
}
