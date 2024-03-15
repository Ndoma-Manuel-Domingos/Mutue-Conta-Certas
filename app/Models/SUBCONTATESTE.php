<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SUBCONTATESTE extends Model
{
    use HasFactory;
        
    protected $table = "subcontasplano";
    
    protected $primaryKey = 'Codigo';
    
    protected $fillable = [
        'Numero',
        'Descricao',
    ];
            
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
    
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id', 'id');
    }
    
}
