<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImportDraft extends Model
{
    protected $fillable = [
        'name',
        'original_filename',
        'file_path',
        'status',
        'total_rows',
        'finalized_at',
    ];

    protected function casts(): array
    {
        return [
            'finalized_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(ImportDraftItem::class);
    }
}