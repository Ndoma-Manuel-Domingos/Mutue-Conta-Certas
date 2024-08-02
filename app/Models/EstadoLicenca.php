<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoLicenca extends Model
{
    use HasFactory;

    protected $table = "status_licencas";

    protected $fillable = [
        "descricao"
    ];
}
