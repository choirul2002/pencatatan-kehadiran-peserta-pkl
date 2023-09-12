<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_tim_peserta extends Model
{
    protected $table      = 'tabel_tim_peserta';
    protected $primaryKey = 'KD_TIM';
    protected $allowedFields = ['KD_TIM', 'KD_KAWAN', 'NAMA_TIM', 'KD_ASAL', 'TGL_MULAI_TIM','TGL_SELESAI_TIM', 'STATUS_TIM', 'TAHUN_TIM'];

    //filter semua data
    public function listening()
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_karyawan', 'tabel_karyawan.KD_KAWAN = tabel_tim_peserta.KD_KAWAN')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->orderBy('STATUS_TIM', 'ASC')
            ->orderBy('TAHUN_TIM', 'DESC')
            ->orderBy('NAMA_TIM', 'ASC')
            ->get()->getResultArray();
    }

    public function listening1($tanggal)
    {
            return $this->db->query("SELECT * FROM tabel_tim_peserta, tabel_karyawan, tabel_asal 
            WHERE KD_TIM NOT IN (SELECT KD_TIM FROM tabel_tim_peserta WHERE TGL_SELESAI_TIM < '$tanggal' AND STATUS_TIM = 'aktif')
            AND tabel_tim_peserta.KD_KAWAN = tabel_karyawan.KD_KAWAN
            AND tabel_tim_peserta.KD_ASAL = tabel_asaL.KD_ASAL
            ORDER BY STATUS_TIM ASC, TAHUN_TIM DESC, NAMA_TIM ASC")->getResultArray();
    }

    //filter generate tim all all all
    public function generate_all_all_all()
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->orderBy('STATUS_TIM', 'ASC')
            ->orderBy('tabel_tim_peserta.TAHUN_TIM', 'DESC')
            ->orderBy('NAMA_TIM', 'ASC')
            ->get()->getResultArray();
    }

        //filter generate tim all all person
        public function generate_all_all_person($tahun)
        {
            return $this->db->table('tabel_tim_peserta')
                ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
                ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM')
                ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
                ->where('tabel_tim_peserta.TAHUN_TIM', $tahun)
                ->orderBy('STATUS_TIM', 'ASC')
                ->orderBy('tabel_tim_peserta.TAHUN_TIM', 'DESC')
                ->orderBy('NAMA_TIM', 'ASC')
                ->get()->getResultArray();
        }
    
    //filter generate tim all person all
    public function generate_all_person_all($status)
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->where('STATUS_TIM', $status)
            ->orderBy('STATUS_TIM', 'ASC')
            ->orderBy('tabel_tim_peserta.TAHUN_TIM', 'DESC')
            ->orderBy('NAMA_TIM', 'ASC')
            ->get()->getResultArray();
    }

    //filter generate tim all person all
    public function menunggu_all_person_all($tanggal)
    {
        return $this->db->query("SELECT*FROM tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_asal
        WHERE tabel_tim_peserta.KD_TIM NOT IN (SELECT tabel_tim_peserta.KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM < '$tanggal')
        AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
        AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL")->getResultArray();
    }
    
    //filter generate tim all status tahun
    public function menunggu_all_person_person($tanggal, $tahun)
    {
        return $this->db->query("SELECT*FROM tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_asal
        WHERE tabel_tim_peserta.KD_TIM NOT IN (SELECT tabel_tim_peserta.KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM < '$tanggal')
        AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
        AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
        AND TAHUN_TIM = '$tahun'
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL")->getResultArray();
    }

    //filter generate tim all status tahun
    public function generate_all_person_person($status, $tahun)
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->where('STATUS_TIM', $status)
            ->where('tabel_tim_peserta.TAHUN_TIM', $tahun)
            ->orderBy('STATUS_TIM', 'ASC')
            ->orderBy('tabel_tim_peserta.TAHUN_TIM', 'DESC')
            ->orderBy('NAMA_TIM', 'ASC')
            ->get()->getResultArray();
    }
    
    //filter generate tim person all all
    public function generate_person_all_all($kode)
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->where('tabel_asal.KD_ASAL', $kode)
            ->orderBy('STATUS_TIM', 'ASC')
            ->orderBy('tabel_tim_peserta.TAHUN_TIM', 'DESC')
            ->orderBy('NAMA_TIM', 'ASC')
            ->get()->getResultArray();
    }

    //filter generate tim person all person
    public function generate_person_all_person($kode, $tahun)
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->where('tabel_asal.KD_ASAL', $kode)
            ->where('tabel_tim_peserta.TAHUN_TIM', $tahun)
            ->orderBy('STATUS_TIM', 'ASC')
            ->orderBy('tabel_tim_peserta.TAHUN_TIM', 'DESC')
            ->orderBy('NAMA_TIM', 'ASC')
            ->get()->getResultArray();
    }

    //filter generate tim person person all
    public function generate_person_person_all($kode, $status)
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->where('tabel_asal.KD_ASAL', $kode)
            ->where('STATUS_TIM', $status)
            ->orderBy('STATUS_TIM', 'ASC')
            ->orderBy('tabel_tim_peserta.TAHUN_TIM', 'DESC')
            ->orderBy('NAMA_TIM', 'ASC')
            ->get()->getResultArray();
    }

    //filter generate tim person status
    public function generate_person_person_person($kode, $status, $tahun)
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST')
            ->where('tabel_asal.KD_ASAL', $kode)
            ->where('STATUS_TIM', $status)
            ->where('tabel_tim_peserta.TAHUN_TIM', $tahun)
            ->orderBy('STATUS_TIM', 'ASC')
            ->orderBy('tabel_tim_peserta.TAHUN_TIM', 'DESC')
            ->orderBy('NAMA_TIM', 'ASC')
            ->get()->getResultArray();
    }
    
    //filter generate tim person person all
    public function menunggu_person_person_all($kode, $tanggal)
    {
        return $this->db->query("SELECT*FROM tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_asal
        WHERE tabel_tim_peserta.KD_TIM NOT IN (SELECT tabel_tim_peserta.KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM < '$tanggal')
        AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
        AND tabel_tim_peserta.KD_ASAL = '$kode'
        AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL")->getResultArray();
    }

    public function menunggu_person_person_person($kode, $tanggal, $tahun)
    {
        return $this->db->query("SELECT*FROM tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_asal
        WHERE tabel_tim_peserta.KD_TIM NOT IN (SELECT tabel_tim_peserta.KD_TIM FROM tabel_tim_peserta WHERE TGL_MULAI_TIM < '$tanggal')
        AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
        AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
        AND TAHUN_TIM = '$tahun'
        AND tabel_tim_peserta.KD_ASAL = '$kode'
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL")->getResultArray();
    }

    //filter data tim mahasisa dengan kode akun
    public function filter_data_tim_mahasiswa($kode_tim)
    {
        return $this->db->table('tabel_tim_peserta')
            ->join('tabel_karyawan', 'tabel_karyawan.KD_KAWAN = tabel_tim_peserta.KD_KAWAN')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
            ->where('KD_TIM', $kode_tim)
            ->get()->getResultArray();
    }

    //filter data tim mahasisa dengan kode akun
    public function filter($kode_tim)
    {
        return $this->db->table('tabel_tim_peserta')
            ->where('KD_TIM', $kode_tim)
            ->get()->getResultArray();
    }
    
    //filter data tim mahasisa dengan kode akun
    public function tahun()
    {
        return $this->db->table('tabel_tim_peserta')
            ->select('TAHUN_TIM')
            ->orderBy('TAHUN_TIM', 'ASC')
            ->groupBy('TAHUN_TIM')
            ->get()->getResultArray();
    }

    //jumlah seluruh tabel_asal
    public function jumlah_tim_mahasiswa()
    {
        return $this->db->table('tabel_tim_peserta')
            ->select('COUNT(*) AS jumlah')
            ->get()->getRow();
    }

    // edit data tim
    public function edit_data_tim($data)
    {
        return $this->db->table('tabel_tim_peserta')
            ->where('KD_TIM', $data['KD_TIM'])
            ->update($data);
    }

    // hapus data tim
    public function hapus_tim($data)
    {
        return $this->db->table('tabel_tim_peserta')
            ->delete(['KD_TIM' => $data['KD_TIM']]);
    }

    // cari kode tertinggi
    public function max_kode_tim()
    {
        return $this->db->table('tabel_tim_peserta')
            ->selectMax('KD_TIM')
            ->get()->getRow();
    }

    // tambah data tim
    public function tambah_tim($data)
    {
        return $this->db->table('tabel_tim_peserta')
            ->insert($data);
    }

    // menonaktifkan mahasiswa yang habis masa pkl
    public function nonaktif_tim($data)
    {
        return $this->db->table('tabel_tim_peserta')
            ->where('KD_TIM', $data['KD_TIM'])
            ->update($data);
    }

    //jumlah seluruh tabel_asal
    public function jumlah_tim_masa_habis($tgl_sekarang)
    {
        return $this->db->table('tabel_tim_peserta')
            ->select('COUNT(*) AS jumlah')
            ->where('STATUS_TIM', 'aktif')
            ->where('TGL_SELESAI_TIM <', $tgl_sekarang)
            ->get()->getRow();
    }

        //filter data mahasiswa pkl yang masa pkl sudah habis limit
        public function filter_tim_masa_habis_limit($tgl_sekarang)
        {
            return $this->db->table('tabel_tim_peserta')
                ->where('STATUS_TIM', 'aktif')
                ->where('TGL_SELESAI_TIM <', $tgl_sekarang)
                ->orderBy('TGL_SELESAI_TIM', 'DESC')
                ->limit(5)
                ->get()->getResultArray();
        }
            //filter data mahasiswa pkl yang masa pkl sudah habis limit
            public function filter_tim_masa_habis($tgl_sekarang)
            {
                return $this->db->table('tabel_tim_peserta')
                    ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_tim_peserta.KD_ASAL')
                    ->join('tabel_karyawan', 'tabel_karyawan.KD_KAWAN = tabel_tim_peserta.KD_KAWAN')
                    ->where('STATUS_TIM', 'aktif')
                    ->where('TGL_SELESAI_TIM <', $tgl_sekarang)
                    ->orderBy('TGL_SELESAI_TIM', 'DESC')
                    ->get()->getResultArray();
            }

    //jumlah data izin mahasiswa
    public function cek_data_relasi_dtl_tim_mahasiswa($data)
    {
        return $this->db->query("SELECT * FROM tabel_tim_peserta WHERE NOT EXISTS (SELECT * FROM tabel_dtl_tim_peserta WHERE KD_TIM = tabel_tim_peserta.KD_TIM) AND KD_TIM = '$data'")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function jumlahtimaktif($tanggal)
    {
        return $this->db->query("SELECT STATUS_TIM, COUNT(*) AS jumlah FROM tabel_tim_peserta WHERE TGL_MULAI_TIM <= '$tanggal' GROUP BY STATUS_TIM")->getResultArray();
    }
}
