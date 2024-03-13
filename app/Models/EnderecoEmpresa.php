<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnderecoEmpresa extends Model
{
    use HasFactory;
        
    protected $table = "endereco_empresas";
    
    protected $fillable = [
        'rua',
        'casa',
        'bairro',
        'codigo_postal',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'comuna_id',
        'empresa_id',
    ];
}
