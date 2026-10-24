<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OcrKtpRequest extends Model
{
    protected $table = 'ocr_ktp_requests';

    protected $guarded = ['id'];

    protected $casts = ['public_check_id' => 'string', 'result' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
