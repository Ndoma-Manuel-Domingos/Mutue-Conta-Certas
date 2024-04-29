<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MoedaEmpresa extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "moeda_empresas";
    
    protected $fillable = [
        'moeda_base_id',
        'moeda_alternativa_id',
        'moeda_cambio_id',
        'empresa_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    public function base()
    {
        return $this->belongsTo(Moeda::class, 'moeda_base_id', 'id');
    }  
    
    public function alternativa()
    {
        return $this->belongsTo(Moeda::class, 'moeda_alternativa_id', 'id');
    }  
    
    public function cambio()
    {
        return $this->belongsTo(Moeda::class, 'moeda_cambio_id', 'id');
    }  
        
}
