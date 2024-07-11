<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeranPegawai extends Model
{
    use HasFactory;

    protected $table = 'peran_pegawai';

    protected $guarded = ['id'];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
    
}