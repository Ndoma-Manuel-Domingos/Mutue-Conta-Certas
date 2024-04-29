<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercicio extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "exercicios";
    
    protected $fillable = [
        'designacao',
        'estado',
        'empresa_id',
    ];
                    
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  

}
