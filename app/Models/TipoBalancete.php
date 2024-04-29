<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoBalancete extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tipos_balancetes";
    
    protected $fillable = [
        'sigla',
        'designacao',
    ];
}
