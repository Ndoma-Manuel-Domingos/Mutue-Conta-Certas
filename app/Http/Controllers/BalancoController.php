<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\SubConta;
use App\Models\Conta;
use App\Models\ContaEmpresa;
use App\Models\Exercicio;
use App\Models\MovimentoItem;
use App\Models\Periodo;
use App\Models\User;
use FontLib\TrueType\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use Carbon\Carbon;
use App\Exports\CentroDeCustoExport;
use Maatwebsite\Excel\Facades\Excel;

class BalancoController extends Controller
{
    use Config;
    public $ano_exercicio = '';

    public function index(Request $request)
    {
        // Retorna a lista de posts
        $dados = $this->anoAnterior();

        foreach ($dados as $value) {
            $exercicio_anterior = $value;
        }
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $classes_activo_corrente = Classe::whereIn('id', ['4', '5'])->pluck('id');
        $classes_activo_nao_corrente = Classe::whereIn('id', ['2', '3'])->pluck('id');

        $classes_passivos_corrente = Classe::whereIn('id', ['4'])->pluck('id');
        $classes_passivos_nao_corrente = Classe::whereIn('id', ['4'])->pluck('id');

        $contas_activos_corrente = ContaEmpresa::whereIn('classe_id', $classes_activo_corrente)->pluck('conta_id');
        $contas_activos_nao_corrente = ContaEmpresa::whereIn('classe_id', $classes_activo_nao_corrente)->pluck('conta_id');

        $contas_passivos_corrente = ContaEmpresa::whereIn('classe_id', $classes_passivos_corrente)->pluck('conta_id');
        $contas_passivos_nao_corrente = ContaEmpresa::whereIn('classe_id', $classes_passivos_nao_corrente)->pluck('conta_id');


        $conta_imobilizacoes_corporeas = ContaEmpresa::with(['sub_contas_empresa.items_movimentos'])->with(['sub_contas_empresa.items_movimentos.movimento'])
            ->with([
                'sub_contas_empresa' => function ($query) {
                    $query->where('tipo', 'M');
                }
            ])
            ->where('conta_id', 3)
            ->get()
            ->unique('sub_contas_empresa.id')
            ->values();


        $conta_imobilizacoes_incorporeas = ContaEmpresa::with(['sub_contas_empresa.items_movimentos', 'sub_contas_empresa.items_movimentos.movimento'])
            ->with([
                'sub_contas_empresa' => function ($query) {
                    $query->where('tipo', 'M');
                }
            ])
            ->where('conta_id', 4)
            ->get()
            ->unique('sub_contas_empresa.id')
            ->values();

        // balanço
        $contas_existencias = Conta::with([
            'subconta' => function ($query) {
                $query->where('tipo', 'M');
            }
        ])
            ->where('nota', 8)
            ->get()
            ->map(function ($conta) {
                return $conta->subconta->pluck('id');
            })
            ->flatten();

        // contas disponiblidade
        $contas_disponibilidade = Conta::with([
            'subconta' => function ($query) {
                $query->where('tipo', 'M');
            }
        ])
            ->where('nota', 10)
            ->get()
            ->map(function ($conta) {
                return $conta->subconta->pluck('id');
            })
            ->flatten();

        // contas subssidiarias
        $contas_subssidiarias = Conta::with([
            'subconta' => function ($query) {
                $query->where('tipo', 'M');
            }
        ])
            ->where('nota', 6)
            ->get()
            ->map(function ($conta) {
                return $conta->subconta->pluck('id');
            })
            ->flatten();


        // apuramento de resultados
        $data_inicio = Carbon::now()->format('d/m/Y');
        $data_final = Carbon::now()->endOfYear()->format('d/m/Y');
        $data_inicio_ano = Carbon::now()->startOfYear()->format('d/m/Y');

        $contas_apuramento = ContaEmpresa::with(['classe'])
            ->with(['conta.items_movimentos', 'sub_contas_empresa.items_movimentos.movimento'])
            ->with(['sub_contas_empresa' => function ($querey) {
                $querey->select('*')->whereBetween('conta_id', [45, 62]);
            }])
            ->whereIn('classe_id', [7, 8])
            ->distinct()
            ->distinct('classe_id')
            ->get();

            // calculo apuramento
        $resultado_operacionais_82 = [];
        $resultado_financeiro_83 = [];
        $resultado_em_associados_84 = [];
        $resultado_nao_operacionais_85 = [];
        $resultado_nao_operacionais_86 = [];
        $imposto_sobre_lucro = 0;
        $resultado_liquido_do_exercicio = 0;
        $RAI = 0;

        $resultados_proveitos_operacionais_credito = 0;
        $resultados_proveitos_operacionais_debito = 0;
        $resultados_operacionais_proveitos_debito = 0;

        $resultados_operacionais_proveitos =  $this->calculoProveitosOperacionais($contas_apuramento);
        $resultados_custos_operacionais =  $this->calculoCustosOperacionais($contas_apuramento);
        $resultados_proveitos_financeiro =  $this->calculoProveitosFinanceiros($contas_apuramento);
        $resultados_custos_financeiro =  $this->calculoCustosFinanceiros($contas_apuramento);
        $resultados_proveitos_associados =  $this->calculoProveitosAssociados($contas_apuramento);
        $resultados_custos_associados =  $this->calculoCustosAssociados($contas_apuramento);
        $outros_proveitos_nao_operacionais =  $this->calculoOutrosProveitosNOperacionais($contas_apuramento);
        $outros_custos_nao_operacionais =  $this->calculoOutrosCustosNOperacionais($contas_apuramento);
        $outros_proveitos_extraordinarios =  $this->calculoProveitosExtraordinarios($contas_apuramento);
        $outros_custos_extraordinarios =  $this->calculoCustosExtraordinarios($contas_apuramento);

        $resultados_proveitos_operacionais_credito = $resultados_operacionais_proveitos['credito'];
        $resultados_proveitos_operacionais_debito = $resultados_operacionais_proveitos['debito'];

        $resultados_custos_operacionais_credito = $resultados_custos_operacionais['credito'];
        $resultados_custos_operacionais_debito = $resultados_custos_operacionais['debito'];

        $resultados_proveitos_associados_credito = $resultados_proveitos_associados['credito'];
        $resultados_proveitos_associados_debito = $resultados_proveitos_associados['debito'];

        $resultados_custos_associados_credito = $resultados_custos_associados['credito'];
        $resultados_custos_associados_debito = $resultados_custos_associados['debito'];

        $resultados_outros_proveitosNao_operacionais_credito = $outros_proveitos_nao_operacionais['credito'];
        $resultados_outros_proveitosNao_operacionais_debito = $outros_proveitos_nao_operacionais['debito'];

        $resultados_outros_custosNao_operacionais_credito = $outros_custos_nao_operacionais['credito'];
        $resultados_outros_custosNao_operacionais_debito = $outros_custos_nao_operacionais['debito'];

        $resultados_proveitos_extraordinario_credito = $outros_proveitos_extraordinarios['credito'];
        $resultados_proveitos_extraordinario_debito = $outros_proveitos_extraordinarios['debito'];

        $resultados_custos_extraordinario_credito = $outros_custos_extraordinarios['credito'];
        $resultados_custos_extraordinario_debito = $outros_custos_extraordinarios['debito'];



        $resultado_operacionais_82 = [
            'proveitos_operacionais_credito' => $resultados_proveitos_operacionais_credito ? $resultados_proveitos_operacionais_credito : 0,
            'custos_operacionais' => $resultados_custos_operacionais_debito ? $resultados_custos_operacionais_debito : 0,
        ];

        $resultado_financeiro_83 =[
            'proveitos_financeiros' => $resultados_proveitos_financeiro['credito'] ? $resultados_proveitos_financeiro['credito'] : 0 ,
            'custos_financeiros' => $resultados_custos_financeiro['credito'] ? $resultados_custos_financeiro['credito'] : 0,
        ];

        $resultado_em_associados_84 = [
            'proveitos_em_associados' => $resultados_proveitos_associados_credito ? $resultados_proveitos_associados_credito : 0,
            'custos_em_associados' => $resultados_custos_associados_debito ? $resultados_custos_associados_debito : 0,
        ];

        $resultado_nao_operacionais_85 = [
            'proveitos_nao_operacionais' => $resultados_outros_proveitosNao_operacionais_credito ? $resultados_outros_proveitosNao_operacionais_credito : 0,
            'custos_nao_operacionais' => $resultados_outros_custosNao_operacionais_credito ? $resultados_outros_custosNao_operacionais_credito : 0,
        ];

        $resultado_extraordinario_86 = [
            'proveitos_nao_operacionais' => $resultados_proveitos_extraordinario_credito ? $resultados_proveitos_extraordinario_credito : 0,
            'custos_nao_operacionais' => $resultados_custos_extraordinario_debito ? $resultados_custos_extraordinario_debito : 0,
        ];

        $RAI =
        $resultado_operacionais_82['proveitos_operacionais_credito'] + $resultado_operacionais_82['custos_operacionais']
        + $resultado_financeiro_83['proveitos_financeiros'] + $resultado_financeiro_83['custos_financeiros']
        + $resultado_em_associados_84['proveitos_em_associados'] + $resultado_em_associados_84['custos_em_associados']
        + $resultado_nao_operacionais_85['proveitos_nao_operacionais'] + $resultado_nao_operacionais_85['custos_nao_operacionais']
        + $resultado_extraordinario_86['proveitos_nao_operacionais'] + $resultado_extraordinario_86['custos_nao_operacionais'];

        $imposto_sobre_lucro = ($RAI * 0.25);
        $resultado_liquido_do_exercicio = ($RAI - $imposto_sobre_lucro);

        $data['imposto_sobre_lucro'] = $imposto_sobre_lucro;
        $data['resultado_liquido_do_exercicio'] = $resultado_liquido_do_exercicio;

        $debito_receber = 0;
        $credito_receber = 0;
        $total_debito_existencias = 0;
        $total_credito_existencias = 0;
        $total_debito_disponibilidade = 0;
        $total_credito_disponibilidade = 0;
        $total_debito_subssidiarias = 0;
        $total_credito_subssidiarias = 0;
        $ano_exercicio = Exercicio::where('estado', 1)->select('id', 'designacao')->first();

        $ano_exercicio_id = (int) $ano_exercicio->id;

        $movimentos_subssidiarias = MovimentoItem::with(['movimento'])->whereIn('subconta_id', $contas_subssidiarias)->select('subconta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'))->groupBy('subconta_id')->get();
        foreach ($movimentos_subssidiarias as $value) {
            $total_debito_subssidiarias += $value->debito;
            $total_credito_subssidiarias += $value->credito;
        }

