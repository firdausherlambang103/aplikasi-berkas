<?php

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
        Schema::table('berkas', function (Blueprint $table) {
            // Tambahkan kolom status dan korektor
            $table->string('korektor')->nullable()->after('jumlah_bayar'); // Korektor boleh kosong
            $table->string('status')->default('Baru')->after('korektor'); // Status default 'Baru'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berkas', function (Blueprint $table) {
            $table->dropColumn(['korektor', 'status']);
        });
    }
};