<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "paises";
    
    protected $fillable = [
        'designacao',
        'nacionalidade',
        'nome_ingles',
    ];
                    

}
