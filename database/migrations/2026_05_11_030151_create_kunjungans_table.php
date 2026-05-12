<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id('idkunjungan');
            $table->foreignId('idtoko')->constrained('tokos', 'idtoko');
            $table->decimal('lat_sales', 10, 7);
            $table->decimal('lng_sales', 10, 7);
            $table->decimal('acc_sales', 8, 2);
            $table->decimal('jarak', 10, 2); // meter
            $table->string('status'); // diterima / ditolak
            $table->timestamp('waktu')->useCurrent();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
