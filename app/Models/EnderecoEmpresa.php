<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnderecoEmpresa extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "endereco_empresas";
    
    protected $fillable = [
        'rua',
        'casa',
        'bairro',
        'codigo_postal',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'comuna_id',
        'empresa_id',
    ];
    
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id', 'id');
    }  
    
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }  
    
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }  

    public function comuna()
    {
        return $this->belongsTo(Comuna::class, 'comuna_id', 'id');
    }  
 
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }     
}
