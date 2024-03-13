<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function dashboard(Request $request)
    {
    
        $sua_sessao_global = Session::get('empresa_logada_mutue_contas_certas_2024');
        
        $data = [];        
       
        // if(!$sua_sessao_global){
            
        //     $data['empresas'] = User::with(['empresas'])->findOrFail(Auth::user()->id);
        
        //     return Inertia::render('EscolherEmpresa', $data);
        // }
 
       
        return Inertia::render('Dashboard', $data);
    }


}
