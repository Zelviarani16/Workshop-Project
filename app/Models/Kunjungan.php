<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'idkunjungan';
    protected $fillable = [
        'idtoko', 'lat_sales', 'lng_sales',
        'acc_sales', 'jarak', 'status', 'waktu'
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'idtoko', 'idtoko');
    }
}