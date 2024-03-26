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
        'tipo_empresa_id',
        'grupo_empresa_id',
        'regime_empresa_id',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    public function contactos()
    {
        return $this->hasMany(ContactoEmpresa::class, 'empresa_id', 'id');
    }  
        
    public function documentos()
    {
        return $this->hasMany(DocumentoEmpresa::class, 'empresa_id', 'id');
    }  
    
    public function tipo()
    {
        return $this->belongsTo(TipoEmpresa::class, 'tipo_empresa_id', 'id');
    }  
    
    public function grupo()
    {
        return $this->belongsTo(GrupoEmpresa::class, 'grupo_empresa_id', 'id');
    }  
    
    
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
