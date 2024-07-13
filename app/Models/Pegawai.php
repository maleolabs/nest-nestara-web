<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'pegawai';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function peran_pegawai(): HasMany
    {
        return $this->hasMany(PeranPegawai::class);
    }

    public function izin_as_penanda_tangan(): HasOne
    {
        return $this->hasOne(Izin::class, 'penandatangan_id', 'id');
    }

    public function izin_as_tim_pemeriksa(): BelongsToMany
    {
        return $this->belongsToMany(Izin::class, 'izin_timpemeriksa', 'pegawai_id', 'izin_id');
    }

}