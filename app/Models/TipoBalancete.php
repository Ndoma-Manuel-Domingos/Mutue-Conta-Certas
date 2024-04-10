<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoBalancete extends Model
{
    use HasFactory;
        
    protected $table = "tipos_balancetes";
    
    protected $fillable = [
        'sigla',
        'designacao',
    ];
}
