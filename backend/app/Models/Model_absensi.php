<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_absensi extends Model
{
    protected $table      = 'tabel_absensi';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['ID', 'KD_PST', 'TGL', 'CHECK_IN', 'LOKASI_CHECK_IN', 'CHECK_OUT', 'LOKASI_CHECK_OUT', 'KETERANGAN', 'KEHADIRAN', 'SURAT', 'STATUS_SURAT', 'STATUS', 'KEGIATAN', 'LOKASI_KIRIM_SURAT'];

    //filter data mahasiswa izin person
    public function filter_data($tgl)
    {
        return $this->db->table('tabel_absensi')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->where('STATUS_SURAT !=', 'waiting')
            ->where('STATUS_SURAT !=', 'disapprove')
            ->like('TGL', $tgl)
            ->orderBy('TGL', 'DESC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

        //data diagram
        public function diagram($mulai, $selesai)
        {
            return $this->db->table('tabel_absensi')
                ->select('TGL, status, COUNT(TGL) AS jumlah')
                ->where('TGL >=', $mulai)
                ->where('TGL <=', $selesai)
                ->groupBy('TGL')
                ->groupBy('STATUS')
                ->orderBy('TGL', 'ASC')
                ->get()->getResultArray();
        }

    //filter data mahasiswa semua cetak
    public function filter_data_cetak($tgl)
    {
        return $this->db->table('tabel_absensi')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->where('STATUS_SURAT !=', 'waiting')
            ->where('STATUS_SURAT !=', 'disapprove')
            ->like('TGL', $tgl)
            ->orderBy('TGL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }
    
    //filter data mahasiswa person cetak
    public function filter_data_cetak_person($tgl,$kd_mhs)
    {
        return $this->db->table('tabel_absensi')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->where('STATUS_SURAT !=', 'waiting')
            ->where('STATUS_SURAT !=', 'disapprove')
            ->where('tabel_peserta.KD_PST', $kd_mhs)
            ->like('TGL', $tgl)
            ->orderBy('TGL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }
    
    //filter data mahasiswa person cetak all
    public function filter_data_cetak_tanggal_all($tgl)
    {
        return $this->db->table('tabel_absensi')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->where('STATUS_SURAT !=', 'waiting')
            ->where('STATUS_SURAT !=', 'disapprove')
            ->where('TGL', $tgl)
            ->orderBy('TGL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter data mahasiswa person cetak person
    public function filter_data_cetak_tanggal_person($tgl,$kd_mhs)
    {
        return $this->db->table('tabel_absensi')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->where('STATUS_SURAT !=', 'waiting')
            ->where('STATUS_SURAT !=', 'disapprove')
            ->where('tabel_peserta.KD_PST', $kd_mhs)
            ->where('TGL', $tgl)
            ->orderBy('TGL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter data mahasiswa izin person
    public function jumlah_data_absen()
    {
        return $this->db->table('tabel_absensi')
            ->select('COUNT(*) AS jumlah')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->where('STATUS_SURAT !=', 'waiting')
            ->where('STATUS_SURAT !=', 'disapprove')
            ->get()->getRow();
    }

    //filter jumlah izin yang waiting
    public function jumlah_izin()
    {
        return $this->db->table('tabel_absensi')
            ->select('COUNT(*) AS jumlah')
            ->where('STATUS_SURAT', 'waiting')
            ->get()->getRow();
    }

    //filter data izin yang waiting 
    public function filter_data_izin()
    {
        return $this->db->table('tabel_absensi')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->where('STATUS_SURAT', 'waiting')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter data izin yang waiting limit
    public function filter_data_izin_limit()
    {
        return $this->db->table('tabel_absensi')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->where('STATUS_SURAT', 'waiting')
            ->orderBy('NAMA_PST', 'ASC')
            ->limit(5)
            ->get()->getResultArray();
    }

    //filter data mahasiswa izin person
    public function filter_data_izin_person($id)
    {
        return $this->db->table('tabel_absensi')
            ->join('tabel_peserta', 'tabel_peserta.KD_PST = tabel_absensi.KD_PST')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->where('ID', $id)
            ->get()->getResultArray();
    }

    // edit status surat izin
    public function edit_surat_izin($data)
    {
        return $this->db->table('tabel_absensi')
            ->where('ID', $data['ID'])
            ->update($data);
    }
    
    //jumlah data telat mahasiswa
    public function tahun()
    {
        return $this->db->query("SELECT YEAR(TGL) AS tahun FROM tabel_absensi GROUP BY YEAR(TGL) ASC")->getResultArray();
    }
}
