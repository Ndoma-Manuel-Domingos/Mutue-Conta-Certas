<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imobilizado extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "imobilizados";
    
    protected $fillable = [
        'status',
        'conta',
        'origem_id',
        'classificacao_id',
        'sigla',
        'exercicio_id',
        'empresa_id',
        'periodo_id',
        'amortizacao_id',
        'amortizacao_item_id',
        'valor_aquisicao',
        'quantidade',
        'data_aquisicao',
        'data_utilizacao',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
        
    public function amortizacao()
    {
        return $this->belongsTo(TabelaAmortizacao::class, 'amortizacao_id', 'id');
    }  
        
    public function amortizacao_item()
    {
        return $this->belongsTo(TabelaAmortizacaoItem::class, 'amortizacao_item_id', 'id');
    }  
    
    public function classificacao()
    {
        return $this->belongsTo(ClassificacaoImobilizado::class, 'classificacao_id', 'id');
    }  
        
    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id', 'id');
    }  
        
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'periodo_id', 'id');
    }  
        
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  

    
}
