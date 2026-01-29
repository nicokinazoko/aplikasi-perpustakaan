<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PengembalianModel extends Model
{
    use SoftDeletes;

    protected $table = "pengembalians";
    public $timestamps = true;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'peminjaman_id',
        'tanggal_kembali',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
