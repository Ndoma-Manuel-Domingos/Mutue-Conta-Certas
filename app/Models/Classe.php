<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;
        
    protected $table = "classes";
    
    protected $fillable = [
        'numero',
        'designacao',
        'descricao',
    ];
                    
    public function criador()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }  
    
    public function alterador()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    
    public function deletador()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }
}
