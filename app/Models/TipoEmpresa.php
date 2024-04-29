<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoEmpresa extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tipos_empresas";
    
    protected $fillable = [
        'designacao',
    ];
    
}
