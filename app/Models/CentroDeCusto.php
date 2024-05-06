<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CentroDeCusto extends Model
{
    use HasFactory, SoftDeletes;
        
    protected $table = "centro_de_custo";
    
    protected $fillable = [
        'designacao',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
