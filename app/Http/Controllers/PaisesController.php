<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Pais;
use App\Exports\PaisExport;

use App\Exports\MovimentoExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class PaisesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['paises'] = Pais::get();

        return Inertia::render('Admin/Paises/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['paises'] = Pais::all();

        return Inertia::render('Admin/Paises/Create', $data);
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
            $data = Pais::create([
                'designacao' => $request->designacao,
                'nome_ingles' => $request->nome_ingles,
            ]);
            return response()->json(['message' => 'Dados salvos com sucesso'], 200);
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
        $data['paises'] = Pais::findOrFail($id);

        return Inertia::render('Admin/Paises/Edit', $data);
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
            $paises = Pais::findOrFail($id);
            $paises->designacao = $request->designacao;
            $paises->nome_ingles = $request->nome_ingles;
            $paises->update();
            return response()->json(['message' => 'Dados actualizados com sucesso'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }
    }


    public function exportarExcel()
    {
        return Excel::download(new PaisExport(), 'paises-excel.xlsx');
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

    public function imprimirPaises()
    {
    }
}
