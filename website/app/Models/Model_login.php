<?php

namespace App\Models;

use CodeIgniter\Model;

class Model_login extends Model
{
    protected $table      = 'tabel_akun';
    protected $primaryKey = 'KD_AKUN';
    protected $allowedFields = ['KD_AKUN', 'EMAIL', 'PASSWORD', 'LEVEL'];

    public function validasi($email, $password)
    {
        return $this->db->table('tabel_akun')
            ->where('EMAIL', $email)
            ->where('PASSWORD', $password)
            ->get()->getResultArray();
    }
}
