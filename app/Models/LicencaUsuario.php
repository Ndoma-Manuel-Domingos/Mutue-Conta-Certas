<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicencaUsuario extends Model
{
    use HasFactory;
    protected $table = "controle_licenca_usuario";

    protected $fillable = [
        'id_usuario',
        'id_licenca',
    ];

    public function usuario(){
        return $this->hasMany(User::class,  'id','id_usuario');
    }

    public function licenca(){
        return $this->hasMany(Licenca::class,  'id','id_licenca');
    }

}
