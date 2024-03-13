<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OperacaoController extends Controller
{
    //
    public function diarios(Request $request)
    {
        // Retorna a lista de posts
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        
        $data['diarios'] = [];
               
        return Inertia::render('Operacoes/Diarios', $data);
    }

    public function movimentos(Request $request)
    {
        // Exibe o formulário para criar um novo post
        $data['movimentos'] = [];
               
        return Inertia::render('Operacoes/Movimentos', $data);
    }

    public function balancetes(Request $request)
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
             
        $data['balancetes'] = [];
        
        return Inertia::render('Operacoes/Balancetes', $data);
    }


}
