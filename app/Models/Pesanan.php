<?php
namespace App\Models;
use App\Models\DetailPesanan;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model {
    public $timestamps = false;
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    protected $fillable = [
        'nama', 'timestamp', 'total',
        'metode_bayar', 'status_bayar',
        'snap_token', 'midtrans_order_id'
    ];

    // Satu pesanan punya banyak detail
    public function details() {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }
}