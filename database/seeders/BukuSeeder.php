<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Buku::insert([
            ['kode' => 'NV-01', 'judul' => 'Home Sweet Loan', 'pengarang' => 'Almira Bastari', 'idkategori' => 1]
        ]);
    }
}
