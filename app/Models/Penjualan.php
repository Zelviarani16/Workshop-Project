<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    // Matikan timestamps otomatis Laravel karena kita kelola sendiri
    public $timestamps = false;

    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';

    protected $fillable = [
        'timestamp',
        'total'
    ];

    // Relasi: satu penjualan punya banyak detail
    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id_penjualan');
    }
}