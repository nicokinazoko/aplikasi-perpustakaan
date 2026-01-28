<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BukuModel extends Model
{
    use SoftDeletes;

    protected $table = "bukus";
    public $timestamps = true;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'judul_buku',
        'penerbit',
        'dimensi',
        'stok',
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
