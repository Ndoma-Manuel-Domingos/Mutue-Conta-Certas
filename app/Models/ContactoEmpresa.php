<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactoEmpresa extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "contacto_empresas";
    
    protected $fillable = [
        'contacto_empresas',
        'tipo_contacto_empresa_id',
        'empresa_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    
    public function contacto()
    {
        return $this->belongsTo(TipoContactoEmpresa::class, 'tipo_contacto_empresa_id', 'id');
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
