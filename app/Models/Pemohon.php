<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemohon extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'pemohon';

    public function getAlamat(): string
    {
        $detail_alamat = $this->attributes['detail_alamat'];
        $kelurahan = $this->attributes['kelurahan'];
        $kecamatan = $this->attributes['kecamatan'];
        $kabupaten = $this->attributes['kabupaten'];
        $provinsi = $this->attributes['provinsi'];
        return "{$detail_alamat},{$kelurahan},{$kecamatan},{$kabupaten},{$provinsi}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function izin(): HasMany
    {
        return $this->hasMany(Izin::class);
    }

    public function dokumen_pemohon(): HasMany
    {
        return $this->hasMany(DokumenPemohon::class);
    }
    
}