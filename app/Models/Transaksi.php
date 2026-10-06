<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\SumberDana;
use App\Models\TujuanTransaksi;

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
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana');
    }

    public function tujuanTransaksi()
    {
        return $this->belongsTo(TujuanTransaksi::class, 'id_tujuan_transaksi');
    }
}
