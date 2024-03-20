<?php

namespace App\Http\Controllers;

use App\Models\SubConta;
use App\Models\SUBCONTATESTE;
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
    
        // $sucontas = SUBCONTATESTE::where('CodConta', '71')->get();
        
        // foreach ($sucontas as $conta) {
        //     SubConta::create(
        //         [
        //             'numero' => $conta->Numero,
        //             'designacao' => $conta->Descricao,
        //             'descricao' => $conta->Descricao,
        //             'estado' => 'activo',
        //             'conta_id' => 54,
        //             'empresa_id' => 1,
        //         ]
        //     );
        // }
        
        // dd("finish");
    
        
        $data = [];        
       
        // if(!$sua_sessao_global){
            
        //     $data['empresas'] = User::with(['empresas'])->findOrFail(Auth::user()->id);
        
        //     return Inertia::render('EscolherEmpresa', $data);
        // }
 
       
        return Inertia::render('Dashboard', $data);
    }


}
