<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KartuController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LatihanJsController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UndanganController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WilayahController;
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


    // Scan barcode tag harga
    Route::get('/barang/scan', [BarangController::class, 'scan'])->name('barang.scan');
    Route::get('/barang/cari/{id_barang}', [BarangController::class, 'cariBarcode'])->name('barang.cari');


    Route::resource('barang', BarangController::class)->except(['show']);


    Route::get('tm4/tabel-biasa', [LatihanJsController::class, 'tabelBiasa'])
     ->name('tm4.tabel-biasa');

    Route::get('tm4/datatables', [LatihanJsController::class, 'datatables'])
     ->name('tm4.datatables');

    Route::get('tm4/select', [LatihanJsController::class, 'select'])
     ->name('tm4.select');

    //  atau
    // Route::get('/tm4/tabel-biasa', function () {
    //     return view('tm4/tabel-biasa');
    // })->name('tm4.tabel-biasa');

    Route::get('wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('wilayah/kota', [WilayahController::class, 'getKota'])->name('wilayah.kota');
    Route::get('wilayah/kecamatan', [WilayahController::class, 'getKecamatan'])->name('wilayah.kecamatan');
    Route::get('wilayah/kelurahan', [WilayahController::class, 'getKelurahan'])->name('wilayah.kelurahan');


    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::get('/kasir/cari-barang', [KasirController::class, 'cariBarang'])->name('kasir.cariBarang');
    Route::post('/kasir/bayar', [KasirController::class, 'bayar'])->name('kasir.bayar');


    Route::prefix('vendor')->name('vendor.')->group(function () {
    // Kelola Menu
    Route::get('/menu', [VendorController::class, 'menu'])->name('menu');
    // Tambah menu
    Route::post('/menu', [VendorController::class, 'storeMenu'])->name('menu.store');
    // Hapus menu
    Route::delete('/menu/{idmenu}', [VendorController::class, 'destroyMenu'])->name('menu.destroy');
    // Lihat pesanan lunas
    Route::get('/pesanan', [VendorController::class, 'pesanan'])->name('pesanan');

    Route::get('/scan-qr', [VendorController::class, 'scanQr'])->name('scan-qr');
    Route::get('/scan-qr/cari/{idpesanan}', [VendorController::class, 'cariPesanan'])->name('cari-pesanan');
});
});


// ============ CUSTOMER ============
// Halaman pemesanan (tidak perlu login)
Route::get('/pesan', [CustomerController::class, 'index'])->name('pesan.index');

// AJAX: ambil menu berdasarkan vendor yang dipilih
Route::get('/pesan/menu', [CustomerController::class, 'getMenu'])->name('pesan.menu');

// Proses checkout: simpan pesanan + minta token Midtrans
Route::post('/pesan/checkout', [CustomerController::class, 'checkout'])->name('pesan.checkout');

// Halaman status setelah bayar
Route::get('/pesan/status/{idpesanan}', [CustomerController::class, 'status'])->name('pesan.status');


// ============ MIDTRANS WEBHOOK ============
// Endpoint ini dipanggil otomatis oleh Midtrans saat pembayaran berhasil
// PENTING: harus dikecualikan dari CSRF verification!
// Midtrans TIDAK BISA kirim CSRF token karena dia server eksternal
Route::post('/midtrans/callback', [MidtransController::class, 'callback'])
     ->name('midtrans.callback');


// Customer
Route::get('/customer/data', [CustomerController::class, 'dataCustomer'])->name('customer.data');
Route::get('/customer/tambah1', [CustomerController::class, 'tambahCustomer1'])->name('customer.tambah1');
Route::post('/customer/tambah1', [CustomerController::class, 'storCustomer1'])->name('customer.store1');
Route::get('/customer/tambah2', [CustomerController::class, 'tambahCustomer2'])->name('customer.tambah2');
Route::post('/customer/tambah2', [CustomerController::class, 'storCustomer2'])->name('customer.store2');


Route::get('/customer/edit/{id}',    [CustomerController::class, 'editCustomer'])->name('customer.edit');
Route::post('/customer/update/{id}', [CustomerController::class, 'updateCustomer'])->name('customer.update');
Route::post('/customer/delete/{id}', [CustomerController::class, 'destroyCustomer'])->name('customer.destroy');



// Halaman QR Code customer (bisa diakses kapan saja)
Route::get('/pesan/qrcode/{idpesanan}', [CustomerController::class, 'qrcode'])->name('pesan.qrcode');

// Halaman riwayat pesanan customer (dari localStorage)
Route::get('/pesan/riwayat', function() {
    return view('customer.riwayat');
})->name('pesan.riwayat');





// VENDOR
// Semua route vendor pakai middleware auth (harus login)
// Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
//     // Kelola Menu
//     Route::get('/menu', [VendorController::class, 'menu'])->name('menu');
//     // Tambah menu
//     Route::post('/menu', [VendorController::class, 'storeMenu'])->name('menu.store');
//     // Hapus menu
//     Route::delete('/menu/{idmenu}', [VendorController::class, 'destroyMenu'])->name('menu.destroy');
//     // Lihat pesanan lunas
//     Route::get('/pesanan', [VendorController::class, 'pesanan'])->name('pesanan');
// });

//     Route::get('welcome-fabo', function () {
//         return view('welcome-fabo');
//     })->name('welcome-fabo');


//     Route::get('/belajar',         fn() => view('belajar.index')  )->name('belajar');
// Route::get('/tentang',         fn() => view('tentang')         )->name('tentang');
// Route::get('/darurat',         fn() => view('darurat')         )->name('darurat');
 
// Sub-halaman belajar
// Route::get('/belajar/luka',    fn() => view('belajar.luka')   )->name('belajar.luka');
// Route::get('/belajar/bakar',   fn() => view('belajar.bakar')  )->name('belajar.bakar');
// Route::get('/belajar/darurat', fn() => view('belajar.darurat'))->name('belajar.darurat');
// Route::get('/belajar/rjp',     fn() => view('belajar.rjp')    )->name('belajar.rjp');
 


// Route::get('/kartu/{kodeQr}', [KartuController::class, 'show'])
//     ->name('kartu.show');

// ===== KARTU EKSKLUSIF (dari scan QR) =====
// URL: /kartu/{kode}
// Contoh QR mengarah ke: https://fabo.id/kartu/luka-bakar
