<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Izin extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'izin';

    public function jenis_perizinan(): BelongsTo
    {
        return $this->belongsTo(JenisPerizinan::class);
    }

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(Pemohon::class);
    }

    public function status_izin(): HasMany
    {
        return $this->hasMany(StatusIzin::class);
    }

    public function entri_data_izin(): HasMany
    {
        return $this->hasMany(EntriDataIzin::class);
    }

    public function pengelola_layanan(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function penanda_tangan(): BelongsTo
    {
        return $this->belongsTo(PenandaTangan::class); 
    }

    public function tim_teknis(): BelongsToMany
    {
        return $this->belongsToMany(TimTeknis::class, 'izin_tim_teknis');
    }

    public function getNoPendaftaranAttribute($value)
    {
        $pad_value = str_pad($value, 5, '0', STR_PAD_LEFT);
        $created_date = Carbon::parse($this->attributes['created_at'])->format('dmY');
        return $created_date . '-' . $pad_value;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest_today = self::whereDate('created_at', Carbon::today()->toDateTimeString())->orderBy('no_pendaftaran', 'DESC')->first();
            $latest_no_pendaftaran = explode('-', $latest_today->no_pendaftaran);
            $no_pendaftaran = intval($latest_no_pendaftaran[1]);
            $model->no_pendaftaran = $latest_today ? $no_pendaftaran + 1 : 1;
        });
    }
    
}