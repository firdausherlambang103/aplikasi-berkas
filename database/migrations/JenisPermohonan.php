<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPermohonan extends Model
{
    use HasFactory;
    protected $fillable = ['nama'];
    
    // Nama tabel eksplisit karena nama model jamak
    protected $table = 'jenis_permohonans';
}