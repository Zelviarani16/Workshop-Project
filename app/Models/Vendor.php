<?php

namespace App\Models;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model {
    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';
    protected $fillable = ['nama_vendor', 'user_id'];

    // Satu vendor punya banyak menu
    public function menus() {
        return $this->hasMany(Menu::class, 'idvendor', 'idvendor');
    }

    // Vendor milik satu user
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}