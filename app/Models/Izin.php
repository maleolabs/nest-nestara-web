<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Izin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'izin';

    public function jenis_perizinan(): BelongsTo
    {
        return $this->belongsTo(JenisPerizinan::class);
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(Pemohon::class);
    }

    public function status_izin(): HasMany
    {
        return $this->hasMany(StatusIzin::class);
    }
    
}