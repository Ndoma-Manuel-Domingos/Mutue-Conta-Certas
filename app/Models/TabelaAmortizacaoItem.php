<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaAmortizacaoItem extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tabela_amortizacoes_items";
    
    protected $fillable = [
        'designacao',
        'amortizacao_id',
        'taxa',
        'vida_util',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
        
    public function amortizacao()
    {
        return $this->belongsTo(TabelaAmortizacao::class, 'amortizacao_id', 'id');
    }  

    
}
