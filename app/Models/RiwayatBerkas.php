<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBerkas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_berkas';

    protected $fillable = [
        'user_id',
        'berkas_id',
        'aksi',
        'keterangan',
        'data_lama',
        'data_baru',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function berkas()
    {
        return $this->belongsTo(Berkas::class)->withTrashed(); // Tetap ambil walau soft delete (jika ada)
    }
}