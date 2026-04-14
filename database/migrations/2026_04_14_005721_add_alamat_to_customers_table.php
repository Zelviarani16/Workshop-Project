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
    Schema::table('customers', function (Blueprint $table) {
        $table->string('alamat')->nullable();
        $table->string('provinsi')->nullable();
        $table->string('kota')->nullable();
        $table->string('kecamatan')->nullable();
        $table->string('kelurahan')->nullable();
        $table->string('kode_pos', 10)->nullable();
    });
}
    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropColumn(['alamat','provinsi','kota','kecamatan','kelurahan','kode_pos']);
    });
}
};
