<?php

namespace App\Http\Controllers;

use App\Models\Movimento;
use App\Models\SubConta;
use App\Models\SUBCONTATESTE;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Models\Empresa;
use App\Models\LicencaUsuario;

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

        $data['resultado'] = Movimento::with(['items', 'exercicio', 'periodo', 'diario', 'tipo_documento', 'empresa', 'criador'])
        // ->where('origem', 'fluxocaixa')
        ->where('empresa_id', $this->empresaLogada())
        ->select(
            DB::raw('SUM(debito) AS debito'),
            DB::raw('SUM(credito) AS credito'),
            DB::raw('SUM(iva) AS iva')
        )
        ->first();

        // $data['grafico'] = Movimento::with(['items', 'exercicio', 'periodo', 'diario', 'tipo_documento', 'empresa', 'criador'])
        // ->where('origem', 'fluxocaixa')
        // ->where('empresa_id', $this->empresaLogada())
        // ->select(
        //     DB::raw('YEAR(created_at) as ano'),
        //     DB::raw('MONTH(created_at) as mes'),
        //     DB::raw('SUM(debito) AS debito'),
        //     DB::raw('SUM(credito) AS credito'),
        //     DB::raw('SUM(credito - debito) AS saldo')
        // )
        // ->groupBy('ano', 'mes')
        // ->orderBy('ano', 'mes')
        // ->get();

        return Inertia::render('Dashboard', $data);
    }

    public function movimentosPorMes()
    {
        $empresaId = $this->empresaLogada();

        $movimentos = Movimento::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('SUM(debito) as total_debito'),
            DB::raw('SUM(credito) as total_credito'),
            DB::raw('IF(SUM(debito) > SUM(credito), SUM(debito) - SUM(credito), SUM(credito) - SUM(debito)) as total_saldo')
        )
        // ->where('origem', 'fluxocaixa')
        ->where('empresa_id', $empresaId)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

        return response()->json($movimentos);
    }

    public function dashboard_admin(Request $request)
    {

        $data['movimentos'] = [];
        $data['empresas_count'] = Empresa::count();
        $data['licencas_usadas_count'] = LicencaUsuario::where('estado', 1)->count();
        $data['licencas_nao_usadas_count'] = LicencaUsuario::where('estado', 0)->count();

        return Inertia::render('DashboardAdmin', $data);
    }


}
