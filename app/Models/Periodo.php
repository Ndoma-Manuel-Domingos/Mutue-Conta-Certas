<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;
        
    protected $table = "periodos";
    
    protected $fillable = [
        'designacao',
        'estado',
        'exercicio_id',
        'empresa_id',
    ];
                    
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
    
    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id', 'id');
    }  

}
