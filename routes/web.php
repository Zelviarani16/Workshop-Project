<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LatihanJsController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UndanganController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/auth/google', [AuthController::class, 'redirectGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'callbackGoogle']);


    Route::get('/otp', function () {
    return view('otp');
});

    Route::post('/otp', [AuthController::class, 'verifyOtp']);

    Route::get('/sertifikat', [PdfController::class, 'sertifikat'])->middleware('auth');


    Route::get('/preview-sertifikat', function () {
    return view('sertifikat');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/sertifikat', [PdfController::class, 'sertifikat'])
        ->name('sertifikat.generate');

    Route::get('/undangan/generate', [PdfController::class, 'undangan'])
    ->name('undangan.generate');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Buku
    Route::resource('buku', BukuController::class);

    // CRUD Kategori
    Route::resource('kategori', KategoriController::class);

    Route::post('barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');

    Route::resource('barang', BarangController::class);



    Route::get('tm4/tabel-biasa', [LatihanJsController::class, 'tabelBiasa'])
     ->name('tm4.tabel-biasa');

Route::get('tm4/datatables', [LatihanJsController::class, 'datatables'])
     ->name('tm4.datatables');

Route::get('tm4/select', [LatihanJsController::class, 'select'])
     ->name('tm4.select');
});