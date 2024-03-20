<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoContactoEmpresa extends Model
{
    use HasFactory;
        
    protected $table = "tipo_contacto_empresas";
    
    protected $fillable = [
        'designacao',
        'estado',
    ];
        
    public function criador()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }  
    
    public function alterador()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    
}
