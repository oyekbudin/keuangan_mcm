<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoBulanan extends Model
{
    protected $table = 'saldo_bulanan';

    protected $fillable = [
        'akun_id',
        'tahun',
        'bulan',
        'saldo_awal',
        'total_masuk',
        'total_keluar',
        'saldo_akhir',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id');
    }
}
