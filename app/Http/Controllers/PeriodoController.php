<?php

namespace App\Http\Controllers;

use App\Exports\PeriodoExport;
use App\Models\Exercicio;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use PDF;
use App\Exports\MovimentoExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class PeriodoController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts

        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['periodos'] = Periodo::when($request->input_busca_periodos, function($query, $value){
            $query->where('designacao', 'like', "%".$value."%");
        })->where('empresa_id', $this->empresaLogada())->with(['empresa', 'exercicio'])->get();

        return Inertia::render('Periodos/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post

        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();

        return Inertia::render('Periodos/Create', $data);
    }

    public function store(Request $request)
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $validator = Validator::make($request->all(), [
            "designacao" => "required",
            "exercicio_id" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if($this->empresaLogada()){

            Periodo::create([
                'numero' => $request->numero,
                'designacao' => $request->designacao,
                'exercicio_id' => $request->exercicio_id,
                'empresa_id' => $this->empresaLogada(),
                'estado' => $request->estado,
            ]);
        }

        // return redirect()->route('classes.index');

        return response()->json(['message' => "Dados salvos com sucesso!"], 200);

        // Salva um novo post no banco de dados
    }

    public function show($id)
    {
        $diario = Periodo::findOrFail($id);

        $estado = "";

        if($diario->estado == "activo"){
            $estado = "desactivo";
        }
        if($diario->estado == "desactivo"){
            $estado = "activo";
        }

        $diario->estado = $estado;
        $diario->update();

        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }
    
    public function get_info($id)
    {
        $periodo = Periodo::findOrFail($id);
        
        return response()->json(
            [
                'periodo' => $periodo, 
            ]
        , 200);
    }

    public function edit($id)
    {
        // Exibe o formulário para editar um post
        $data['periodo'] = Periodo::findOrFail($id);

        $data['exercicios'] = Exercicio::select('id', 'designacao As text')->get();

        return Inertia::render('Periodos/Edit', $data);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            "estado" => "required",
            "designacao" => "required",
            "exercicio_id" => "required",
        ],
        [
            "estado.required" => "Campo Obrigatório",
            "designacao.required" => "Campo Obrigatório",
            "exercicio_id.required" => "Campo Obrigatório",
        ]);


        // Atualiza um post específico no banco de dados
        $periodo = Periodo::findOrFail($id);
        $periodo->numero = $request->numero;
        $periodo->estado = $request->estado;
        $periodo->designacao = $request->designacao;
        $periodo->exercicio_id = $request->exercicio_id;
        $periodo->update();

        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }
    
    
    public function get_periodos($id)
    {
        $exercicio = Exercicio::findOrFail($id);
        $periodos = Periodo::select('id', 'designacao AS text')->where('exercicio_id', $exercicio->id)->get();

        return response()->json(
            [
                'exercicio' => $exercicio,
                'periodos' => $periodos,
            ]
        , 200);
    }


    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirPeriodo(){
        $data['periodo_data'] = Periodo::with(['empresa', 'exercicio'])->get();

        $pdf = PDF::loadView('pdf.contas.Periodo', $data)->setPaper('a3', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Contas.pdf');
    }

    public function exportarExcel(){
        return Excel::download(new PeriodoExport(), 'exercicio-excel.xlsx');
    }
}
