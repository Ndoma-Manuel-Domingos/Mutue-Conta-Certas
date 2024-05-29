<?php

namespace App\Http\Controllers;

use App\Models\Ano;
use App\Exports\ExercicioExport;
use App\Models\Exercicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use PDF;
use App\Exports\MovimentoExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class ExercicioController extends Controller
{

    use Config;
    //
    public function index(Request $request)
    {
        // Retorna a lista de posts

        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $data['exercicios'] = Exercicio::when($request->input_busca_exercicios, function($query, $value){
            $query->where('designacao', 'like', "%".$value."%");
        })
        ->where('empresa_id', $this->empresaLogada())
        ->with(['empresa'])
        ->get();

        return Inertia::render('Exercicios/Index', $data);
    }

    public function create()
    {
        // Exibe o formulário para criar um novo post

        $data['anos'] = Ano::select('id', 'designacao As text')->get();

        return Inertia::render('Exercicios/Create', $data);
    }

    public function store(Request $request)
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $validator = Validator::make($request->all(), [
            "designacao" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if($this->empresaLogada()){

            Exercicio::create([
                'designacao' => $request->designacao,
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
        $empresa = Exercicio::findOrFail($id);

        $estado = "";

        if($empresa->estado == '1'){
            $estado = '2';
        }
        if($empresa->estado == '2'){
            $estado = '1';
        }

        $empresa->estado = $estado;
        $empresa->update();

    }
        
    public function get_info($id)
    {
        $exercicio = Exercicio::findOrFail($id);
        
        return response()->json(
            [
                'exercicio' => $exercicio, 
            ]
        , 200);
    }


    public function edit($id)
    {
        // Exibe o formulário para editar um post
        $data['exercicio'] = Exercicio::findOrFail($id);

        // $data['classes'] = Classe::select('id', 'designacao As d', DB::raw('CONCAT(numero, " - ", designacao) AS text'))->get();

        return Inertia::render('Exercicios/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "estado" => "required",
            "designacao" => "required",
        ],
        [
            "estado.required" => "Campo Obrigatório",
            "designacao.required" => "Campo Obrigatório",
        ]);

        // Atualiza um post específico no banco de dados
        $classe = Exercicio::findOrFail($id);
        $classe->estado = $request->estado;
        $classe->designacao = $request->designacao;
        $classe->update();

        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function imprimirExercicios(){
        $data['exercicio_data'] = Exercicio::with(['empresa'])->get();

        $pdf = PDF::loadView('pdf.contas.Exercicio', $data)->setPaper('a4', 'landscape');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream('Contas.pdf');
    }


    public function iniciar_sessao($id)
    {

        if($this->empresaLogada()){

            $exercicios = Exercicio::where('empresa_id', $this->empresaLogada())->get();
            foreach ($exercicios as $item) {
                $update = Exercicio::findOrFail($item->id);
                $update->estado = 2;
                $update->update();
            }

            $exercicio_a_operar = Exercicio::with('empresa')->findOrFail($id);
            $exercicio_a_operar->estado = 1;
            $exercicio_a_operar->update();

            Session::forget('exercicio_logada_mutue_contas_certas_2024');

            $exercicio = Exercicio::findOrFail($id);

            $exercicio_array = [
                'id' => $exercicio->id,
                'nome' => $exercicio->designacao,
            ];

            // Colocar um novo valor na sessão global
            Session::put('exercicio_logada_mutue_contas_certas_2024', $exercicio_array);
        }


        return response()->json(['message' => "Dados salvos com sucesso!"], 200);
    }

    public function logout_exercicio(Request $request)
    {

        Session::forget('exercicio_logada_mutue_contas_certas_2024');

        return redirect('/exercicios');

    }

    public function exportarExcel(){
        return Excel::download(new ExercicioExport(), 'exercicio-excel.xlsx');
    }
}
