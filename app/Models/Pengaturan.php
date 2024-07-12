<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';

    protected $guarded = ['id'];

    public function getValueAttribute($value)
    {
        switch ($this->tipe) {
            case 'int':
                return (int) $value;
            case 'boolean':
                return (bool) $value;
            case 'datetime':
                return Carbon::parse($value);
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function setValueAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['value'] = json_encode($value);
            $this->attributes['tipe'] = 'json';
        } else {
            $this->attributes['value'] = $value;
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}