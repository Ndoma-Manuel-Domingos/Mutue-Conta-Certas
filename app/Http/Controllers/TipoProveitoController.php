<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TipoProveito;
use App\Exports\TipoProveitoExport;

use App\Exports\MovimentoExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class TipoProveitoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TipoProveito::get();

        return Inertia::render('TipoProveitos/Index', ['tipos_proveitos' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['regime'] = [];

        return Inertia::render('TipoProveitos/Create', ['vazio' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "designacao" => "required",
        ],
        [
            "designacao.required" => "Campo Obrigatório",
        ]);

        try {
            $tipo_proveito = TipoProveito::create([
                'designacao' => $request->designacao,
            ]);
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => "Não foi possível salvar os dados!"], 201);
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
        $data['tipo_proveito'] = TipoProveito::findOrFail($id);

        return Inertia::render('TipoProveitos/Edit', $data);
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
            $tipo_proveito = TipoProveito::findOrFail($id);
            $tipo_proveito->designacao = $request->designacao;
            $tipo_proveito->update();
            return response()->json(['message', 'Dados actualizado com sucesso'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message', 'Não foi possível actualizar os dados!'], 201);
        }

    }

    public function exportarExcel(){
        return Excel::download(new TipoProveitoExport(), 'tipo-proveito-excel.xlsx');
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
