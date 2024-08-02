<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licenca extends Model
{
    use HasFactory;

    protected $table = "licencas";

    protected $fillable = [
        'tipo_licenca_id',
        'designacao',
        'descricao',
        'valor',
        'preco_final_iva',
        'tipo_taxa_id',
        'motivo_isencao_iva_codigo',
        'limite_usuario',
        'estado_desconto',
        'desconto',
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
