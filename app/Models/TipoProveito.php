<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoProveito extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tipos_proveitos";
    
    protected $fillable = [
        'designacao',
    ];
}
