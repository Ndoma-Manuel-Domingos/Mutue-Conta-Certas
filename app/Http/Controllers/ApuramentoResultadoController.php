<?php

namespace App\Http\Controllers;;

use App\Models\Conta;
use App\Models\Exercicio;
use App\Models\Periodo;
use App\Models\ContaEmpresa;
use App\Models\MovimentoItem;
use App\Models\SubConta;
use App\Models\TipoBalancete;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ApuramentoResultadoController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $startDate = $request->data_inicio;
        $endDate = $request->data_final;
        $exercicio_id = $request->exercicio_id;

        $users = User::with('empresa')->findOrFail(auth()->user()->id);
        $data['contas_apuramento'] = ContaEmpresa::select('classe_id')->whereIn('classe_id', [7, 8, 9])
            ->with([
                'classe',
                'conta.items_movimentos' => function ($query) {
                    $query->select(
                        'conta_id',
                        DB::raw('sum(debito) as TotalDebito'),
                        DB::raw('sum(credito) as TotalCredito')
                    )->groupBy('conta_id');
                },
                'sub_contas_empresa.items_movimentos' => function ($query) {
                    $query->select(
                        'subconta_id',
                        DB::raw('sum(debito) as total_debito'),
                        DB::raw('sum(credito) as total_credito')
                    )->groupBy('subconta_id');
                }
            ])
            ->groupBy('classe_id')
            ->get();
        // dd($data['contas_apuramento']);
        $data['registros'] = ContaEmpresa::whereIn('classe_id', [7, 8, 9])
            ->with([
                'classe',
                'conta.items_movimentos' => function ($query) {
                    $query->select(
                        'conta_id',
                        DB::raw('sum(debito) as TotalDebito'),
                        DB::raw('sum(credito) as TotalCredito')
                    )->groupBy('conta_id');
                },
                'sub_contas_empresa.items_movimentos' => function ($query) {
                    $query->select(
                        'subconta_id',
                        DB::raw('sum(debito) as total_debito'),
                        DB::raw('sum(credito) as total_credito')
                    )->groupBy('subconta_id');
                }
            ])
            ->whereHas('sub_contas_empresa.items_movimentos.movimento', function ($query) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $query->whereBetween('data_lancamento', [$startDate, $endDate]);
                } else {
                    // Considera o ano inteiro
                    $query->whereYear('data_lancamento', date('Y'));
                }
            })
            ->whereHas('sub_contas_empresa.items_movimentos.movimento', function ($query) use ($exercicio_id) {
                if ($exercicio_id) {
                    $query->where('exercicio_id', $exercicio_id);
                } else {
                    $query->where('exercicio_id', 1);
                }
            })
            ->distinct()
            ->distinct('classe_id')  // Distinct baseado na classe
            ->get();

        // Remove duplicatas manualmente no PHP com base na classe_id
        $data['registros'] = $data['registros']->unique('classe_id')->values();



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



        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();
        $data['periodos'] = Periodo::select('id', 'designacao As text')->get();

        return Inertia::render('ApuramentoResultado/Index', $data);
    }

    public function create() {}

    public function store(Request $request) {}


    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function imprimirMovimento() {}

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function pesquisaParametros(Request $request)
    {

        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['registros'] = ContaEmpresa::with(['classe'])
            ->with(['conta.items_movimentos', 'sub_contas_empresa.items_movimentos.movimento'])
            ->with(['sub_contas_empresa' => function ($querey) {
                $querey->select('*')->where('numero', '>=', 61)->where('numero', '<', 80);
            }])
            ->whereIn('classe_id', [7, 8, 9])
            ->distinct()
            ->distinct('classe_id')
            ->get();

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

        $resultados_operacionais_proveitos =  $this->calculoProveitosOperacionais($data['registros'], $request);
        $resultados_custos_operacionais =  $this->calculoCustosOperacionais($data['registros'], $request);
        $resultados_proveitos_financeiro =  $this->calculoProveitosFinanceiros($data['registros'], $request);
        $resultados_custos_financeiro =  $this->calculoCustosFinanceiros($data['registros'], $request);
        $resultados_proveitos_associados =  $this->calculoProveitosAssociados($data['registros'], $request);
        $resultados_custos_associados =  $this->calculoCustosAssociados($data['registros'], $request);
        $outros_proveitos_nao_operacionais =  $this->calculoOutrosProveitosNOperacionais($data['registros'], $request);
        $outros_custos_nao_operacionais =  $this->calculoOutrosCustosNOperacionais($data['registros'], $request);
        $outros_proveitos_extraordinarios =  $this->calculoProveitosExtraordinarios($data['registros'], $request);
        $outros_custos_extraordinarios =  $this->calculoCustosExtraordinarios($data['registros'], $request);
        $data['registros'] = $resultados_operacionais_proveitos['contas_apuramento'];

        $resultados_proveitos_operacionais_credito = $resultados_operacionais_proveitos['total_credito'];
        $resultados_proveitos_operacionais_debito = $resultados_operacionais_proveitos['total_debito'];

        $resultados_custos_operacionais_credito = $resultados_custos_operacionais['total_credito'];
        $resultados_custos_operacionais_debito = $resultados_custos_operacionais['total_debito'];

        $resultados_proveitos_associados_credito = $resultados_proveitos_associados['total_credito'];
        $resultados_proveitos_associados_debito = $resultados_proveitos_associados['total_debito'];

        $resultados_custos_associados_credito = $resultados_custos_associados['total_credito'];
        $resultados_custos_associados_debito = $resultados_custos_associados['total_debito'];

        $resultados_outros_proveitosNao_operacionais_credito = $outros_proveitos_nao_operacionais['total_credito'];
        $resultados_outros_proveitosNao_operacionais_debito = $outros_proveitos_nao_operacionais['total_debito'];

        $resultados_outros_custosNao_operacionais_credito = $outros_custos_nao_operacionais['total_credito'];
        $resultados_outros_custosNao_operacionais_debito = $outros_custos_nao_operacionais['total_debito'];

        $resultados_proveitos_extraordinario_credito = $outros_proveitos_extraordinarios['total_credito'];
        $resultados_proveitos_extraordinario_debito = $outros_proveitos_extraordinarios['total_debito'];

        $resultados_custos_extraordinario_credito = $outros_custos_extraordinarios['total_credito'];
        $resultados_custos_extraordinario_debito = $outros_custos_extraordinarios['total_debito'];

        $resultado_operacionais_82 = [
            'proveitos_operacionais_credito' => $resultados_proveitos_operacionais_credito ? $resultados_proveitos_operacionais_credito : 0,
            'custos_operacionais' => $resultados_custos_operacionais_debito ? $resultados_custos_operacionais_debito : 0,
        ];

        $resultado_financeiro_83 = [
            'proveitos_financeiros' => $resultados_proveitos_financeiro['total_credito'] ? $resultados_proveitos_financeiro['total_credito'] : 0,
            'custos_financeiros' => $resultados_custos_financeiro['total_credito'] ? $resultados_custos_financeiro['total_credito'] : 0,
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


        return Inertia::render('ApuramentoResultado/Index', $data);
    }

    public function calculoProveitosOperacionais($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data['contas_apuramento'] = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [61, 66]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data['contas_apuramento'] as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoCustosOperacionais($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [71, 75]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();

        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoProveitosFinanceiros($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [66, 67]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoCustosFinanceiros($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [76, 77]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoProveitosAssociados($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [67, 68]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoCustosAssociados($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [77, 78]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoOutrosProveitosNOperacionais($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [68, 69]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoOutrosCustosNOperacionais($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [78, 79]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoProveitosExtraordinarios($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [69, 70]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }

    public function calculoCustosExtraordinarios($item, $parametrosPesquisa)
    {
        $contas_id = [];
        $valCredito = 0;
        $total_credito = 0;
        $total_debito = 0;

        $startDate = $parametrosPesquisa->data_inicio;
        $endDate = $parametrosPesquisa->data_final;
        $exercicio_id = $parametrosPesquisa->exercicio_id;

        foreach ($item as $value) {
            $contas_id[] = $value->conta_id;
        }

        $data = MovimentoItem::select(
            DB::raw('SUM(credito) AS total_credito'),
            DB::raw('SUM(debito) AS total_debito'),
            'movimento_id'
        )
            ->whereHas('subconta', function ($query) {
                $query->whereBetween('numero', [79, 80]);
            })
            ->whereHas('movimento', function ($query) use ($startDate, $endDate, $exercicio_id) {
                $query->whereBetween('data_lancamento', [$startDate, $endDate])
                    ->where('exercicio_id', $exercicio_id);
            })
            ->whereIn('conta_id', $contas_id)
            ->groupBy('movimento_id')
            ->get();


        foreach ($data as $saldo) {
            $total_credito += $saldo->total_credito;
            $total_debito += $saldo->total_debito;
        }

        $data['total_credito'] = $total_credito;
        $data['total_debito'] = $total_debito;

        return $data;
    }
}
