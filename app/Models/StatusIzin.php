<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusIzin extends Model
{
    use HasFactory;

    protected $table = 'status_izin';

    protected $guarded = ['id'];

    public function izin(): BelongsTo
    {
        return $this->belongsTo(Izin::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function riwayat_proses_izin(): HasMany
    {
        return $this->hasMany(RiwayatProsesIzin::class);
    }
    
}