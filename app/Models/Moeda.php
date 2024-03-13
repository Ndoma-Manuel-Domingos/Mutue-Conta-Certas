<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moeda extends Model
{
    use HasFactory;
        
    protected $table = "moedas";
    
    protected $fillable = [
        'sigla',
        'designacao',
        'pais',
        'descricao',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
