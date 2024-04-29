<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "municipios";
    
    protected $fillable = [
        'designacao',
        'provincias_id',
    ];
                    
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincias_id', 'id');
    }  
    
}
