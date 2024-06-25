<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SyaratIzin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'syarat_izin';

    public function jenis_perizinan(): BelongsToMany
    {
        return $this->belongsToMany(JenisPerizinan::class, 'jenis_perizinan_syarat_izin')->withTimestamps();
    }
    
}