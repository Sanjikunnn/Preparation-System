<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribute extends Model
{
    protected $table = 'distributes';

    protected $fillable = [
        'id_hasil_upper',
        'waktu_proses',
        'no_barcode',
        'barcode_image',

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

    public function hasilUpper()
    {
        return $this->belongsTo(HasilUpper::class, 'id_hasil_upper');
    }
}
