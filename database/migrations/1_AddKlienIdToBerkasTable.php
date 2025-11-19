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
        // Cek jika kolom 'klien_id' belum ada
        if (!Schema::hasColumn('berkas', 'klien_id')) {
            Schema::table('berkas', function (Blueprint $table) {
                // Tambahkan kolom klien_id setelah nomer_berkas
                $table->foreignId('klien_id')
                      ->nullable()
                      ->after('nomer_berkas') // Menempatkan kolomnya agar rapi
                      ->constrained('kliens')      // Menghubungkan ke tabel kliens
                      ->onDelete('set null');   // Jika klien dihapus, set klien_id di berkas ini jadi NULL
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berkas', function (Blueprint $table) {
            // Hati-hati: Pastikan nama constraint foreign key benar jika ada
            // $table->dropForeign(['klien_id']); 
            $table->dropColumn('klien_id');
        });
    }
};