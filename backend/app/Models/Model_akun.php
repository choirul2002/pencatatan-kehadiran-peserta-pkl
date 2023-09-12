<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_akun extends Model
{
    protected $table      = 'tabel_akun';
    protected $primaryKey = 'KD_AKUN';
    protected $allowedFields = ['KD_AKUN', 'EMAIL', 'PASSWORD','LEVEL'];

    // edit data akun
    public function edit_akun($data)
    {
        return $this->db->table('tabel_akun')
            ->where('KD_AKUN', $data['KD_AKUN'])
            ->update($data);
    }

    // cari kode tertinggi
    public function max_kode_akun()
    {
        return $this->db->table('tabel_akun')
            ->selectMax('KD_AKUN')
            ->get()->getRow();
    }

    // tambah data tim
    public function tambah_data_akun($data)
    {
        return $this->db->table('tabel_akun')
            ->insert($data);
    }

    // tambah data tim
    public function hapus_data_akun($data)
    {
        return $this->db->table('tabel_akun')
            ->delete(['KD_AKUN' => $data['KD_AKUN']]);
    }
    
    // tambah data tim
    public function cek_email($data)
    {
        return $this->db->query("SELECT * FROM tabel_akun WHERE EMAIL = '$data'")->getResultArray();
    }
}
