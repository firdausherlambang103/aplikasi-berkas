<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaLog extends Model
{
    use HasFactory;
    protected $fillable = ['berkas_id', 'wa_template_id'];
}