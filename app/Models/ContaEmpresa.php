<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContaEmpresa extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "controle_conta_empresas";
    
    protected $fillable = [
        'conta_id',
        'empresa_id',
        'classe_id',
        'estado',
        'numero',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
        
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
    
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id', 'id');
    }
    
    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id', 'id');
    }
    
    public function sub_contas_empresa()
    {
        return $this->hasMany(SubConta::class, 'conta_id', 'conta_id');
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
