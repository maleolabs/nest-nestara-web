<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JenisPerizinan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'jenis_perizinan';

    public function syarat_izin(): BelongsToMany
    {
        return $this->belongsToMany(SyaratIzin::class, 'jenis_perizinan_syarat_izin')->withTimestamps();
    }
}