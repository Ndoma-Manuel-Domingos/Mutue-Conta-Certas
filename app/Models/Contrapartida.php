<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrapartida extends Model
{
    use HasFactory;
        
    protected $table = "contrapartidas";
    
    protected $fillable = [
        'sub_conta_id',
        'tipo_credito_id',
    ];
        
    public function tipo_credito()
    {
        return $this->belongsTo(TipoCredito::class, 'tipo_credito_id', 'id');
    }   
    
    public function sub_conta()
    {
        return $this->belongsTo(SubConta::class, 'sub_conta_id', 'id');
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
