<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_peserta extends Model
{
    protected $table      = 'tabel_peserta';
    protected $primaryKey = 'KD_PST';
    protected $allowedFields = ['KD_PST', 'KD_ASAL', 'KD_AKUN', 'NAMA_PST', 'JK_PST', 'AGAMA_PST', 'ALAMAT_PST', 'NOHP_PST', 'FOTO_PST', 'STATUS_PST', 'TAHUN_PST'];
    
    //filter semua data
    public function filt_mhs($kode)
    {
        return $this->db->table('tabel_peserta')
            ->where('KD_PST', $kode)
            ->get()->getResultArray();
    }

    //filter semua data
    public function filter_kode($kode_akun)
    {
        return $this->db->table('tabel_peserta')
            ->where('KD_AKUN', $kode_akun)
            ->get()->getResultArray();
    }

    //filter semua data
    public function listening_mhs()
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

        //jumlah data izin mahasiswa
        public function list_data($tanggal)
        {
                return $this->db->query("SELECT*FROM tabel_peserta, tabel_asal, tabel_akun
                WHERE KD_PST NOT IN (SELECT KD_PST FROM tabel_tim_peserta NATURAL JOIN tabel_dtl_tim_peserta WHERE STATUS_TIM = 'aktif' AND TGL_SELESAI_TIM < '$tanggal')
                AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
                AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
                ORDER BY STATUS_PST ASC, TAHUN_PST DESC, NAMA_PST ASC")->getResultArray();
        }


    public function generate_all_all_all()
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }
    
    //filter generate tim all all person
    public function generate_all_all_person($TAHUN_PST)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('TAHUN_PST', $TAHUN_PST)
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }
    
    //filter generate tim all person all
    public function generate_all_person_all($status)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('STATUS_PST', $status)
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter generate tim all person all
    public function kosong_all_person_all()
    {
        return $this->db->query("SELECT*FROM tabel_peserta, tabel_asal, tabel_akun
        WHERE KD_PST NOT IN (SELECT KD_PST FROM tabel_tim_peserta NATURAL JOIN tabel_dtl_tim_peserta)
        AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
        ORDER BY STATUS_PST ASC, TAHUN_PST DESC, NAMA_ASAL ASC, NAMA_PST ASC")->getResultArray();
    }

    public function kosong_all_person_person($TAHUN_PST)
    {
        return $this->db->query("SELECT*FROM tabel_peserta, tabel_asal, tabel_akun
        WHERE KD_PST NOT IN (SELECT KD_PST FROM tabel_tim_peserta NATURAL JOIN tabel_dtl_tim_peserta)
        AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
        AND TAHUN_PST = '$TAHUN_PST'
        ORDER BY STATUS_PST ASC, TAHUN_PST DESC, NAMA_ASAL ASC, NAMA_PST ASC")->getResultArray();
    }

    //filter generate tim all person all
    public function menunggu_all_person_all($tanggal)
    {
        return $this->db->query("SELECT*FROM tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_asal, tabel_akun
        WHERE tabel_tim_peserta.KD_TIM NOT IN (SELECT tabel_tim_peserta.KD_TIM FROM tabel_tim_peserta WHERE STATUS_TIM = 'aktif' AND TGL_MULAI_TIM < '$tanggal')
        AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
        AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
        AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
        AND STATUS_TIM = 'aktif'")->getResultArray();
    }

    public function menunggu_all_person_person($tanggal, $TAHUN_PST)
    {
        return $this->db->query("SELECT*FROM tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_asal, tabel_akun
        WHERE tabel_tim_peserta.KD_TIM NOT IN (SELECT tabel_tim_peserta.KD_TIM FROM tabel_tim_peserta WHERE STATUS_TIM = 'aktif' AND TGL_MULAI_TIM < '$tanggal')
        AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
        AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
        AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
        AND TAHUN_PST = '$TAHUN_PST'
        AND STATUS_TIM = 'aktif'")->getResultArray();
    }

    //filter generate tim person person all
    public function kosong_person_person_all($kode)
    {
            return $this->db->query("SELECT*FROM tabel_peserta, tabel_asal, tabel_akun
            WHERE KD_PST NOT IN (SELECT KD_PST FROM tabel_tim_peserta NATURAL JOIN tabel_dtl_tim_peserta)
            AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
            AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
            AND tabel_peserta.KD_ASAL = '$kode'
            ORDER BY STATUS_PST ASC, TAHUN_PST DESC, NAMA_ASAL ASC, NAMA_PST ASC")->getResultArray();
    }

    //filter generate tim person status
    public function kosong_person_person_person($kode, $TAHUN_PST)
    {
            return $this->db->query("SELECT*FROM tabel_peserta, tabel_asal, tabel_akun
            WHERE KD_PST NOT IN (SELECT KD_PST FROM tabel_tim_peserta NATURAL JOIN tabel_dtl_tim_peserta)
            AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
            AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
            AND tabel_peserta.KD_ASAL = '$kode'
            AND TAHUN_PST = '$TAHUN_PST'
            ORDER BY STATUS_PST ASC, TAHUN_PST DESC, NAMA_ASAL ASC, NAMA_PST ASC")->getResultArray();
    }

    public function menunggu_person_person_all($kode, $tanggal)
    {
        return $this->db->query("SELECT*FROM tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_asal, tabel_akun
        WHERE tabel_tim_peserta.KD_TIM NOT IN (SELECT tabel_tim_peserta.KD_TIM FROM tabel_tim_peserta WHERE STATUS_TIM = 'aktif' AND TGL_MULAI_TIM < '$tanggal')
        AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
        AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
        AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
        AND tabel_peserta.KD_ASAL = '$kode'
        AND STATUS_TIM = 'aktif'")->getResultArray();
    }

    //filter generate tim person status
    public function menunggu_person_person_person($kode, $tanggal, $TAHUN_PST)
    {
        return $this->db->query("SELECT*FROM tabel_tim_peserta, tabel_dtl_tim_peserta, tabel_peserta, tabel_asal, tabel_akun
        WHERE tabel_tim_peserta.KD_TIM NOT IN (SELECT tabel_tim_peserta.KD_TIM FROM tabel_tim_peserta WHERE STATUS_TIM = 'aktif' AND TGL_MULAI_TIM < '$tanggal')
        AND tabel_tim_peserta.KD_TIM = tabel_dtl_tim_peserta.KD_TIM
        AND tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST
        AND tabel_peserta.KD_ASAL = tabel_asal.KD_ASAL
        AND tabel_peserta.KD_AKUN = tabel_akun.KD_AKUN
        AND TAHUN_PST = '$TAHUN_PST'
        AND tabel_peserta.KD_ASAL = '$kode'
        AND STATUS_TIM = 'aktif'")->getResultArray();
    }

    //filter generate tim all status TAHUN_PST
    public function generate_all_person_person($status, $TAHUN_PST)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('TAHUN_PST', $TAHUN_PST)
            ->where('STATUS_PST', $status)
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }
    
    //filter generate tim person all all
    public function generate_person_all_all($kode)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('tabel_asal.KD_ASAL', $kode)
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter generate tim person all person
    public function generate_person_all_person($kode, $TAHUN_PST)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('tabel_asal.KD_ASAL', $kode)
            ->where('TAHUN_PST', $TAHUN_PST)
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter generate tim person person all
    public function generate_person_person_all($kode, $status)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('tabel_asal.KD_ASAL', $kode)
            ->where('STATUS_PST', $status)
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter generate tim person status
    public function generate_person_person_person($kode, $status, $TAHUN_PST)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('tabel_asal.KD_ASAL', $kode)
            ->where('STATUS_PST', $status)
            ->where('TAHUN_PST', $TAHUN_PST)
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('TAHUN_PST', 'DESC')
            ->orderBy('NAMA_ASAL', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter semua data
    public function listening()
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST')
            ->join('tabel_tim_peserta', 'tabel_tim_peserta.kd_tim = tabel_dtl_tim_peserta.kd_tim')
            ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter semua data
    public function list_mhs()
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST')
            ->join('tabel_tim_peserta', 'tabel_tim_peserta.kd_tim = tabel_dtl_tim_peserta.kd_tim')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter semua data mahasiswa
    public function listening_mahasiswa_aktif()
    {
        return $this->db->table('tabel_peserta')
            // ->orderBy('STATUS_PST', 'ASC')
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }
    
    //jumlah seluruh mahasiswa pkl
    public function jumlah_mahasiswa_pkl()
    {
        return $this->db->table('tabel_peserta')
            ->select('COUNT(*) AS jumlah')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->get()->getRow();
    }

    //filter data mahasiswa pkl berdasarkan kd_mahasiswa
    public function filter_akun($akun)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('tabel_akun.KD_AKUN', $akun)
            ->get()->getResultArray();
    }

    //filter data mahasiswa pkl berdasarkan kd_mahasiswa
    public function filter_data_mahasiswa_edit($kode_mahasiswa)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->where('tabel_peserta.KD_PST', $kode_mahasiswa)
            ->get()->getResultArray();
    }

    //filter data mahasiswa pkl berdasarkan kd_mahasiswa
    public function filter_data_mahasiswa($kode_mahasiswa)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_akun', 'tabel_akun.KD_AKUN = tabel_peserta.KD_AKUN')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST')
            ->join('tabel_tim_peserta', 'tabel_tim_peserta.kd_tim = tabel_dtl_tim_peserta.kd_tim')
            ->where('tabel_mahasiswa.KD_PST', $kode_mahasiswa)
            ->get()->getResultArray();
    }

    //filter data mahasiswa pkl yang masa pkl sudah habi
    public function filter_mahasiswa_masa_habis($tgl_sekarang)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST')
            ->join('tabel_tim_peserta', 'tabel_tim_peserta.kd_tim = tabel_dtl_tim_peserta.kd_tim')
            ->where('STATUS_PST', 'aktif')
            ->where('TGL_SELESAI_TIM <', $tgl_sekarang)
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getResultArray();
    }

    //filter data mahasiswa pkl yang masa pkl sudah habis limit
    public function filter_mahasiswa_masa_habis_limit($tgl_sekarang)
    {
        return $this->db->table('tabel_peserta')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST')
            ->join('tabel_tim_peserta', 'tabel_tim_peserta.kd_tim = tabel_dtl_tim_peserta.kd_tim')
            ->where('STATUS_PST', 'aktif')
            ->where('TGL_SELESAI_TIM <=', $tgl_sekarang)
            ->orderBy('NAMA_PST', 'ASC')
            ->limit(5)
            ->get()->getResultArray();
    }

    //jumlsh data mahasiswa pkl yang masa pkl sudah habi
    public function jumlah_mahasiswa_masa_habis($tgl_sekarang)
    {
        return $this->db->table('tabel_peserta')
            ->select('COUNT(*) AS jumlah')
            ->join('tabel_asal', 'tabel_asal.KD_ASAL = tabel_peserta.KD_ASAL')
            ->join('tabel_dtl_tim_peserta', 'tabel_dtl_tim_peserta.KD_PST = tabel_peserta.KD_PST')
            ->join('tabel_tim_peserta', 'tabel_tim_peserta.kd_tim = tabel_dtl_tim_peserta.kd_tim')
            ->where('STATUS_PST', 'aktif')
            ->where('TGL_SELESAI_TIM <=', $tgl_sekarang)
            ->orderBy('NAMA_PST', 'ASC')
            ->get()->getRow();
    }

    //jumlah data telat mahasiswa
    public function jumlah_terlambat_mahasiswa()
    {
        return $this->db->query("SELECT tabel_peserta.KD_PST, COUNT(STATUS) AS terlambat FROM tabel_peserta LEFT JOIN tabel_absensi ON tabel_absensi.KD_PST = tabel_peserta.KD_PST AND KEHADIRAN = 'terlambat' GROUP BY KD_PST ORDER BY NAMA_PST ASC")->getResultArray();
    }

    //jumlah data hadir mahasiswa
    public function jumlah_hadir_mahasiswa()
    {
        return $this->db->query("SELECT tabel_peserta.KD_PST, COUNT(STATUS) AS hadir FROM tabel_peserta LEFT JOIN tabel_absensi ON tabel_absensi.KD_PST = tabel_peserta.KD_PST AND STATUS = 'hadir' GROUP BY KD_PST ORDER BY NAMA_PST ASC")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function jumlah_izin_mahasiswa()
    {
        return $this->db->query("SELECT tabel_peserta.KD_PST, COUNT(STATUS) AS izin FROM tabel_peserta LEFT JOIN tabel_absensi ON tabel_absensi.KD_PST = tabel_peserta.KD_PST AND STATUS = 'izin' AND STATUS_SURAT = 'approve' GROUP BY KD_PST ORDER BY NAMA_PST ASC")->getResultArray();
    }

    // menonaktifkan mahasiswa yang habis masa pkl
    public function nonaktif_mahasiswa($data)
    {
        return $this->db->table('tabel_peserta')
            ->where('KD_PST', $data['KD_PST'])
            ->update($data);
    }

    // edit data mahasiswa pkl
    public function edit_data_mahasiswa($data)
    {
        return $this->db->table('tabel_peserta')
            ->where('KD_PST', $data['KD_PST'])
            ->update($data);
    }

    // cari kode tertinggi
    public function max_kode_mahasiswa()
    {
        return $this->db->table('tabel_peserta')
            ->selectMax('KD_PST')
            ->get()->getRow();
    }

    // tambah data tim
    public function tambah_data_mahasiswa($data)
    {
        return $this->db->table('tabel_peserta')
            ->insert($data);
    }

    // hapus data mahasiswa
    public function hapus_data_mahasiswa($data)
    {
        return $this->db->table('tabel_peserta')
            ->delete(['KD_AKUN' => $data['KD_AKUN']]);
    }
    
    public function TAHUN_PST()
    {
        return $this->db->query("SELECT TAHUN_PST FROM tabel_peserta GROUP BY TAHUN_PST ASC")->getResultArray();
    }
    
    //jumlah data izin mahasiswa
    public function cek_data_relasi_logpos($data)
    {
        return $this->db->query("SELECT * FROM tabel_peserta WHERE NOT EXISTS (SELECT * FROM tabel_logpos WHERE KD_PST = tabel_peserta.KD_PST) AND KD_AKUN = '$data'")->getResultArray();
    }
    
    //jumlah data izin mahasiswa
    public function cek_data_relasi_absensi($data)
    {
        return $this->db->query("SELECT * FROM tabel_peserta WHERE NOT EXISTS (SELECT * FROM tabel_absensi WHERE KD_PST = tabel_peserta.KD_PST) AND KD_AKUN = '$data'")->getResultArray();
    }
    
    //jumlah data izin mahasiswa
    public function cek_data_relasi_dtl_tim_mahasiswa($data)
    {
        return $this->db->query("SELECT * FROM tabel_peserta WHERE NOT EXISTS (SELECT * FROM TABEL_dtl_tim_PESERTA WHERE KD_PST = tabel_peserta.KD_PST) AND KD_AKUN = '$data'")->getResultArray();
    }
    
    //jumlah data izin mahasiswa
    public function mahasiswa_lakilaki()
    {
        return $this->db->query("SELECT TAHUN_PST, JK_PST, COUNT(*) AS jumlah FROM tabel_peserta WHERE JK_PST = 'Laki-laki' GROUP BY TAHUN_PST,JK_PST ORDER BY TAHUN_PST ASC")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function mahasiswa_perempuan()
    {
        return $this->db->query("SELECT TAHUN_PST, JK_PST, COUNT(*) AS jumlah FROM tabel_peserta WHERE JK_PST = 'Perempuan' GROUP BY TAHUN_PST,JK_PST ORDER BY TAHUN_PST ASC")->getResultArray();
    }
    
    //jumlah data izin mahasiswa
    public function statusmahasiswa($tanggal)
    {
        return $this->db->query("SELECT STATUS_PST, COUNT(*) AS jumlah FROM tabel_peserta,tabel_dtl_tim_peserta,tabel_tim_peserta WHERE tabel_peserta.KD_PST = tabel_dtl_tim_peserta.KD_PST AND tabel_dtl_tim_peserta.KD_TIM = tabel_tim_peserta.KD_TIM AND TGL_MULAI_TIM <= '$tanggal' GROUP BY STATUS_PST")->getResultArray();
    }

    //jumlah data izin mahasiswa
    public function agamamahasiswa()
    {
        return $this->db->query("SELECT AGAMA_PST, COUNT(*) AS jumlah FROM tabel_peserta GROUP BY AGAMA_PST")->getResultArray();
    }

    public function tahun()
    {
        return $this->db->query("SELECT TAHUN_PST FROM tabel_peserta GROUP BY TAHUN_PST ASC")->getResultArray();
    }
}
