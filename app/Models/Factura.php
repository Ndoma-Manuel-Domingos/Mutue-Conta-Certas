<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "facturas";
    
    protected $fillable = [
        'valor_total',
        'data_vencimento',
        'fornecedor_id',
        'tipo_documento_id',
        'referencia_factura',
        'centro_custo_id',
        'cliente_id',
        'data_factura',
        'status',
        'user_id',
        'descricao',
        'empresa_id',
        'exercicio_id',
        'periodo_id',
    ];

    
    public function cliente()
    {
        return $this->belongsTo(SubConta::class, 'cliente_id', 'id');
    } 
    
    public function fornecedor()
    {
        return $this->belongsTo(SubConta::class, 'fornecedor_id', 'id');
    } 
    
    public function exercicio()
    {
        return $this->belongsTo(Exercicio::class, 'exercicio_id', 'id');
    } 
    
    public function centro_de_custo()
    {
        return $this->belongsTo(CentroDeCusto::class, 'centro_custo_id', 'id');
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
        return $this->belongsTo(Documento::class, 'tipo_documento_id', 'id');
    }  
                
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
        
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }  

}
