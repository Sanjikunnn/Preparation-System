<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilUpper extends Model
{
    protected $table = 'hasil_uppers';

    protected $fillable = [
        'id_komponen_1',
        'id_komponen_2',
        'id_komponen_3',
        'id_produk_jadi',
        'waktu_proses',
        'no_barcode',
        'barcode_image',
        'total_proses',
        'distribute',
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

    // Relasi ke komponen
    public function komponen1()
    {
        return $this->belongsTo(KomponenProdukJadi::class, 'id_komponen_1');
    }

    public function komponen2()
    {
        return $this->belongsTo(KomponenProdukJadi::class, 'id_komponen_2');
    }

    public function komponen3()
    {
        return $this->belongsTo(KomponenProdukJadi::class, 'id_komponen_3');
    }

    // Relasi ke produk jadi
    public function produkJadi()
    {
        return $this->belongsTo(ProdukJadi::class, 'id_produk_jadi');
    }
    public function distributeRelation()
    {
        return $this->hasOne(Distribute::class, 'id_hasil_upper');
    }


}
