<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// PERBAIKAN: Tambahkan "extends Model"
class Klien extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'kode_klien',
        'nama_klien',
        'nomer_wa',
        'alamat',
    ];

    public function berkas()
    {
        return $this->hasMany(Berkas::class);
    }
}