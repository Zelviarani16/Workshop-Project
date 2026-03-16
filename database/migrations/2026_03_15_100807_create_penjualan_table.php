<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // tabel penjualan : menyimpan header transaksi
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id('id_penjualan');
            $table->timestamp('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('total');
        });

        // Tabel penjualan detail : menyimpan detail item per transaksi
        Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id('idpenjualan_detail');
            $table->unsignedBigInteger('id_penjualan'); // FK ke penjualan
            $table->string('id_barang', 50); // FK ke barang
            $table->smallInteger('jumlah');
            $table->integer('subtotal'); // harga x jumlah

            // FK ke tabel penjualan --> detail ini milik penjualan mana
            $table->foreign('id_penjualan')
                ->references('id_penjualan')->on('penjualan')
                ->onDelete('cascade'); // kalau penjualan dihapus, detailnya ikut terhapus

            // FK ke tabel barang 
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barang')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
        Schema::dropIfExists('penjualan');    }
};
