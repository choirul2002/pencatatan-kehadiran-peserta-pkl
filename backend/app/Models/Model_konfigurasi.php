<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_konfigurasi extends Model
{
    protected $table      = 'tabel_konfigurasi';
    protected $allowedFields = ['KD_KONF', 'NAMA_SISTEM', 'LOGO_SISTEM', 'LOGO_SISTEM', 'VERSI', 'PRE_SEKAM_MULAI', 'PRE_SEKAM_SELESAI', 'PRE_SEKAM_OUT', 'PRE_JUM_MULAI', 'PRE_JUM_SELESAI', 'PRE_JUM_OUT', 'LATITUDE_KONF','LONGITUDE_KONF','RADIUS_KONF','JUDUL_RADIUS'];
    //filter semua data
    public function listening()
    {
        return $this->db->table('tabel_konfigurasi')
            ->get()->getResultArray();
    }

    // edit data tabel_konfigurasi
    public function edit_data($data)
    {
        return $this->db->table('tabel_konfigurasi')
            ->where('KD_KONF', $data['KD_KONF'])
            ->update($data);
    }
}
