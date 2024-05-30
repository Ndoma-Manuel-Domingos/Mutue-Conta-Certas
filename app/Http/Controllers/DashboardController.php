<?php

namespace App\Http\Controllers;

use App\Models\Movimento;
use App\Models\SubConta;
use App\Models\SUBCONTATESTE;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class DashboardController extends Controller
{

    use Config;

    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function dashboard(Request $request)
    {
    
        $data['movimentos'] = Movimento::with(['exercicio', 'diario' ,'tipo_documento', 'criador'])
        ->where('empresa_id', $this->empresaLogada())
        ->orderBy('id', 'asc')
        ->limit(5)
        ->get();
        
       
        return Inertia::render('Dashboard', $data);
    }
    
    public function dashboard_admin(Request $request)
    {
    
        $data['movimentos'] = [];
               
        return Inertia::render('DashboardAdmin', $data);
    }


}
