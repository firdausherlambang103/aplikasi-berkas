<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class Berkas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomer_berkas',
        'klien_id',
        'nama_pemohon',
        'nomer_wa',
        'jenis_hak',
        'nomer_hak',
        'kecamatan_id',
        'desa_id',
        'jenis_permohonan_id',
        'spa',
        'alih_media',
        'keterangan',
        'kode_biling',
        'jumlah_bayar',
        'status',
        'korektor',
    ];

    // --- RELASI YANG SUDAH ADA ---
    public function klien() { return $this->belongsTo(Klien::class); }
    public function dataKecamatan() { return $this->belongsTo(Kecamatan::class, 'kecamatan_id'); }
    public function dataDesa() { return $this->belongsTo(Desa::class, 'desa_id'); }
    public function jenisPermohonan() { return $this->belongsTo(JenisPermohonan::class, 'jenis_permohonan_id'); }
    public function waLogs() { return $this->hasMany(WaLog::class); }

    // --- FITUR LOGGING OTOMATIS ---
    protected static function boot()
    {
        parent::boot();

        // 1. Saat Berkas Baru Dibuat
        static::created(function ($berkas) {
            if (Auth::check()) {
                RiwayatBerkas::create([
                    'user_id' => Auth::id(),
                    'berkas_id' => $berkas->id,
                    'aksi' => 'MEMBUAT',
                    'keterangan' => 'Membuat berkas baru dengan status: ' . $berkas->status,
                    'data_baru' => $berkas->toArray()
                ]);
            }
        });

        // 2. Saat Berkas Diupdate
        static::updated(function ($berkas) {
            if (Auth::check()) {
                // Cari tahu kolom mana yang berubah
                $perubahan = $berkas->getChanges();
                // Abaikan updated_at
                unset($perubahan['updated_at']);

                if (!empty($perubahan)) {
                    RiwayatBerkas::create([
                        'user_id' => Auth::id(),
                        'berkas_id' => $berkas->id,
                        'aksi' => 'MENGUBAH',
                        'keterangan' => 'Mengubah data: ' . implode(', ', array_keys($perubahan)),
                        'data_lama' => $berkas->getOriginal(), // Data sebelum ubah
                        'data_baru' => $perubahan // Data yang berubah saja
                    ]);
                }
            }
        });

        // 3. Saat Berkas Dihapus
        static::deleted(function ($berkas) {
            if (Auth::check()) {
                RiwayatBerkas::create([
                    'user_id' => Auth::id(),
                    'berkas_id' => $berkas->id,
                    'aksi' => 'MENGHAPUS',
                    'keterangan' => 'Menghapus berkas pemohon: ' . $berkas->nama_pemohon,
                    'data_lama' => $berkas->toArray()
                ]);
            }
        });
    }
}