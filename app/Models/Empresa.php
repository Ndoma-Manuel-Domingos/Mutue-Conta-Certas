<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
        
    protected $table = "tb_empresas";
    
    protected $fillable = [
        'nome',
        'contacto',
        'email',
        'nif',
        'moeda_base',
        'moeda_alternativa',
        'regime',
        'logo',
    ];
}
