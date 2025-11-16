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
        // Cek jika kolom belum ada sebelum menambahkan
        if (!Schema::hasColumn('wa_templates', 'status_template')) {
            Schema::table('wa_templates', function (Blueprint $table) {
                // Menambahkan kolom status_template setelah template_text
                $table->string('status_template')->default('aktif')->after('template_text');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wa_templates', function (Blueprint $table) {
            $table->dropColumn('status_template');
        });
    }
};