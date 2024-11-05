<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisPerizinan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'jenis_perizinan';

    public function getNamaAttribute($value)
    {
        return str_capitalize($value, true);
    }

    public function getDurasiAttribute($value)
    {
        return $value . ' hari';
    }

    public function getMasaBerlakuAttribute($value)
    {
        return $value == 0 ? 'selamanya' : $value . ' tahun';
    }

    public function syarat_izin(): BelongsToMany
    {
        return $this->belongsToMany(SyaratIzin::class, 'jenis_perizinan_syarat_izin')->withTimestamps();
    }

    public function entri_data(): HasMany
    {
        return $this->hasMany(EntriData::class);
    }
}