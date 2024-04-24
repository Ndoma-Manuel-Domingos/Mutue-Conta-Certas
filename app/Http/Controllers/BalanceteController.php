<?php

namespace App\Http\Controllers;;

use App\Models\ContaEmpresa;
use App\Models\Exercicio;
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

        $data['registros'] = ContaEmpresa::with(['classe'])->with(['conta.items_movimentos' => function ($query) {
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
        ->whereHas('sub_contas_empresa.items_movimentos.movimento', function($query) use($request){
            $query->when($request->data_inicio, function($query, $value){
                $query->whereDate('data_lancamento',  ">=" ,$value);
            }); 
        })
        ->whereHas('sub_contas_empresa.items_movimentos.movimento', function($query) use($request){
            $query->when($request->data_final, function($query, $value){
                $query->whereDate('data_lancamento', "<=" ,$value);
            }); 
        })
        ->paginate(100);
        
        // $data['registros_contas'] = ContaEmpresa::with(['classe'])->with(['conta.items_movimentos' => function ($query) {
        //     $query->select(
        //         'conta_id',
        //         DB::raw('sum(debito) as TotalDebito'),
        //         DB::raw('sum(credito) as TotalCredito'),
        //     )->groupBy('conta_id');
        // }])
        // ->whereHas('sub_contas_empresa.items_movimentos.movimento', function($query) use($request){
        //     $query->when($request->data_inicio, function($query, $value){
        //         $query->whereDate('data_lancamento',  ">=" ,$value);
        //     }); 
        // })
        // ->whereHas('sub_contas_empresa.items_movimentos.movimento', function($query) use($request){
        //     $query->when($request->data_final, function($query, $value){
        //         $query->whereDate('data_lancamento', "<=" ,$value);
        //     }); 
        // })
        // ->paginate(100);
        
        // dd($data['registros_contas']);
        
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
    
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();
        $data['tipos_balancetes'] = TipoBalancete::select('id', 'designacao As text')->get();
        
        $data['requests'] = $request->all('exercicio_id', 'periodo_id', 'data_inicio', 'data_final', 'tipo_balancete_id');

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
        
        $data['registros'] = ContaEmpresa::with(['classe', 'conta.items_movimentos' => function ($query) {
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
        ->whereHas('sub_contas_empresa.items_movimentos.movimento', function($query) use($request){
            $query->when($request->data_inicio, function($query, $value){
                $query->whereDate('data_lancamento',  ">=" ,$value);
            }); 
        })
        ->whereHas('sub_contas_empresa.items_movimentos.movimento', function($query) use($request){
            $query->when($request->data_final, function($query, $value){
                $query->whereDate('data_lancamento', "<=" ,$value);
            }); 
        })
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

        $data['exercicio'] = Exercicio::find($request->exercicio_id); //select('id', 'designacao As text')->get();
        $data['periodo'] = Periodo::find($request->periodo_id); //select('id', 'designacao As text')->get();
        $data['tipo_balancete'] = TipoBalancete::find($request->tipo_balancete_id); //select('id', 'designacao As text')->get();
        $data['dados_empresa'] = $this->dadosEmpresaLogada();
        $data['requests'] = $request->all('tipo_balancete_id', 'exercicio_id', 'periodo_id', 'data_inicio', 'data_final');

        $pdf = PDF::loadView('pdf.contas.Balancete', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Balancete.pdf');
    }
}
