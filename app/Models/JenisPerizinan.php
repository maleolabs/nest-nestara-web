<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPerizinan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'jenis_perizinan';

    public function syarat_izin(): HasMany
    {
        return $this->hasMany(SyaratIzin::class, 'jenis_perizinan_syarat_izin', 'id', 'jenis_perizinan_id');
    }
}