<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaAmortizacao extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "tabela_amortizacoes";
    
    protected $fillable = [
        'ordem',
        'designacao',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
        
    public function empresa()
    {
        return $this->hasMany(TabelaAmortizacaoItem::class, 'amortizacao_id', 'id');
    }  

    
}
