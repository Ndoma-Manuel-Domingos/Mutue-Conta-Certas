<?php

namespace App\Http\Controllers;

use App\Models\ClassificacaoImobilizado;
use App\Models\Diario;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDF;

class ClassificacaoImobilizadosController extends Controller
{
    
    use Config;

    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['categorias'] = ClassificacaoImobilizado::with(['empresa'])
        ->where('empresa_id', $this->empresaLogada())
        ->get();
        
        return Inertia::render('CategoriaImobilizados/Index', $data);
    }

    public function create()
    {
        $data['categorias'] = [];
        
        $data['categorias'] = ClassificacaoImobilizado::with(['empresa'])
        ->where('empresa_id', $this->empresaLogada())
        ->get();
       
        return Inertia::render('CategoriaImobilizados/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            "designacao" => "required",
            "sigla" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "sigla.required" => "Campo Obrigatório",
        ]);
        
        $users = User::with('empresa')->findOrFail(auth()->user()->id);
            
        if($this->empresaLogada()){
            
            ClassificacaoImobilizado::create([
                'designacao' => $request->designacao,
                'sigla' => $request->sigla,
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
        $data['categoria'] = ClassificacaoImobilizado::findOrFail($id);
        
        return Inertia::render('CategoriaImobilizados/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "designacao" => "required",
            "sigla" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "sigla.required" => "Campo Obrigatório",
        ]);
        
        // Atualiza um post específico no banco de dados
        $diario = ClassificacaoImobilizado::findOrFail($id);
        $diario->designacao = $request->designacao;
        $diario->sigla = $request->sigla;
        $diario->update();
        
        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirDiario(){ 
        // $data['diario_data'] = Diario::with(['empresa', 'classe.contas_empresa.conta', 'classe.contas_empresa.sub_contas_empresa'])->get(); //;->where('empresa_id', $users->empresa_id)->paginate(10);
        $data['categorias'] = Diario::with(['empresa'])->get();
        
        $pdf = PDF::loadView('pdf.contas.Diario', $data)->setPaper('a3', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Contas.pdf');
    }
}
