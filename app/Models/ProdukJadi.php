<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukJadi extends Model
{
    protected $table = 'produk_jadis';

    protected $fillable = [
        'nama_produk_jadi',
        'id_komponen_produk_jadi_1',
        'id_komponen_produk_jadi_2',
        'id_komponen_produk_jadi_3',
        'qty',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::orderBy('id', 'desc')->first();
            $lastId = $last ? intval($last->id) : 0;
            $model->id = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        });
    }

    // Relasi ke KomponenProdukJadi
    public function komponen1()
    {
        return $this->belongsTo(KomponenProdukJadi::class, 'id_komponen_produk_jadi_1');
    }

    public function komponen2()
    {
        return $this->belongsTo(KomponenProdukJadi::class, 'id_komponen_produk_jadi_2');
    }

    public function komponen3()
    {
        return $this->belongsTo(KomponenProdukJadi::class, 'id_komponen_produk_jadi_3');
    }
}
