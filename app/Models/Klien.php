<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klien extends Model
{
    use HasFactory;
    protected $table = 'klien';
    protected $fillable = ['kode_klien', 'nama_klien', 'nomer_wa'];

    public function berkas()
    {
        return $this->hasMany(Berkas::class);
    }
}
