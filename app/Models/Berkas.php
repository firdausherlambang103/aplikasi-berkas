<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berkas extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nomer_berkas',
        'klien_id',
        'nama_pemohon',
        'nomer_wa',
        'jenis_hak',
        'nomer_hak',
        
        // --- PERBAIKAN: 3 baris ini dihapus ---
        // 'kecamatan', 
        // 'desa',
        // 'jenis_permohonan',
        // ------------------------------------

        // Kolom ID Baru (Ini sudah benar)
        'kecamatan_id',
        'desa_id',
        'jenis_permohonan_id',

        'spa',
        'alih_media',
        'keterangan',
        'kode_biling',
        'jumlah_bayar',
        
        // Kolom-kolom ini ada di fungsi update(), 
        // jadi harus ada di $fillable juga
        'status',
        'posisi',
        'tanggal_selesai',
    ];

    /**
     * Mendapatkan data klien yang terkait dengan berkas.
     */
    public function klien()
    {
        return $this->belongsTo(Klien::class);
    }

    // Relasi baru
    public function dataKecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    // UBAH DARI: public function desa()
    // MENJADI:
    public function dataDesa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function jenisPermohonan()
    {
        return $this->belongsTo(JenisPermohonan::class, 'jenis_permohonan_id');
    }

    public function waLogs()
    {
        // Pastikan class WaLog di-import atau panggil namespace lengkap
        return $this->hasMany(WaLog::class, 'berkas_id');
    }
}