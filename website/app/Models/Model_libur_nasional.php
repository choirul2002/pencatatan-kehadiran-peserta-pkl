<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_libur_nasional extends Model
{
    protected $table      = 'tabel_libur_nasional';
    protected $primaryKey = 'ID_LBR';
    protected $allowedFields = ['ID_LBR', 'TANGGAL_LBR', 'KEGIATAN_LBR'];

    //filter seluruh data jabatan
    public function listening()
    {
        return $this->db->table('tabel_libur_nasional')
            ->orderBy('TANGGAL_LBR', 'DESC')
            ->get()->getResultArray();
    }
    
    //jumlah seluruh jabatan
    public function jumlah_libur()
    {
        return $this->db->table('tabel_libur_nasional')
            ->select('COUNT(*) AS jumlah')
            ->get()->getRow();
    }

    //jumlah seluruh jabatan
    public function cek_libur($tanggal)
    {
        return $this->db->table('tabel_libur_nasional')
            ->select('COUNT(*) AS jumlah')
            ->where('TANGGAL_LBR', $tanggal)
            ->get()->getRow();
    }

    public function tambah_data_libur($data)
    {
        return $this->db->table('tabel_libur_nasional')
            ->insert($data);
    }
    
    // edit data jabatan
    public function edit_data_libur($data)
    {
        return $this->db->table('tabel_libur_nasional')
            ->where('ID_LBR', $data['ID_LBR'])
            ->update($data);
    }

    // hapus data jabatan
    public function hapus_data_libur($data)
    {
        return $this->db->table('tabel_libur_nasional')
            ->delete(['ID_LBR' => $data['ID_LBR']]);
    }
    
    //filter data jabatan berdasarkan kode jabatan
    public function filter_data_libur($kode)
    {
        return $this->db->table('tabel_libur_nasional')
            ->where('ID_LBR', $kode)
            ->get()->getResultArray();
    }
}
