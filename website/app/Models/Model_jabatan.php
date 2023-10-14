<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_jabatan extends Model
{
    protected $table      = 'tabel_jabatan';
    protected $primaryKey = 'KD_JBTN';
    protected $allowedFields = ['KD_JBTN', 'NAMA_JBTN'];

    //filter seluruh data jabatan
    public function listening()
    {
        return $this->db->table('tabel_jabatan')
            ->orderBy('NAMA_JBTN', 'ASC')
            ->get()->getResultArray();
    }

    //filter data jabatan berdasarkan kode jabatan
    public function filter_data_jabatan($kode_jabatan)
    {
        return $this->db->table('tabel_jabatan')
            ->where('KD_JBTN', $kode_jabatan)
            ->get()->getResultArray();
    }

    //jumlah seluruh jabatan
    public function jumlah_jabatan()
    {
        return $this->db->table('tabel_jabatan')
            ->select('COUNT(*) AS jumlah')
            ->get()->getRow();
    }

    // edit data jabatan
    public function edit_data_jabatan($data)
    {
        return $this->db->table('tabel_jabatan')
            ->where('KD_JBTN', $data['KD_JBTN'])
            ->update($data);
    }

    // hapus data jabatan
    public function hapus_data_jabatan($data)
    {
        return $this->db->table('tabel_jabatan')
            ->delete(['KD_JBTN' => $data['KD_JBTN']]);
    }


    // cari kode tertinggi
    public function max_kode_jabatan()
    {
        return $this->db->table('tabel_jabatan')
            ->selectMax('KD_JBTN')
            ->get()->getRow();
    }

    // tambah data tim
    public function tambah_data_jabatan($data)
    {
        return $this->db->table('tabel_jabatan')
            ->insert($data);
    }
    
    //jumlah data izin mahasiswa
    public function cek_data_relasi_karyawan($data)
    {
        return $this->db->query("SELECT * FROM tabel_jabatan WHERE NOT EXISTS (SELECT * FROM tabel_karyawan WHERE KD_JBTN = tabel_jabatan.KD_JBTN) AND KD_JBTN = '$data'")->getResultArray();
    }
}
