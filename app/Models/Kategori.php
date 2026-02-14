<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'idkategori'; 
    // protected $primaryKey = ['idkategori']; HARUS STRING BOSS BUKAN ARRAY
    public $timestamps = false; // kalau tabel kategori gak ada created_at / updated_at
    protected $fillable = ['nama_kategori'];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'idkategori');
    }
}
