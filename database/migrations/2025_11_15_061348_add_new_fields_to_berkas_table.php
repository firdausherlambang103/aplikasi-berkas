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
                // Tambahkan kolom-kolom baru
                
                // Nomer unik untuk internal
                $table->string('nomer_berkas')->nullable()->unique()->after('id'); 
                
                // Nama pemohon, terpisah dari klien
                $table->string('nama_pemohon')->nullable()->after('klien_id'); 
                
                // Nomer WA manual per berkas
                $table->string('nomer_wa', 25)->nullable()->after('nama_pemohon'); 

                // Info pembayaran
                $table->string('kode_biling')->nullable()->after('keterangan');
                $table->bigInteger('jumlah_bayar')->nullable()->after('kode_biling');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('berkas', function (Blueprint $table) {
                // Hapus kolom jika migrasi di-rollback
                $table->dropColumn([
                    'nomer_berkas', 
                    'nama_pemohon', 
                    'nomer_wa', 
                    'kode_biling', 
                    'jumlah_bayar'
                ]);
            });
        }
    };