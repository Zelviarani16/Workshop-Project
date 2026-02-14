<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Kategori::insert([
            ['nama_kategori' => 'Novel'],
            ['nama_kategori' => 'Biografi'],
            ['nama_kategori' => 'Komik'],
        ]);
    }
}
