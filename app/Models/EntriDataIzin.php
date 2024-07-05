<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntriDataIzin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'entri_data_izin';

    public function entri_data(): BelongsTo
    {
        return $this->belongsTo(EntriData::class);
    }
    
}