<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berkas', function (Blueprint $table) {
            // 1. Buat kolom lama jadi nullable
            $table->string('kecamatan')->nullable()->change();
            $table->string('desa')->nullable()->change();
            $table->string('jenis_permohonan')->nullable()->change();
            
            // 2. Tambahkan kolom foreign key baru
            $table->foreignId('kecamatan_id')->nullable()->after('nomer_hak')->constrained('kecamatans');
            $table->foreignId('desa_id')->nullable()->after('kecamatan_id')->constrained('desas');
            $table->foreignId('jenis_permohonan_id')->nullable()->after('desa_id')->constrained('jenis_permohonans');
        });
    }

    public function down(): void
    {
        Schema::table('berkas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kecamatan_id');
            $table->dropConstrainedForeignId('desa_id');
            $table->dropConstrainedForeignId('jenis_permohonan_id');

            // Kembalikan seperti semula jika di-rollback
            $table->string('kecamatan')->nullable(false)->change();
            $table->string('desa')->nullable(false)->change();
            $table->string('jenis_permohonan')->nullable(false)->change();
        });
    }
};