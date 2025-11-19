<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klien extends Model
{
    use HasFactory;

    /**
     * PERBAIKAN: Secara eksplisit memberi tahu Laravel
     * nama tabel yang benar adalah 'klien' (singular), bukan 'kliens'.
     */
    protected $table = 'klien';
    
    protected $fillable = [
        'kode_klien',
        'nama_klien',
        'nomer_wa',
        'alamat',
    ];

    public function berkas()
    {
        // Pastikan model Berkas menggunakan foreign key 'klien_id'
        return $this->hasMany(Berkas::class, 'klien_id');
    }
}