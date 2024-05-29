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
        'modulos',
        'designacao',
    ];
}
