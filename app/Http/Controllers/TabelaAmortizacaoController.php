<?php

namespace App\Http\Controllers;

use App\Models\TabelaAmortizacao;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDF;

class TabelaAmortizacaoController extends Controller
{
    
    use Config;

    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['amortizacoes'] = TabelaAmortizacao::get();
        
        return Inertia::render('TabelaAmortizacao/Index', $data);
    }

    public function create()
    {
        $data['categorias'] = [];
       
        return Inertia::render('TabelaAmortizacao/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([ "designacao" => "required", ], 
        [
            "designacao.required" => "Campo Obrigatório",
        ]);
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
            
        if($this->empresaLogada()){
            
            TabelaAmortizacao::create([
                'ordem' => $request->ordem,
                'designacao' => $request->designacao,
                'created_by' => auth()->user()->id,
                'empresa_id' => $this->empresaLogada(),
            ]);
        
        }
        
        // return redirect()->route('classes.index');
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    
        // Salva um novo post no banco de dados
    }
    
    public function edit($id)
    {
        // Exibe o formulário para editar um post
        $data['amortizacao'] = TabelaAmortizacao::findOrFail($id);
        
        return Inertia::render('TabelaAmortizacao/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "designacao" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $diario = TabelaAmortizacao::findOrFail($id);
        $diario->ordem = $request->ordem;
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
