<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenProdukJadi extends Model
{
    protected $table = 'komponen_produk_jadis';

    protected $fillable = [
        'nama_komponen_produk_jadi',
        'qty',
    ];

    // Menghilangkan auto-increment dan ubah type primary key
    public $incrementing = false;
    protected $keyType = 'string';

    // Hook: sebelum menyimpan data baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Ambil ID terakhir dan tambahkan 1
            $last = self::orderBy('id', 'desc')->first();
            $lastId = $last ? intval($last->id) : 0;
            $model->id = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        });
    }
    
    public function produkJadiSebagaiKomponen1()
    {
        return $this->hasMany(ProdukJadi::class, 'id_komponen_produk_jadi_1');
    }

}
