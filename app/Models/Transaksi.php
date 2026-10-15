<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kode_bukti',
        'date',
        'id_sumber_dana',
        'id_tujuan_transaksi',
        'nominal',
        'keterangan',
        'bukti',
        'bukti_nota',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function sumberDana()
    {
        return $this->belongsTo(Akun::class, 'id_sumber_dana');
    }

    public function tujuanTransaksi()
    {
        return $this->belongsTo(Akun::class, 'id_tujuan_transaksi');
    }
}