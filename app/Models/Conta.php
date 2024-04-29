<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conta extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "contas";
    
    protected $fillable = [
        'id',
        'numero',
        'designacao',
        'descricao',
    ];
    
    public function items_movimentos()
    {
        return $this->hasMany(MovimentoItem::class, 'conta_id', 'id');
    }  
            
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
    
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id', 'id');
    }
    
}
