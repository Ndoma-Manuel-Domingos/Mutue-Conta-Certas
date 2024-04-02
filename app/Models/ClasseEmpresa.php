<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasseEmpresa extends Model
{
    use HasFactory;
        
    protected $table = "controle_classe_empresas";
    
    protected $fillable = [
        'classe_id',
        'empresa_id',
        'estado',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    // public function contas()
    // {
    //     return $this->belongsToMany(ContaEmpresa::class, 'classe_id', 'classe_id');
    // } 
            
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
    
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id', 'id');
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
