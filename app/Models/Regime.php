<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regime extends Model
{
    use HasFactory;
        
    protected $table = "regimes";
    
    protected $fillable = [
        'designacao',
    ];
}
