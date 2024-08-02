<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoLicenca extends Model
{
    use HasFactory;

    protected $table = "tipos_licencas";

    protected $fillable = [
        'designacao',
    ];
}
