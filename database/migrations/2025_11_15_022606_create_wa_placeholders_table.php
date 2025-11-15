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
        Schema::create('wa_placeholders', function (Blueprint $table) {
            $table->id();
            $table->string('placeholder_key')->unique(); // Cth: [nama], [nomer_hak]
            $table->string('deskripsi'); // Cth: Nama Klien
            
            // Ini adalah bagian penting:
            // Ini memberitahu sistem dari mana harus mengambil data
            // Format: 'relasi.kolom' atau 'tabel.kolom'
            // Cth: 'klien.nama_klien', 'berkas.nomer_hak', 'berkas.spa'
            $table->string('data_source'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_placeholders');
    }
};