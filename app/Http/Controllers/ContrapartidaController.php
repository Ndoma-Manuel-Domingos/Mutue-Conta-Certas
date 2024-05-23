<?php

namespace App\Http\Controllers;

use App\Models\Contrapartida;
use App\Exports\ContrapartidaExport;
use App\Models\SubConta;
use App\Models\TipoCredito;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDF;
use App\Exports\MovimentoExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
class ContrapartidaController extends Controller
{

    use Config;

    //
    public function index(Request $request)
    {
        // Retorna a lista de posts
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['contrapartidas'] = Contrapartida::with(['tipo_credito', 'sub_conta'])->get();

        return Inertia::render('Contrapartidas/Index', $data);
    }

    public function create()
    {
        $data['tipos_creditos'] = TipoCredito::select('id', 'designacao AS text')->orderBy('designacao', 'asc')->get();
        $data['sub_contas'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->orderBy('numero', 'asc')->get();

        return Inertia::render('Contrapartidas/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            "sub_conta_id" => "required",
            "tipo_credito_id" => "required",
        ],
        [
            "sub_conta_id.required" => "Campo Obrigatório",
            "tipo_credito_id.required" => "Campo Obrigatório",
        ]);

        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        Contrapartida::create([
            'sub_conta_id' => $request->sub_conta_id,
            'tipo_credito_id' => $request->tipo_credito_id,
        ]);

        // return redirect()->route('classes.index');

        return response()->json(['message' => "Dados salvos com sucesso!"], 200);

        // Salva um novo post no banco de dados
    }

    public function get_subconta($id)
    {
        $subcontas = Contrapartida::with(['tipo_credito', 'sub_conta'])->where('tipo_credito_id', $id)->get();

        return response()->json(
            [
                "subcontas"=> $subcontas
            ], 200);
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $data['tipos_creditos'] = TipoCredito::select('id', 'designacao AS text')->get();
        $data['sub_contas'] = SubConta::select('id', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();

        // Exibe o formulário para editar um post
        $data['contrapartida'] = Contrapartida::findOrFail($id);

        return Inertia::render('Contrapartidas/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "sub_conta_id" => "required",
            "tipo_credito_id" => "required",
        ],
        [
            "sub_conta_id.required" => "Campo Obrigatório",
            "tipo_credito_id.required" => "Campo Obrigatório",
        ]);

        // Atualiza um post específico no banco de dados
        $contrapartida = Contrapartida::findOrFail($id);
        $contrapartida->sub_conta_id = $request->sub_conta_id;
        $contrapartida->tipo_credito_id = $request->tipo_credito_id;
        $contrapartida->update();

        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function exportarExcel(){
        return Excel::download(new ContrapartidaExport(), 'exercicio-excel.xlsx');
    }

}
