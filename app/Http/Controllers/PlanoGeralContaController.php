<?php

namespace App\Http\Controllers;

use App\Models\ClasseEmpresa;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDF;

class PlanoGeralContaController extends Controller
{
    
    use Config;

    //
    public function index(Request $request)
    {
        // Retorna a lista de posts

        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['plano'] = ClasseEmpresa::whereHas('classe', function ($query) use ($request) {
            $query->when($request->classe_designacao, function ($query, $value) {
                $query->where('designacao', 'like', "%" . $value . "%");
                $query->orWhere('numero', 'like', "%" . $value . "%");
            });
        })
        ->with(['empresa', 'classe.contas_empresa.conta', 'classe.contas_empresa.sub_contas_empresa'])
        ->where('empresa_id', $this->empresaLogada())
        ->get();

        // dd($data['plano']);

        return Inertia::render('PlanoGeralConta/Index', $data);
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

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirPlano()
    {

        $user = auth()->user();
        
        if ($user) {

            $user = User::find($user->id);

            $data['dados_empresa'] = Empresa::with(['contactos', 'endereco', 'contactos'])->where('estado_empresa_id', '1')->where('user_id', $user->id)->first();
            
        }

        $data['plano_data'] = ClasseEmpresa::with(['empresa', 'classe.contas_empresa.conta', 'classe.contas_empresa.sub_contas_empresa'])->get(); //;->where('empresa_id', $users->empresa_id)->paginate(10);
        // $data['dados_empresa'] = $this->dadosEmpresaLogada();
        $pdf = PDF::loadView('pdf.contas.PlanoContas', $data)->setPaper('a3', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Contas.pdf');
    }
}
