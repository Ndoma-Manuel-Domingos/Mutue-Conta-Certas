<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassificacaoImobilizado extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "classificacao_imobilizados";
    
    protected $fillable = [
        'designacao',
        'sigla',
        'empresa_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
        
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
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