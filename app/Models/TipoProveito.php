<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProveito extends Model
{
    use HasFactory;
        
    protected $table = "tipos_proveitos";
    
    protected $fillable = [
        'designacao',
    ];
}
