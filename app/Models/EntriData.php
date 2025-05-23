<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntriData extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'entri_data';

    public function jenis_perizinan(): BelongsTo
    {
        return $this->belongsTo(JenisPerizinan::class);
    }

    public function entri_data_izin(): HasMany
    {
        return $this->hasMany(EntriDataIzin::class);
    }
    
}