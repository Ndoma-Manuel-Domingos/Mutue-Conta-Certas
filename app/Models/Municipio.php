<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;
        
    protected $table = "municipios";
    
    protected $fillable = [
        'designacao',
        'provincias_id',
    ];
                    
    public function pais()
    {
        return $this->belongsTo(Provincia::class, 'provincias_id', 'id');
    }  
    
}
