<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubConta extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "sub_contas";
    
    protected $fillable = [
        'numero',
        'designacao',
        'descricao',
        'estado',
        'controle_conta_id',
        'tipo_instituicao',
        'tipo',
        'conta_id',
        'empresa_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    public function items_movimentos()
    {
        return $this->hasMany(MovimentoItem::class, 'subconta_id', 'id');
    }  
        
    public function items_imobilizado()
    {
        return $this->hasMany(Imobilizado::class, 'sub_conta_id', 'id');
    }  
            
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
    
    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id', 'id');
    }
    
    /**controle ou conta empresas */
    public function empresa_conta()
    {
        return $this->belongsTo(ContaEmpresa::class, 'controle_conta_id', 'id');
    }
    
    
    
}
