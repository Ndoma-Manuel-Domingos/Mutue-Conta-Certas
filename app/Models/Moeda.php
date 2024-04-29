<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Moeda extends Model
{
    use HasFactory, SoftDeletes;
        
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
