<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportDraftItem extends Model
{
    protected $fillable = [
        'import_draft_id',
        'row_number',
        'tanggal',
        'kode_bukti',
        'sumber_dana',
        'id_sumber_dana',
        'tujuan',
        'id_tujuan_transaksi',
        'nominal',
        'keterangan',
        'file_transfer',
        'file_nota',
        'is_checked',
        'errors',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'is_checked' => 'boolean',
            'errors' => 'array',
        ];
    }

    public function draft(): BelongsTo
    {
        return $this->belongsTo(ImportDraft::class, 'import_draft_id');
    }
}