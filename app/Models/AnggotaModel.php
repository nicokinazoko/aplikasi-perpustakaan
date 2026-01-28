<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AnggotaModel extends Model
{
    use SoftDeletes;

    protected $table = "anggotas";
    public $timestamps = true;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_anggota',
        'tanggal_lahir',
        'nama',
        'max_pinjam'
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
