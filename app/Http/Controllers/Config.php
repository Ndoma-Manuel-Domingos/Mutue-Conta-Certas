<?php

namespace App\Http\Controllers;

use App\Models\Exercicio;
use App\Models\User;
use App\Models\UserEmpresa;
use Illuminate\Support\Facades\Auth;

Trait Config{

    // EMPRESA LOGADA
    public function empresaLogada()
    {
        $user = auth()->user();
        
        if($user){
        
            $user = User::find($user->id);
            
            $empresa = UserEmpresa::where('estado', '1')->where('user_id', $user->id)->first();
            if($empresa){
                return $empresa->empresa_id;
            }else{
                return "";
            }

        }
        
        return "";
    }
    
    public function exercicioActivo()
    {
        $user = auth()->user();
        
        if($user){
        
            $user = User::find($user->id);
            
            $empresa = UserEmpresa::where('estado', '1')->where('user_id', $user->id)->first();
            if($empresa){
                $exercio = Exercicio::where('estado', '1')->where('empresa_id', $empresa->empresa_id)->first();
                if($exercio){
                    return $exercio->id;
                }else{
                    return "";
                }
            }else{
                return "";
            }
        }
        
        return "";
    }
    
    public function minhasEmpresas()
    {
        $user = auth()->user();
        
        if($user){
        
            $user = User::find($user->id);
            
            $empresa = UserEmpresa::with('empresa')->where('user_id', $user->id)->get();
            if($empresa){
                return $empresa;
            }else{
                return "";
            }

        }
        
        return "";
    }
    
}
