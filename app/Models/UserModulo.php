<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModulo extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "users_modulos";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'modulo_id',
        'user_id',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
