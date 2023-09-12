<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_karyawan extends Model
{
    protected $table      = 'tabel_karyawan';
    protected $primaryKey = 'KD_KAWAN';
    protected $allowedFields = ['KD_KAWAN', 'KD_AKUN', 'KD_JBTN', 'NIP_KAWAN', 'NAMA_KAWAN', 'JK_KAWAN', 'AGAMA_KAWAN', 'ALAMAT_KAWAN', 'NOHP_KAWAN', 'FOTO_KAWAN'];

    //filter semua data karyawan
    public function listening()
    {
        return $this->db->table('tabel_karyawan')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN')
            ->join('tabel_jabatan', 'tabel_jabatan.KD_JBTN = tabel_karyawan.KD_JBTN')
            ->orderBy('NAMA_KAWAN', 'ASC')
            ->get()->getResultArray();
    }

    public function generate_all()
    {
        return $this->db->table('tabel_karyawan')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN')
            ->join('tabel_jabatan', 'tabel_jabatan.KD_JBTN = tabel_karyawan.KD_JBTN')
            ->orderBy('NAMA_KAWAN', 'ASC')
            ->orderBy('LEVEL', 'ASC')
            ->get()->getResultArray();
    }

    public function generate_person($level)
    {
        return $this->db->table('tabel_karyawan')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN')
            ->join('tabel_jabatan', 'tabel_jabatan.KD_JBTN = tabel_karyawan.KD_JBTN')
            ->where('LEVEL', $level)
            ->orderBy('NAMA_KAWAN', 'ASC')
            ->orderBy('LEVEL', 'ASC')
            ->get()->getResultArray();
    }

    //filter data keryawan berdasarkan kd_akun
    public function filter_kd_akun($kd_akun)
    {
        return $this->db->table('tabel_karyawan')
            ->join('tabel_jabatan', 'tabel_jabatan.KD_JBTN = tabel_karyawan.KD_JBTN')
            ->where('KD_AKUN', $kd_akun)
            ->get()->getResultArray();
    }

    //filter data karyawan berdasarkan kd_karyawan
    public function filter_kd_karyawan($kd_karyawan)
    {
        return $this->db->table('tabel_karyawan')
            ->where('KD_KAWAN', $kd_karyawan)
            ->join('tabel_jabatan', 'tabel_jabatan.KD_JBTN = tabel_karyawan.KD_JBTN')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN')
            ->get()->getResultArray();
    }

    //jumlah seluruh karyawan
    public function jumlah_karyawan()
    {
        return $this->db->table('tabel_karyawan')
            ->select('COUNT(*) AS jumlah')
            ->get()->getRow();
    }

    //filter data untuk pembimbing lapangan
    public function pembimbing_lapangan()
    {
        return $this->db->table('tabel_karyawan')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_karyawan.KD_AKUN')
            ->where('LEVEL', 'karyawan')
            ->orderBy('NAMA_KAWAN', 'ASC')
            ->get()->getResultArray();
    }

    // edit data profil
    public function edit_profil($data)
    {
        return $this->db->table('tabel_karyawan')
            ->where('KD_KAWAN', $data['KD_KAWAN'])
            ->update($data);
    }

    // edit data kampus
    public function edit_data_karyawan($data)
    {
        return $this->db->table('tabel_karyawan')
            ->where('KD_KAWAN', $data['KD_KAWAN'])
            ->update($data);
    }

    // hapus data tabel_jabatan
    public function hapus_data_karyawan($data)
    {
        return $this->db->table('tabel_karyawan')
            ->delete(['KD_AKUN' => $data['KD_AKUN']]);
    }


    // cari kode tertinggi
    public function max_kode_karyawan()
    {
        return $this->db->table('tabel_karyawan')
            ->selectMax('KD_KAWAN')
            ->get()->getRow();
    }

    // tambah data tim
    public function tambah_data_karyawan($data)
    {
        return $this->db->table('tabel_karyawan')
            ->insert($data);
    }
    
    //jumlah data izin mahasiswa
    public function cek_data_relasi_tim_mahasiswa($data)
    {
        return $this->db->query("SELECT * FROM tabel_karyawan WHERE NOT EXISTS (SELECT * FROM tabel_tim_peserta WHERE KD_KAWAN = tabel_karyawan.KD_KAWAN) AND KD_KAWAN = '$data'")->getResultArray();
    }
    
    //jumlah data izin mahasiswa
    public function kelamin()
    {
        return $this->db->query("SELECT JK_KAWAN, COUNT(*) AS jumlah FROM tabel_karyawan GROUP BY JK_KAWAN")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function jumlahtimdibimbing()
    {
        return $this->db->query("SELECT KD_KAWAN, COUNT(*) AS jumlah FROM tabel_tim_peserta NATURAL JOIN tabel_karyawan GROUP BY KD_KAWAN ORDER BY jumlah DESC LIMIT 3")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function datapembimbing()
    {
        return $this->db->query("SELECT KD_KAWAN, NAMA_KAWAN, COUNT(*) AS jumlah FROM tabel_tim_peserta NATURAL JOIN tabel_karyawan GROUP BY KD_KAWAN ORDER BY jumlah DESC LIMIT 3")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function agamakaryawan()
    {
        return $this->db->query("SELECT AGAMA_KAWAN, COUNT(*) AS jumlah FROM tabel_karyawan GROUP BY AGAMA_KAWAN")->getResultArray();
    }
    
    // tambah data tim
    public function cek_nip($data)
    {
        return $this->db->query("SELECT * FROM tabel_karyawan WHERE NIP_KAWAN = '$data'")->getResultArray();
    }
}
