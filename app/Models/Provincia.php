<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provincia extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "provincias";
    
    protected $fillable = [
        'designacao',
        'pais_id',
    ];
                    
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id', 'id');
    }  
    
}
