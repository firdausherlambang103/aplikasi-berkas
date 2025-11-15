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
        'kecamatan',
        'desa',
        'jenis_permohonan',
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
        // Fungsi index() Anda menggunakan with('klien'), jadi relasi ini penting
        return $this->belongsTo(Klien::class);
    }
}