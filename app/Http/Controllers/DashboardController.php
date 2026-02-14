<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Middleware auth untuk seluruh method di controller ini
     // Constructor
    public function __construct()
    {
        // Terapkan middleware 'auth' untuk semua method di controller ini
         $this->middleware('auth'); // Ini valid di Laravel
    }

    public function index()
    {
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();

        return view('dashboard', compact('totalBuku', 'totalKategori'));
    }
}