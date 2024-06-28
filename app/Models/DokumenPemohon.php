<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPemohon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'dokumen_pemohon';

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(Pemohon::class);
    }

    public function jenis_perizinan(): BelongsTo
    {
        return $this->belongsTo(JenisPerizinan::class);
    }

    public function syarat_izin(): BelongsTo
    {
        return $this->belongsTo(SyaratIzin::class);
    }

}