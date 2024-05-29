<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Provincia;
use App\Models\Pais;

use App\Exports\ProvinciaExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class ProvinciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['provincia'] = Provincia::with(['pais'])->get();

        return Inertia::render('Provincia/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['provincia'] = Provincia::all();
        $data['paises'] = Pais::select('id', 'designacao As text')->get();
        return Inertia::render('Provincia/Create', $data);
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

            $data = Provincia::create([
                'designacao' => $request->designacao,
                'pais_id' => $request->pais_id
            ]);
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
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
        $data['provincia'] = Provincia::with(['pais'])->where('id', $id)->first();
        $data['paises'] = Pais::select('id', 'designacao As text')->get();
        return Inertia::render('Provincia/Edit', $data);
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
            $provincia = Provincia::findOrFail($id);

            $provincia->designacao = $request->designacao;
            $provincia->pais_id = $request->pais_id;
            $provincia->update();
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }
    }

    public function exportarExcel(){
        return Excel::download(new ProvinciaExport(), 'pais-excel.xlsx');
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