        $movimentos_diponibilidade = MovimentoItem::with(['movimento'])->whereIn('subconta_id', $contas_disponibilidade)->select('subconta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'))->groupBy('subconta_id')->get();

        foreach ($movimentos_diponibilidade as $value) {
            $total_debito_disponibilidade += $value->debito;
            $total_credito_disponibilidade += $value->credito;
        }

        $movimento_itens = MovimentoItem::whereIn('subconta_id', $contas_existencias)->select('subconta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'))->groupBy('subconta_id')->get();

        foreach ($movimento_itens as $value) {
            $total_debito_existencias += $value->debito;
            $total_credito_existencias += $value->credito;
        }
        // dd($total_debito_existencias, $total_credito_existencias);

        $movimento_itens_contas_receber = MovimentoItem::whereIn('subconta_id', [122, 279])->select('subconta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'))->groupBy('subconta_id')->get();
        foreach ($movimento_itens_contas_receber as $value) {
            $debito_receber += $value->debito;
            $credito_receber += $value->credito;
        }


        // chamda do metodo para calcular o total

        $resultado_corporeas = $this->conversaoValores($conta_imobilizacoes_corporeas);
        $resultado_incorporeas = $this->conversaoValores($conta_imobilizacoes_incorporeas);

        $data['total_existencias'] = $total_debito_existencias - $total_credito_existencias;
        $data['total_disponibilidade'] = $total_debito_disponibilidade - $total_credito_disponibilidade;
        $data['total_subssidiarias'] = $total_debito_subssidiarias - $total_credito_disponibilidade;

        $data['contas_receber'] = $debito_receber - $credito_receber;

        $data['total_imobilizacoes_corporeas'] = $resultado_corporeas;
        $data['total_imobilizacoes_incorporeas'] = $resultado_incorporeas;

        $data['conta_do_activos_corrente'] = MovimentoItem::with(['conta', 'movimento'])->select(
            'conta_id',
            DB::raw('sum(debito) as debito'),
            DB::raw('sum(credito) as credito'),
        )->whereIn('conta_id', $contas_activos_corrente)
            ->groupBy('conta_id')
            ->get();

        $data['conta_do_activos_nao_corrente'] = MovimentoItem::with(['conta', 'movimento'])
            ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
            ->whereIn('conta_id', $contas_activos_nao_corrente)
            ->groupBy('conta_id')
            ->get();

        $data['conta_do_passivos_corrente'] = MovimentoItem::with(['conta', 'movimento'])
            ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
            ->whereIn('conta_id', $contas_passivos_corrente)
            ->groupBy('conta_id')
            ->get();

        $data['conta_do_passivos_nao_corrente'] = MovimentoItem::with(['conta', 'movimento'])
            ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
            ->whereIn('conta_id', $contas_passivos_nao_corrente)
            ->groupBy('conta_id')
            ->get();


        $capital_proprio = Conta::whereIn('numero', ['51', '55', '81', '88'])->pluck('id');
        $data['capital_proprio'] = MovimentoItem::with(['conta', 'movimento'])
            ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
            ->whereIn('conta_id', $capital_proprio)
            ->groupBy('conta_id')
            ->get();

        // dd($data['capital_proprio']);
        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['exercicio_actual'] = Exercicio::select('id', 'designacao As text')->where('estado', 1)->get();
        $data['exercicio_anterior'] = ($exercicio_anterior);
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();



        return Inertia::render('Balancos/Index', $data);
    }

    public function anoAnterior()
    {
        $exercicio_anterior = '';

        $data['exercicios_activo'] = Exercicio::select('id', 'designacao', 'estado')->where('estado', '!=', 1)->orderBy('id', 'desc')->first();

        $exercicio_anterior = $data;


        return $exercicio_anterior;
    }

    public function calculoProveitosOperacionais($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->whereBetween('numero', [61, 66]);
        })->whereIn('conta_id', $valoresBalnco)->get();

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoCustosOperacionais($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->whereBetween('numero', [71, 75]);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return 0;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoProveitosFinanceiros($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->where('numero', '>=', 66)->where('numero', '<', 67);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return null;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoCustosFinanceiros($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->where('numero', '>=', 76)->where('numero', '<', 77);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return null;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoProveitosAssociados($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->where('numero', '>=', 67)->where('numero', '<', 68);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return null;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoCustosAssociados($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->where('numero', '>=', 77)->where('numero', '<', 78);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return null;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoOutrosProveitosNOperacionais($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->where('numero', '>=', 68)->where('numero', '<', 69);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return null;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoOutrosCustosNOperacionais($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->where('numero', '>=', 78)->where('numero', '<', 79);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return null;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoProveitosExtraordinarios($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->where('numero', '>=', 69)->where('numero', '<', 70);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return null;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function calculoCustosExtraordinarios($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        $collect = collect([]);

        foreach ($item as $value) {
            $valoresBalnco[] = $value->conta_id;
        }

        $data = MovimentoItem::select(DB::raw('SUM(credito) AS credito'), DB::raw('SUM(debito) AS debito'))->whereHas('subconta', function ($querey) {
            $querey->where('numero', '>=', 79)->where('numero', '<', 80);
        })->whereIn('conta_id', $valoresBalnco)->get();

        if (!$data) {
            return null;
        }

        foreach ($data as $saldo) {
            $valCredito = ['credito' => $saldo->credito, 'debito' => $saldo->debito];
        }

        return $valCredito;
    }

    public function conversaoValores($item)
    {
        $valoresBalnco = [];
        $valDebito = 0;
        $valCredito = 0;
        $resultado = 0;
        foreach ($item as $value) {
            foreach ($value->sub_contas_empresa as $valor) {
                foreach ($valor->items_movimentos as $movimentos) {
                    $valDebito += $movimentos->debito;
                    $valCredito += $movimentos->credito;
                }
                $valoresBalnco = [
                    'debito' => $valDebito,
                    'credito' => $valCredito,
                ];
            }
        }
        if ($valoresBalnco['debito'] > $valoresBalnco['credito']) {
            $resultado = $valoresBalnco['debito'] - $valoresBalnco['credito'];
        }
        if ($valoresBalnco['credito'] > $valoresBalnco['debito']) {
            $resultado = $valoresBalnco['credito'] - $valoresBalnco['debito'];
        }

        return $resultado;
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

    public function imprimirBalanco(Request $request)
    {

        $classes_activo_corrente = Classe::whereIn('id', ['4', '5'])->pluck('id');
        $classes_activo_nao_corrente = Classe::whereIn('id', ['2', '3'])->pluck('id');

        $classes_passivos_corrente = Classe::whereIn('id', ['4'])->pluck('id');
        $classes_passivos_nao_corrente = Classe::whereIn('id', ['4'])->pluck('id');

        $contas_activos_corrente = ContaEmpresa::whereIn('classe_id', $classes_activo_corrente)->pluck('conta_id');
        $contas_activos_nao_corrente = ContaEmpresa::whereIn('classe_id', $classes_activo_nao_corrente)->pluck('conta_id');

        $contas_passivos_corrente = ContaEmpresa::whereIn('classe_id', $classes_passivos_corrente)->pluck('conta_id');
        $contas_passivos_nao_corrente = ContaEmpresa::whereIn('classe_id', $classes_passivos_nao_corrente)->pluck('conta_id');


        $data['conta_do_activos_corrente'] = MovimentoItem::with(['conta', 'movimento'])
            ->whereHas('movimento', function ($query) use ($request) {
                $query->when($request->exercicio_id, function ($query, $value) {
                    $query->orWhere('exercicio_id', $value);
                });
            })
            ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
            ->whereIn('conta_id', $contas_activos_corrente)
            ->groupBy('conta_id')
            ->get();

        $data['conta_do_activos_nao_corrente'] = MovimentoItem::with(['conta', 'movimento'])
            ->whereHas('movimento', function ($query) use ($request) {
                $query->when($request->exercicio_id, function ($query, $value) {
                    $query->orWhere('exercicio_id', $value);
                });
            })
            ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
            ->whereIn('conta_id', $contas_activos_nao_corrente)
            ->groupBy('conta_id')
            ->get();


        $data['conta_do_passivos_corrente'] = MovimentoItem::with(['conta', 'movimento'])
            ->whereHas('movimento', function ($query) use ($request) {
                $query->when($request->exercicio_id, function ($query, $value) {
                    $query->orWhere('exercicio_id', $value);
                });
            })
            ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
            ->whereHas('movimento', function ($query) use ($request) {
                $query->when($request->exercicio_id, function ($query, $value) {
                    $query->orWhere('exercicio_id', $value);
                });
            })
            ->whereIn('conta_id', $contas_passivos_corrente)
            ->groupBy('conta_id')
            ->get();

        $data['conta_do_passivos_nao_corrente'] = MovimentoItem::with(['conta', 'movimento'])
            ->whereHas('movimento', function ($query) use ($request) {
                $query->when($request->exercicio_id, function ($query, $value) {
                    $query->orWhere('exercicio_id', $value);
                });
            })
            ->select('conta_id', DB::raw('sum(debito) as debito'), DB::raw('sum(credito) as credito'),)
            ->whereIn('conta_id', $contas_passivos_nao_corrente)
            ->groupBy('conta_id')
            ->get();


        $data['exercicio'] = Exercicio::find($request->exercicio_id);
        $data['requests'] = $request->all('exercicio_id', 'data_inicio', 'data_final');
        $data['dados_empresa'] = $this->dadosEmpresaLogada();

        $pdf = PDF::loadView('pdf.contas.Balanco', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Balanco.pdf');
    }
    public function exportarExcel(Request $request)
    {
        $data['dados'] = $request;
        $data_inicio = $data['dados']->data_inicio;
        $data_final = $data['dados']->data_final;
        $exercicio_id = $data['dados']->exercicio_id;
        $tipo_balancete_id = $data['dados']->tipo_balancete_id;
        $periodo_id = $data['dados']->periodo_id;

        return Excel::download(new CentroDeCustoExport($data_inicio, $data_final, (int)$exercicio_id, $tipo_balancete_id, $periodo_id), 'balanco.xlsx');
    }

    public function getSubcontas(Request $request)
    {

        if ((int) $request->nota == 0) {
            $data['subcontas'] = SubConta::with(['conta'])
                ->whereIn('numero', [11.9, 12.9])
                ->get();
            $data['count'] = 0;
        } elseif ((int) $request->nota == 8) {

            // $data['contas'] = Conta::with(['subconta',])->where('nota', $request->nota)->get();
            $data['contas'] = Conta::with([
                'subconta' => function ($querey) {
                    $querey->where('tipo', 'M');
                },
                'subconta.items_movimentos'
            ])->where('nota', $request->nota)->get();

            $data['count'] = 1;
        } elseif ((int) $request->nota == 10) {

            // pegar os ids das subcontas
            $contas_disponibilidade = Conta::with([
                'subconta' => function ($query) {
                    $query->where('tipo', 'M');
                }
            ])
                ->where('nota', 10)
                ->get()
                ->map(function ($conta) {
                    return $conta->subconta->pluck('id');
                })
                ->flatten();

            $data['sub_contas'] = SubConta::with([
                'conta',
                'items_movimentos'
                => function ($query) {
                    $query->select(
                        'subconta_id',
                        DB::raw('sum(debito) as TotalDebito'),
                        DB::raw('sum(credito) as TotalCredito'),
                    )->groupBy('subconta_id');
                }
            ])->whereIn('id', $contas_disponibilidade)
                ->get();

            $data['subcontas'] = SubConta::with([
                'conta',
                'items_movimentos'
                => function ($query) {
                    $query->select(
                        'subconta_id',
                        DB::raw('sum(debito) as TotalDebito'),
                        DB::raw('sum(credito) as TotalCredito'),
                    )->groupBy('subconta_id');
                }
            ])->whereIn('conta_id', [28, 29, 30, 31])->where('tipo', 'M')
                ->get();

            $collect = collect([]);

            $subconta = 0;
            $debito = 0;
            $credito = 0;

            foreach ($data['sub_contas'] as $movimentos_item) {
                // dd($data['sub_contas']);
                foreach ($movimentos_item->items_movimentos as $item) {

                    $item->TotalDebito ? $debito = $item->TotalDebito : $debito = 0;
                    $item->TotalCredito ? $credito = $item->TotalCredito : $credito = 0;

                    if ($item->TotalDebito > $item->TotalCredito) {
                        $debito = $item->TotalDebito - $item->TotalCredito;
                    }
                    if ($item->TotalCredito > $item->TotalDebito) {

                        $credito = $item->TotalCredito - $item->TotalDebito;
                    }
                }

                $collect->push(['numSubConta' => $movimentos_item->numero . ' - ' . $movimentos_item->designacao, 'debito' => $debito, 'credito' => $credito]);
                $debito = 0;
                $credito = 0;
            }

            $data['subcontas1'] = $collect;
            $data['count'] = 3;
        } elseif ((int) $request->nota == 9) {

            $data['sub_contas'] = SubConta::with([
                'conta',
                'items_movimentos'
                => function ($query) {
                    $query->select(
                        'subconta_id',
                        DB::raw('sum(debito) as TotalDebito'),
                        DB::raw('sum(credito) as TotalCredito'),
                    )->groupBy('subconta_id');
                }
            ])->whereIn('numero', ['37.9', '31.1.1'])
                ->get();

            $data['subcontas'] = SubConta::with([
                'conta',
                'items_movimentos'
                => function ($query) {
                    $query->select(
                        'subconta_id',
                        DB::raw('sum(debito) as TotalDebito'),
                        DB::raw('sum(credito) as TotalCredito'),
                    )->groupBy('subconta_id');
                }
            ])->whereIn('numero', ['37.9', '31.1.1'])
                ->get();

            $collect = collect([]);

            $subconta = 0;
            $debito = 0;
            $credito = 0;

            foreach ($data['sub_contas'] as $movimentos_item) {
                // dd($data['sub_contas']);
                foreach ($movimentos_item->items_movimentos as $item) {
                    $item->TotalDebito ? $debito = $item->TotalDebito : $debito = 0;
                    $item->TotalCredito ? $credito = $item->TotalCredito : $credito = 0;

                    if ($item->TotalDebito > $item->TotalCredito) {
                        $debito = $item->TotalDebito - $item->TotalCredito;
                    }
                    if ($item->TotalCredito > $item->TotalDebito) {
                        $credito = $item->TotalCredito - $item->TotalDebito;
                    }
                }
                $collect->push(['numSubConta' => $movimentos_item->numero . ' - ' . $movimentos_item->designacao, 'debito' => $debito, 'credito' => $credito]);
                $debito = 0;
                $credito = 0;
            }
            $data['subcontas1'] = $collect;
            $data['count'] = 3;
        } else {

            $users = User::with('empresa')->findOrFail(auth()->user()->id);

            $conta = Conta::where('nota', $request->nota)->select('id', 'numero', 'nota', 'designacao')->first();

            if (!$conta) {
                return redirect()->back();
            } else {

                $data['sub_contas'] = SubConta::with([
                    'conta',
                    'items_movimentos'
                    => function ($query) {
                        $query->select(
                            'subconta_id',
                            DB::raw('sum(debito) as TotalDebito'),
                            DB::raw('sum(credito) as TotalCredito'),
                        )->groupBy('subconta_id');
                    }
                ])->where('conta_id', $conta->id)->where('tipo', 'M')
                    ->get();

                $data['subcontas'] = SubConta::with([
                    'conta',
                    'items_movimentos'
                    => function ($query) {
                        $query->select(
                            'subconta_id',
                            DB::raw('sum(debito) as TotalDebito'),
                            DB::raw('sum(credito) as TotalCredito'),
                        )->groupBy('subconta_id');
                    }
                ])->where('conta_id', $conta->id)
                    ->get();

                if (!($data['sub_contas'] || $data['subcontas'])) {
                    return redirect()->back();
                }
                $collect = collect([]);

                $subconta = 0;
                $debito = 0;
                $credito = 0;

                foreach ($data['sub_contas'] as $movimentos_item) {
                    // dd($data['sub_contas']);
                    foreach ($movimentos_item->items_movimentos as $item) {

                        $item->TotalDebito ? $debito = $item->TotalDebito : $debito = 0;
                        $item->TotalCredito ? $credito = $item->TotalCredito : $credito = 0;

                        if ($item->TotalDebito > $item->TotalCredito) {

                            $debito = $item->TotalDebito - $item->TotalCredito;
                            $credito = 0;
                        }
                        if ($item->TotalCredito > $item->TotalDebito) {
                            $credito = $item->TotalCredito - $item->TotalDebito;
                            $debito = 0;
                        }
                    }
                    $collect->push(['numSubConta' => $movimentos_item->numero . ' - ' . $movimentos_item->designacao, 'debito' => $debito, 'credito' => $credito]);
                    $debito = 0;
                    $credito = 0;
                }

                $data['subcontas1'] = $collect;
                // dd($data['subcontas1']);
                $data['count'] = 3;
            }
        }

        return Inertia::render('Balancos/Notas', $data);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }
}
