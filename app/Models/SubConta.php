<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubConta extends Model
{
    use HasFactory;
        
    protected $table = "tb_subcontas";
    
    protected $fillable = [
        'nome',
        'codigo',
        'status',
        'conta_id',
        'empresa_id',
    ];
            
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }  
    
    public function conta()
    {
        return $this->belongsTo(Conta::class, 'conta_id', 'id');
    }
    
}
