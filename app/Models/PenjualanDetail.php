<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    public $timestamps = false;

    protected $table = 'penjualan_detail';
    protected $primaryKey = 'idpenjualan_detail';

    protected $fillable = [
        'id_penjualan',
        'id_barang',
        'jumlah',
        'subtotal'
    ];

    // Relasi: detail ini milik satu penjualan
    // belongsTo = banyak detail -> satu penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }

    // Relasi: detail ini merujuk ke satu barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}