<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentoItem extends Model
{
    use HasFactory;
        
    protected $table = "movimento_items";
    
    protected $fillable = [
        'hash',
        'debito',
        'credito',
        'iva',
        'descricao',
        'empresa_id',
        'subconta_id',
        'movimento_id',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
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
        return $this->belongsTo(Movimento::class, 'movimento_id', 'id');
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
