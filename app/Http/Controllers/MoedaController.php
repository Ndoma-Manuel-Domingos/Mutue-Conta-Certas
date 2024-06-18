<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Moeda;

use App\Exports\MoedaExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class MoedaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data['moeda'] = Moeda::when($request->input_busca_moedas, function($query, $value){
            $query->where('designacao', 'like', "%".$value."%");
            $query->orWhere('sigla', 'like', "%".$value."%");
            $query->orWhere('pais', 'like', "%".$value."%");
        })
        ->get();

        return Inertia::render('Admin/Moeda/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['moeda'] = Moeda::all();

        return Inertia::render('Admin/Moeda/Create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $data = Moeda::create([
                'designacao' => $request->designacao,
                'sigla' => $request->sigla,
                'pais' => $request->pais,
            ]);

            return response()->json(['message' => 'Registo salvo com sucesso!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $data['moeda'] = Moeda::findOrFail($id);
            return Inertia::render('Admin/Moeda/Edit', $data);

        } catch (\Throwable $th) {
            //throw $th;
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $moeda = Moeda::findOrFail($id);

            $moeda->designacao = $request->designacao;
            $moeda->pais = $request->pais;
            $moeda->sigla = $request->sigla;
            $moeda->update();

            return response()->json(['message' => 'Registo actualizado com sucesso!'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Não foi possível actualizar o registo!'], 201);
        }
    }

    public function exportarExcel(){
        return Excel::download(new MoedaExport(), 'moeda-excel.xlsx');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
