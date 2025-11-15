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
        Schema::create('wa_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berkas_id')->constrained('berkas')->onDelete('cascade');
            $table->foreignId('wa_template_id')->constrained('wa_templates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_logs');
    }
};
