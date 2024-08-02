<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    use HasFactory;
    protected $table = "tb_forma_pagamento";

    protected $fillable = [
        'descricao',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
