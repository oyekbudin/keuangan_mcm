<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujuanTransaksi extends Model
{
    protected $table = 'tujuan_transaksi';

    protected $fillable = [
        'name',
    ];
}
