<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicencaModulo extends Model
{
    use HasFactory;

    protected $table = "controle_licenca_modulos";

    protected $fillable = [
        'licenca_id',
        'modulo_id',
    ];

    public function licenca()
    {
        return $this->belongsTo(Licenca::class, 'licenca_id', 'id');
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id', 'id');
    }
}
