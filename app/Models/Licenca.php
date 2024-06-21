<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    use HasFactory;

    protected $table = "licencas";

    protected $fillable = [
        'titulo',
        'designacao',
        'preco',
    ];

    public function modulos()
    {
        return $this->hasMany(LicencaModulo::class, 'licenca_id', 'id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id', 'id');
    }
}
