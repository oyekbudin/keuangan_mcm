<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PemetaanUraianTujuan extends Model
{
    protected $table = 'pemetaan_uraian_tujuan';

    protected $fillable = [
        'uraian',
        'uraian_hash',
        'id_tujuan_transaksi',
    ];

    public function tujuanTransaksi(): BelongsTo
    {
        return $this->belongsTo(
            Akun::class,
            'id_tujuan_transaksi'
        );
    }
}
