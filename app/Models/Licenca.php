<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    use HasFactory;

    protected $table = "licenca";

    protected $fillable = [
        'titulo',
        'modulo_id',
        'designacao',
    ];

    public function modulos()
    {
        return $this->belongsTo(modulo::class, 'modulo_id', 'id');
    }
}
