<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_asal extends Model
{
    protected $table      = 'tabel_asal';
    protected $primaryKey = 'KD_ASAL';
    protected $allowedFields = ['KD_ASAL', 'NAMA_ASAL', 'ALAMAT_ASAL', 'TELP_ASAL', 'FAX_ASAL', 'WEBSITE_ASAL', 'KATEGORI_ASAL'];

    //filter keseluruhan data kampus
    public function listening()
    {
        return $this->db->table('tabel_asal')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->get()->getResultArray();
    }

    public function listening_filter($kategori)
    {
        return $this->db->table('tabel_asal')
            ->where('KATEGORI_ASAL', $kategori)
            ->orderBy('NAMA_ASAL', 'ASC')
            ->get()->getResultArray();
    }

    //filter data kampus berdasarkan kd_kampus
    public function filter_data_kampus($kode_kampus)
    {
        return $this->db->table('tabel_asal')
            ->where('KD_ASAL', $kode_kampus)
            ->get()->getResultArray();
    }

    //jumlah seluruh kampus
    public function jumlah_kampus()
    {
        return $this->db->table('tabel_asal')
            ->select('COUNT(*) AS jumlah')
            ->get()->getRow();
    }

    // edit data kampus
    public function edit_data_kampus($data)
    {
        return $this->db->table('tabel_asal')
            ->where('KD_ASAL', $data['KD_ASAL'])
            ->update($data);
    }

    // hapus data kampus
    public function hapus_data_kampus($data)
    {
        return $this->db->table('tabel_asal')
            ->delete(['KD_ASAL' => $data['KD_ASAL']]);
    }

    // cari kode tertinggi
    public function max_kode_kampus()
    {
        return $this->db->table('tabel_asal')
            ->selectMax('KD_ASAL')
            ->get()->getRow();
    }

    // tambah data tim
    public function tambah_data_kampus($data)
    {
        return $this->db->table('tabel_asal')
            ->insert($data);
    }
    
    //jumlah data izin mahasiswa
    public function cek_data_relasi_mahasiswa($data)
    {
        return $this->db->query("SELECT * FROM tabel_asal WHERE NOT EXISTS (SELECT * FROM tabel_peserta WHERE KD_ASAL = tabel_asal.KD_ASAL) AND KD_ASAL = '$data'")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function cek_data_relasi_tim_mahasiswa($data)
    {
        return $this->db->query("SELECT * FROM tabel_asal WHERE NOT EXISTS (SELECT * FROM tabel_tim_peserta WHERE KD_ASAL = tabel_asal.KD_ASAL) AND KD_ASAL = '$data'")->getResultArray();
    }
    
    //jumlah data izin mahasiswa
    public function jumlahtimsetiapkampus()
    {
        return $this->db->query("SELECT NAMA_ASAL, COUNT(*) AS jumlah FROM tabel_tim_peserta NATURAL JOIN tabel_asal GROUP BY KD_ASAL ORDER BY jumlah DESC LIMIT 3")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function jumlahmahasiswa()
    {
        return $this->db->query("SELECT NAMA_ASAL, COUNT(*) AS jumlah FROM tabel_peserta NATURAL JOIN tabel_asal GROUP BY KD_ASAL ORDER BY jumlah DESC LIMIT 3")->getResultArray();
    }
}
