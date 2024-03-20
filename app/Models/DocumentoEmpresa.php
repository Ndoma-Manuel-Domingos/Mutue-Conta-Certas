<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoEmpresa extends Model
{
    use HasFactory;
        
    protected $table = "documento_empresas";
    
    protected $fillable = [
        'numero_documento_empresa',
        'anexo_documento_empresa',
        'tipo_documento_empresa_id',
        'empresa_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    public function documento()
    {
        return $this->belongsTo(TipoDocumentoEmpresa::class, 'tipo_documento_empresa_id', 'id');
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
