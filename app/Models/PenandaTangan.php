<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenandaTangan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'pendanda_tangan';

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function izin(): HasMany
    {
        return $this->hasMany(Izin::class);
    }
    
}