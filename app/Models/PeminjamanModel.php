<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeminjamanModel extends Model
{
    use SoftDeletes;

    protected $table = "peminjamans";
    public $timestamps = true;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tanggal_pinjam',
        'anggota_id'
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
