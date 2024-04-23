<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    use HasFactory;
        
    protected $table = "movimentos";
    
    protected $fillable = [
        'hash',
        'debito',
        'credito',
        'empresa_id',
        'iva',
        'descricao',
        'exercicio_id',
        'periodo_id',
        'dia_id',
        'data_lancamento',
        'lancamento_atual',
        'diario_id',
        'origem',
        'tipo_documento_id',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    public function items()
    {
        return $this->hasMany(MovimentoItem::class, 'movimento_id', 'id');
    }  
    
    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id', 'id');
    } 
    
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    } 
    
    public function diario()
    {
        return $this->belongsTo(Diario::class, 'diario_id', 'id');
    } 
                    
    public function tipo_documento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id', 'id');
    }  
                
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
