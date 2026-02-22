<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UndanganController;



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

Route::get('/pengumuman', [PdfController::class, 'pengumuman']);
Route::middleware(['auth'])->group(function () {

    Route::get('/sertifikat', [PdfController::class, 'sertifikat'])
        ->name('sertifikat.generate');

    Route::get('/undangan/generate', [UndanganController::class, 'generate'])
    ->name('undangan.generate');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Buku
    Route::resource('buku', BukuController::class);

    // CRUD Kategori
    Route::resource('kategori', KategoriController::class);


});