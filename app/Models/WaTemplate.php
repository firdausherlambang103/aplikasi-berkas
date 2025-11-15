<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['nama_template', 'template_text'];

    public function waLogs()
    {
        return $this->hasMany(WaLog::class);
    }
}
