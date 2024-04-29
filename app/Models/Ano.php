<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ano extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "anos";
    
    protected $fillable = [
        'designacao',
        'estado_ano_id',
    ];
                    
    public function estado()
    {
        return $this->belongsTo(EstadoAno::class, 'estado_ano_id', 'id');
    }  
                
    public function criador()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }  
    
    public function alterador()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    
}
