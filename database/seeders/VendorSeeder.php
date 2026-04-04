<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Menu;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user vendor 1
        $user1 = User::create([
            'name'     => 'Vendor Kantin Bu Sari',
            'email'    => 'vendor1@kantin.com',
            'password' => Hash::make('password'),
            'role'     => 'vendor',
        ]);

        // Buat data vendor 1
        $vendor1 = Vendor::create([
            'nama_vendor' => 'Kantin Bu Sari',
            'user_id'     => $user1->id,
        ]);

        // Buat menu untuk vendor 1
        Menu::insert([
            ['nama_menu' => 'Nasi Goreng',    'harga' => 15000, 'idvendor' => $vendor1->idvendor, 'created_at' => now(), 'updated_at' => now()],
            ['nama_menu' => 'Mie Goreng',     'harga' => 13000, 'idvendor' => $vendor1->idvendor, 'created_at' => now(), 'updated_at' => now()],
            ['nama_menu' => 'Es Teh Manis',   'harga' => 5000,  'idvendor' => $vendor1->idvendor, 'created_at' => now(), 'updated_at' => now()],
            ['nama_menu' => 'Ayam Geprek',    'harga' => 18000, 'idvendor' => $vendor1->idvendor, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Buat user vendor 2
        $user2 = User::create([
            'name'     => 'Vendor Kantin Pak Budi',
            'email'    => 'vendor2@kantin.com',
            'password' => Hash::make('password'),
            'role'     => 'vendor',
        ]);

        // Buat data vendor 2
        $vendor2 = Vendor::create([
            'nama_vendor' => 'Kantin Pak Budi',
            'user_id'     => $user2->id,
        ]);

        // Buat menu untuk vendor 2
        Menu::insert([
            ['nama_menu' => 'Bakso Spesial',  'harga' => 20000, 'idvendor' => $vendor2->idvendor, 'created_at' => now(), 'updated_at' => now()],
            ['nama_menu' => 'Mie Ayam',       'harga' => 15000, 'idvendor' => $vendor2->idvendor, 'created_at' => now(), 'updated_at' => now()],
            ['nama_menu' => 'Es Jeruk',       'harga' => 6000,  'idvendor' => $vendor2->idvendor, 'created_at' => now(), 'updated_at' => now()],
            ['nama_menu' => 'Soto Ayam',      'harga' => 17000, 'idvendor' => $vendor2->idvendor, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}