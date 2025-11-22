<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yang mengubah
            $table->foreignId('berkas_id')->nullable()->constrained('berkas')->onDelete('set null'); // Berkas apa
            $table->string('aksi'); // CONTOH: CREATE, UPDATE, DELETE
            $table->text('keterangan')->nullable(); // Ringkasan perubahan
            $table->json('data_lama')->nullable(); // Data sebelum diubah
            $table->json('data_baru')->nullable(); // Data setelah diubah
            $table->timestamps(); // Kapan terjadi
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_berkas');
    }
};