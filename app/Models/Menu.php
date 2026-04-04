<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
    protected $table = 'menu';
    protected $primaryKey = 'idmenu';
    protected $fillable = ['nama_menu', 'harga', 'path_gambar', 'idvendor'];

    // Menu milik satu vendor
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }
}