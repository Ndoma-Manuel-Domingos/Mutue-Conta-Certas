<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMovimento extends Model
{
    use HasFactory;
        
    protected $table = "tipos_movimentos";
    
    protected $fillable = [
        'designacao',
    ];
}
