<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimentoItem extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "movimento_items";
    
    protected $fillable = [
        'hash',
        'debito',
        'credito',
        'iva',
        'descricao',
        'empresa_id',
        'subconta_id',
        'tipo_movimento_id',
        'tipo_instituicao',
        'taxta_iva_id',
        'documento_id',
        'tipo_credito_id',
        'tipo_proveito_id',
        'contrapartida_id',
        'origem',
        'apresentar',
        'conta_id',
        'movimento_id',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];       
        
    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id', 'id');
    }  
        
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id', 'id');
    }  
    
    public function contrapartida()
    {
        return $this->belongsTo(Contrapartida::class, 'contrapartida_id', 'id');
    }  
        
    public function taxa_iva()
    {
        return $this->belongsTo(Taxa::class, 'contrapartida_id', 'id');
    }  
    
    public function tipo_proveito()
    {
        return $this->belongsTo(TipoProveito::class, 'tipo_proveito_id', 'id');
    }  
    
    public function tipo_credito()
    {
        return $this->belongsTo(TipoCredito::class, 'tipo_credito_id', 'id');
    }  
    
    public function tipo_movimento()
    {
        return $this->belongsTo(TipoMovimento::class, 'tipo_movimento_id', 'id');
    }  
    
    public function subconta()
    {
        return $this->belongsTo(SubConta::class, 'subconta_id', 'id');
    }  
                
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
        
    public function movimento()
    {
        return $this->hasOne(Movimento::class, 'id', 'movimento_id');
    }  
    
    public function alterador()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    
    public function criador()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }  
    
}
