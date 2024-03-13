<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
        
    protected $table = "empresas";
    
    protected $fillable = [
        'nome_empresa',
        'codigo_empresa',
        'descricao_empresa',
        'logotipo_da_empresa',
        'estado_empresa_id',
        'regime_empresa_id',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    public function regime()
    {
        return $this->belongsTo(Regime::class, 'regime_empresa_id', 'id');
    }  
    
    public function endereco()
    {
        return $this->hasOne(EnderecoEmpresa::class, 'empresa_id', 'id');
    }  
    
    public function moeda()
    {
        return $this->hasOne(MoedaEmpresa::class, 'empresa_id', 'id');
    }  
        
    
}
