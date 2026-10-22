<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermitLetterGeneration extends Model
{
    protected $guarded = ['id'];
    public const QUEUED = 'queued';
    public const PROCESSING = 'processing';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';

    protected $casts = ['processing_at' => 'datetime'];

    public function izin(): BelongsTo
    {
        return $this->belongsTo(Izin::class, 'izin_id');
    }
}
