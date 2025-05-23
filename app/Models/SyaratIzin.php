<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyaratIzin extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'syarat_izin';

    public function jenis_perizinan(): BelongsToMany
    {
        return $this->belongsToMany(JenisPerizinan::class, 'jenis_perizinan_syarat_izin')->withTimestamps();
    }

    public function dokumen_pemohon(): HasMany
    {
        return $this->hasMany(DokumenPemohon::class);
    }
    
}