<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_dtl_tim_peserta extends Model
{
    protected $table      = 'tabel_dtl_tim_peserta';
    protected $primaryKey = 'DTL_ID';
    protected $allowedFields = ['DTL_ID','KD_TIM', 'KD_PST'];

    //filter semua data
    public function listening()
    {
        return $this->db->table('tabel_dtl_tim_peserta')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->join('tabel_tim_peserta', 'tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM')
            // ->orderBy('status_mhs', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter semua data tim mahasiswa berdasarkan kode tim
    public function filter_data_dtl_tim_mahasiswa($kode_tim)
    {
        return $this->db->table('tabel_dtl_tim_peserta')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->where('KD_TIM', $kode_tim)
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter semua data tim mahasiswa berdasarkan kode tim
    public function filter_detail($kode_mahasiswa)
    {
        return $this->db->table('tabel_dtl_tim_peserta')
            ->where('KD_PST', $kode_mahasiswa)
            ->get()->getResultArray();
    }

        //filter semua data tim mahasiswa berdasarkan kode tim
        public function filter_detail1($kode_mahasiswa)
        {
            return $this->db->table('tabel_dtl_tim_peserta')
                ->join('tabel_tim_peserta', 'tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM')
                ->where('KD_PST', $kode_mahasiswa)
                ->get()->getResultArray();
        }
    
    //filter semua data tim mahasiswa berdasarkan kode tim
    public function filter_detail_home($kode)
    {
        return $this->db->table('tabel_dtl_tim_peserta')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->where('KD_TIM', $kode)
            ->get()->getResultArray();
    }

    // hapus data tim
    public function hapus_data_tim($data)
    {
        return $this->db->table('tabel_dtl_tim_peserta')
            ->delete(['KD_TIM' => $data['KD_TIM']]);
    }

    // tambah data tim
    public function tambah_data_tim($data)
    {
        return $this->db->table('tabel_dtl_tim_peserta')
            ->insert($data);
    }

}
