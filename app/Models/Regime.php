<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regime extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "regimes";
    
    protected $fillable = [
        'designacao',
    ];
}
