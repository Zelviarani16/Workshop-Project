<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Role: 'vendor' atau 'guest'
            $table->string('role')->default('guest')->after('email');
            // Nomor urut untuk generate nama Guest_0000001
            $table->integer('guest_number')->nullable()->after('role');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'guest_number']);
        });
    }
};