<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function pendanda_tangan(): HasMany
    {
        return $this->hasMany(PenandaTangan::class);
    }

}