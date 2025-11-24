<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RiwayatBerkas
 * * Model ini menangani pencatatan sejarah/log aktivitas pada sebuah berkas.
 * Berguna untuk fitur audit trail.
 *
 * @package App\Models
 */
class RiwayatBerkas extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini.
     *
     * @var string
     */
    protected $table = 'riwayat_berkas';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'berkas_id',    // ID dari berkas yang diproses
        'user_id',      // ID user yang melakukan aksi (bisa null jika sistem)
        'aksi',         // Jenis aksi (contoh: 'upload', 'verifikasi', 'tolak', 'download')
        'status',       // Status berkas saat riwayat ini dibuat (contoh: 'pending', 'disetujui')
        'keterangan',   // Catatan tambahan atau alasan penolakan/persetujuan
        'ip_address',   // Alamat IP pengguna saat melakukan aksi (opsional, untuk keamanan)
        'user_agent',   // Info browser/perangkat (opsional)
    ];

    /**
     * Atribut yang harus di-casting ke tipe data native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke model Berkas.
     * Setiap riwayat pasti milik satu berkas.
     *
     * @return BelongsTo
     */
    public function berkas(): BelongsTo
    {
        return $this->belongsTo(Berkas::class, 'berkas_id');
    }

    /**
     * Relasi ke model User (Aktor).
     * Menunjukkan siapa yang membuat riwayat/melakukan aksi ini.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Aksesors atau Helper (Opsional)
     * Contoh: Mengambil format tanggal yang lebih mudah dibaca.
     */
    public function getTanggalDibuatAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->translatedFormat('d F Y H:i');
    }
}