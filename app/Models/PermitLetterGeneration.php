<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermitLetterGeneration extends Model
{
    protected $guarded = ['id'];
    public const QUEUED = 'queued';
    public const PROCESSING = 'processing';
    public const COMPLETED = 'completed';
    public const FAILED = 'failed';
}
