<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasseEmpresa extends Model
{
    use HasFactory;
        
    protected $table = "tb_classes_empresas";
    
    protected $fillable = [
        'classe_id',
        'empresa_id',
        'status',
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
