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
        Schema::create('klien', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klien', 50)->unique();
            $table->string('nama_klien');
            $table->string('nomer_wa', 20);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kliens');
    }
};
