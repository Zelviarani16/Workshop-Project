<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'idbuku';
    public $timestamps = false; 
    protected $fillable = [
        'idkategori',
        'kode',
        'judul',
        'pengarang'
    ];

    public function kategori()
    {
        // 1 Buku milik satu Kategori
        return $this->belongsTo(Kategori::class, 'idkategori', 'idkategori');
    }
}
