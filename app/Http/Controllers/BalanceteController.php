<?php

namespace App\Http\Controllers;;

use App\Models\ClasseEmpresa;
use App\Models\ContaEmpresa;
use App\Models\Exercicio;
use App\Models\Movimento;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\SubConta;
use App\Models\TipoBalancete;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class BalanceteController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['registros'] = ContaEmpresa::with(['conta.items_movimentos' => function ($query) {
            $query->select(
                'conta_id',
                DB::raw('sum(debito) as TotalDebito'),
                DB::raw('sum(credito) as TotalCredito'),
            )->groupBy('conta_id');
        }, 'sub_contas_empresa.items_movimentos' => function ($query) {
            $query->select(
                'subconta_id',
                DB::raw('sum(debito) as total_debito'),
                DB::raw('sum(credito) as total_credito'),
            )->groupBy('subconta_id');
        }])
        ->paginate(15);
        
        $valores = [];
        $valores_por_conta = [];

        $movimento_debito = 0;
        $movimento_credito = 0;
        $total_credito = 0;
        $total_debito = 0;
        $total_por_conta_credito = 0;
        $total_por_conta_debito = 0;

        foreach ($data['registros'] as $movimento) {
            foreach ($movimento['conta']['items_movimentos'] as $contas) {
                
                $total_por_conta_credito += $contas->TotalCredito;
                $total_por_conta_debito += $contas->TotalDebito;
                
                $valores_por_conta = [
                    "total_credito" => $total_por_conta_credito,
                    "total_debito" => $total_por_conta_debito,
                ];
            
            }     
            
            foreach ($movimento['sub_contas_empresa'] as $mov) {
                
                foreach ($mov['items_movimentos'] as $resultado) {
                    
                    if ($resultado->total_debito > $resultado->total_credito) {
                        $movimento_debito += ($resultado->total_debito - $resultado->total_credito);
                    }
                    
                    if ($resultado->total_credito > $resultado->total_debito) {
                        $movimento_credito += ($resultado->total_credito - $resultado->total_debito);
                    }
                    
                    $total_credito += $resultado->total_credito;
                    $total_debito += $resultado->total_debito;
                    
                    $valores = [
                        "total_movimento_credito" => $movimento_credito,
                        "total_movimento_debito" => $movimento_debito,
                        "total_credito" => $total_credito,
                        "total_debito" => $total_debito,
                    ];
                   
                }  
            }
        }

        $data['resultado'] = $valores;
        $data['resultado_por_conta'] = $valores_por_conta;
        

        // ->whereHas('sub_contas_empresa.items_movimentos', function ($query) use ($request) {
        //     $query->groupBy('subconta_id');
        //     $query->orderByDesc(DB::raw('MAX(id)'));
        //     $query->select('subconta_id', DB::raw('SUM(debito) as debito'), DB::raw('SUM(credito) as credito'));
        // })
        // ->groupBy('subconta_id')
        // ->orderByDesc(DB::raw('MAX(id)'))
        // ->select('subconta_id', DB::raw('SUM(debito) as debito'), DB::raw('SUM(credito) as credito'))


        // dd($data['novos'][0]['sub_contas_empresa'][0]['sub_contas_empresa']);
        // dd($data['novos'][1]['conta']['items_movimentos']);


        // dd( $data['novos']);

        // $data['movimentos'] = MovimentoItem::whereHas('movimento', function($query) use($request){
        //     $query->when($request->exercicio_id, function($query, $value){
        //         $query->where('exercicio_id', $value);
        //     }); 
        // })
        // ->whereHas('movimento', function($query) use($request){
        //     $query->when($request->periodo_id, function($query, $value){
        //         $query->where('periodo_id', $value);
        //     }); 
        // })
        // ->whereHas('movimento', function($query) use($request){
        //     $query->when($request->data_inicio, function($query, $value){
        //         $query->whereDate('data_lancamento',  ">=" ,$value);
        //     }); 
        // })
        // ->whereHas('movimento', function($query) use($request){
        //     $query->when($request->data_final, function($query, $value){
        //         $query->whereDate('data_lancamento', "<=" ,$value);
        //     }); 
        // })
        // ->when($request->conta_id, function($query, $value){
        //     $query->where('subconta_id', $value);
        // })
        // ->with(['subconta', 'movimento', 'criador'])
        // ->where('empresa_id', $this->empresaLogada())
        // ->orderBy('id', 'desc')
        // ->get();

        // $data['movimentos'] = MovimentoItem::select('subconta_id', DB::raw('SUM(debito) as debito'), DB::raw('SUM(credito) as credito'))
        //     ->whereHas('movimento', function ($query) use ($request) {
        //         $query->when($request->exercicio_id, function ($query, $value) {
        //             $query->where('exercicio_id', $value);
        //         });
        //     })
        //     ->whereHas('movimento', function ($query) use ($request) {
        //         $query->when($request->periodo_id, function ($query, $value) {
        //             $query->where('periodo_id', $value);
        //         });
        //     })
        //     ->whereHas('movimento', function ($query) use ($request) {
        //         $query->when($request->data_inicio, function ($query, $value) {
        //             $query->whereDate('data_lancamento',  ">=", $value);
        //         });
        //     })
        //     ->whereHas('movimento', function ($query) use ($request) {
        //         $query->when($request->data_final, function ($query, $value) {
        //             $query->whereDate('data_lancamento', "<=", $value);
        //         });
        //     })
        //     ->when($request->conta_id, function ($query, $value) {
        //         $query->where('subconta_id', $value);
        //     })
        //     ->with(['subconta', 'movimento', 'criador'])
        //     ->where('empresa_id', $this->empresaLogada())
        //     ->groupBy('subconta_id')
        //     ->orderByDesc(DB::raw('MAX(id)')) // Usando MAX(id) na cláusula orderBy
        //     ->get();


        // dd($data['movimentos']);

        // $valores = [];

        // $movimento_debito = 0;
        // $movimento_credito = 0;
        // $total_credito = 0;
        // $total_debito = 0;

        // foreach ($data['movimentos'] as $movimento) {

        //     if ($movimento->debito > $movimento->credito) {
        //         $movimento_debito += ($movimento->debito - $movimento->credito);
        //     }

        //     if ($movimento->credito > $movimento->debito) {
        //         $movimento_credito += ($movimento->credito - $movimento->debito);
        //     }

        //     $total_credito += $movimento->credito;
        //     $total_debito += $movimento->debito;

        //     $valores = [
        //         "total_movimento_credito" => $movimento_credito,
        //         "total_movimento_debito" => $movimento_debito,
        //         "total_credito" => $total_credito,
        //         "total_debito" => $total_debito,
        //     ];
        // }

        $data['resultado'] = $valores;

        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['tipos_balancetes'] = TipoBalancete::select('id', 'designacao As text')->get();

        $data['contas'] = SubConta::where('empresa_id', $this->empresaLogada())
            ->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))
            ->get();

        return Inertia::render('Balancetes/Index', $data);
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }


    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function imprimirMovimento()
    {
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirBalancete(Request $request)
    {

        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['registros'] = ContaEmpresa::with(['conta.items_movimentos' => function ($query) {
            $query->select(
                'conta_id',
                DB::raw('sum(debito) as TotalDebito'),
                DB::raw('sum(credito) as TotalCredito'),
            )->groupBy('conta_id');
        }, 'sub_contas_empresa.items_movimentos' => function ($query) {
            $query->select(
                'subconta_id',
                DB::raw('sum(debito) as total_debito'),
                DB::raw('sum(credito) as total_credito'),
            )->groupBy('subconta_id');
        }])
        ->paginate(15);
        
        $valores = [];
        $valores_por_conta = [];

        $movimento_debito = 0;
        $movimento_credito = 0;
        $total_credito = 0;
        $total_debito = 0;
        $total_por_conta_credito = 0;
        $total_por_conta_debito = 0;

        foreach ($data['registros'] as $movimento) {
            foreach ($movimento['conta']['items_movimentos'] as $contas) {
                
                $total_por_conta_credito += $contas->TotalCredito;
                $total_por_conta_debito += $contas->TotalDebito;
                
                $valores_por_conta = [
                    "total_credito" => $total_por_conta_credito,
                    "total_debito" => $total_por_conta_debito,
                ];
            
            }     
            
            foreach ($movimento['sub_contas_empresa'] as $mov) {
                
                foreach ($mov['items_movimentos'] as $resultado) {
                    
                    if ($resultado->total_debito > $resultado->total_credito) {
                        $movimento_debito += ($resultado->total_debito - $resultado->total_credito);
                    }
                    
                    if ($resultado->total_credito > $resultado->total_debito) {
                        $movimento_credito += ($resultado->total_credito - $resultado->total_debito);
                    }
                    
                    $total_credito += $resultado->total_credito;
                    $total_debito += $resultado->total_debito;
                    
                    $valores = [
                        "total_credito" => $total_credito,
                        "total_debito" => $total_debito,
                        "total_movimento_credito" => $movimento_credito,
                        "total_movimento_debito" => $movimento_debito,
                    ];
                   
                }  
            }
        }

        $data['resultado'] = $valores;
        $data['resultado_por_conta'] = $valores_por_conta;


        $data['resultado'] = $valores;

        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['tipos_balancetes'] = TipoBalancete::select('id', 'designacao As text')->get();

        $data['contas'] = SubConta::where('empresa_id', $this->empresaLogada())
            ->select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))
            ->get();
        
        $pdf = PDF::loadView('pdf.contas.Balancete', $data)->setPaper('a3', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Balancete.pdf');
    }
}
