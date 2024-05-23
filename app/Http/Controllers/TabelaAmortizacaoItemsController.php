<?php

namespace App\Http\Controllers;

use App\Models\MovimentoItem;
use App\Models\TabelaAmortizacao;
use App\Models\TabelaAmortizacaoItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;

class TabelaAmortizacaoItemsController extends Controller
{
    
    use Config;

    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['items'] = TabelaAmortizacaoItem::with(['amortizacao'])->get();
        
        return Inertia::render('TabelaAmortizacaoItem/Index', $data);
    }

    public function create()
    {
        $data['amortizacoes'] = TabelaAmortizacao::select('id', DB::raw('CONCAT(ordem, " - ", designacao) AS text'))->get();
        
        return Inertia::render('TabelaAmortizacaoItem/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([ 
            "designacao" => "required", 
            "taxa" => "required", 
            "vida_util" => "required", 
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "taxa.required" => "Campo Obrigatório",
            "vida_util.required" => "Campo Obrigatório",
        ]);
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
            
        if($this->empresaLogada()){
            
            TabelaAmortizacaoItem::create([
                'amortizacao_id' => $request->amortizacao_id,
                'designacao' => $request->designacao,
                'taxa' => $request->taxa,
                'vida_util' => $request->vida_util,
                'created_by' => auth()->user()->id,
            ]);
        
        }
        
        // return redirect()->route('classes.index');
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    
        // Salva um novo post no banco de dados
    }
    
    public function get_items($id)
    {
        $item = TabelaAmortizacao::findOrFail($id);
        
        $items = TabelaAmortizacaoItem::with(['amortizacao'])->where('amortizacao_id', $item->id)->get();
        
        return response()->json(
            [
                'item' => $item, 
                "items"=> $items
            ]
        , 200);
    }
    
    public function get_dados_sub_conta($id)
    {
        $items = MovimentoItem::where('subconta_id', $id)->get();
        
        $credito = 0;
        $debito = 0;
        
        foreach ($items as $item) {
            $credito += $item->credito;
            $debito += $item->debito;
        }
        
        
        if($credito > $debito) {
            $saldo = $credito - $debito;
        }elseif($credito < $debito){
            $saldo = $debito - $credito;
        }else{
            $saldo = 0;
        }
        
        return response()->json(
            [
                'saldo' => $saldo, 
            ]
        , 200);
    }
    
    public function get_dados_item($id)
    {
        $item = TabelaAmortizacaoItem::findOrFail($id);
        
        return response()->json(
            [
                'item' => $item, 
            ]
        , 200);
    }
    
        
    public function edit($id)
    {        
        $data['amortizacoes'] = TabelaAmortizacao::select('id', DB::raw('CONCAT(ordem, " - ", designacao) AS text'))->get();
        
        // Exibe o formulário para editar um post
        $data['item'] = TabelaAmortizacaoItem::findOrFail($id);
        
        return Inertia::render('TabelaAmortizacaoItem/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([ 
            "designacao" => "required", 
            "taxa" => "required", 
            "vida_util" => "required", 
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "taxa.required" => "Campo Obrigatório",
            "vida_util.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $diario = TabelaAmortizacaoItem::findOrFail($id);
        $diario->amortizacao_id = $request->amortizacao_id;
        $diario->taxa = $request->taxa;
        $diario->vida_util = $request->vida_util;
        $diario->designacao = $request->designacao;
        $diario->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    // public function imprimirDiario(){ 
    //     // $data['diario_data'] = Diario::with(['empresa', 'classe.contas_empresa.conta', 'classe.contas_empresa.sub_contas_empresa'])->get(); //;->where('empresa_id', $users->empresa_id)->paginate(10);
    //     $data['categorias'] = TabelaAmortizacao::with(['empresa'])->get();
        
    //     $pdf = PDF::loadView('pdf.contas.Diario', $data)->setPaper('a3', 'landscape');
    //     $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
    //     return $pdf->stream('Contas.pdf');
    // }
}
