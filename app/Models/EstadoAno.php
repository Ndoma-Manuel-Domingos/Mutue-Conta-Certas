<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoAno extends Model
{
    use HasFactory;
        
    protected $table = "estado_anos";
    
    protected $fillable = [
        'designacao',
        'descricao',
        'estado_ano_id',
    ];
                
    public function criador()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }  
    
    public function alterador()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    
    public function deletador()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }
}
