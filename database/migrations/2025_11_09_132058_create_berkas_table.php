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
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();

            // Relasi opsional ke klien
            $table->foreignId('klien_id')->nullable()->constrained('klien')->onDelete('set null');

            $table->enum('jenis_hak', ['SHGB', 'SHM', 'SHW', 'SHP', 'Leter C']);
            $table->string('nomer_hak', 100);
            $table->string('kecamatan', 100);
            $table->string('desa', 100);
            $table->string('jenis_permohonan');

            $table->text('spa')->nullable();
            $table->text('alih_media')->nullable();
            $table->longText('keterangan')->nullable();

            $table->boolean('status_kirim_wa')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas');
    }
};
