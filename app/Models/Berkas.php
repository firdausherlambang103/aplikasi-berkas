<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berkas extends Model
{
    use HasFactory;
    protected $table = 'berkas';
    protected $fillable = [
        'klien_id', 'jenis_hak', 'nomer_hak', 'kecamatan', 'desa',
        'jenis_permohonan', 'spa', 'alih_media', 'keterangan'
    ];

    public function klien()
    {
        return $this->belongsTo(Klien::class);
    }
}
