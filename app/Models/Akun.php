<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'akun';

    protected $fillable = [
        'name',
        'jenis_akun',
    ];

    public function transaksiSumber()
    {
        return $this->hasMany(Transaksi::class, 'id_sumber_dana');
    }

    public function transaksiTujuan()
    {
        return $this->hasMany(Transaksi::class, 'id_tujuan_transaksi');
    }
}