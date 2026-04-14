<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // Tabel vendor
        Schema::create('vendor', function (Blueprint $table) {
            $table->id('idvendor');
            $table->string('nama_vendor', 255);
            $table->unsignedBigInteger('user_id'); // FK ke users (untuk login vendor)
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });

        // Tabel menu
        Schema::create('menu', function (Blueprint $table) {
            $table->id('idmenu');
            $table->string('nama_menu', 255);
            $table->integer('harga');
            $table->string('path_gambar', 255)->nullable();
            $table->unsignedBigInteger('idvendor');
            $table->timestamps();

            $table->foreign('idvendor')
                  ->references('idvendor')->on('vendor')
                  ->onDelete('cascade');
        });

        // Tabel pesanan (header transaksi)
        // + kolom tambahan untuk Midtrans
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('idpesanan');
            $table->string('nama', 255);            // nama guest: "Guest_0000001"
            $table->timestamp('timestamp')
                  ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('total');
            $table->string('metode_bayar')->default('QRIS');
            // status_bayar: 0=belum bayar, 1=lunas
            $table->smallInteger('status_bayar')->default(0);
            // Kolom tambahan untuk Midtrans
            $table->string('snap_token')->nullable();       // token dari Midtrans
            // Tanpa kolom ini, MidtransController tidak bisa tahu pesanan mana yang harus diubah statusnya!
            $table->string('midtrans_order_id')->nullable(); // ID unik pesanan di Midtrans
            // Snap token 
            // Kenapa disimpan ke database? Supaya kalau customer mau bayar lagi (misal popup ditutup), kamu bisa ambil snap_token yang sama dari database tanpa perlu minta baru ke Midtrans.

        });

        // Tabel detail_pesanan (item per transaksi)
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('iddetail_pesanan');
            $table->unsignedBigInteger('idmenu');
            $table->unsignedBigInteger('idpesanan');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->integer('subtotal');
            $table->timestamp('timestamp')
                  ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('catatan', 255)->nullable();

            $table->foreign('idmenu')
                  ->references('idmenu')->on('menu')
                  ->onDelete('cascade');

            $table->foreign('idpesanan')
                  ->references('idpesanan')->on('pesanan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('vendor');
    }
};