<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $primaryKey = 'idtoko';
    protected $fillable = [
        'barcode', 
        'nama_toko', 
        'latitude',
        'longitude', 
        'accuracy',
        'alamat'
    ];

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class, 'idtoko', 'idtoko');
    }
}