<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Municipio;
use App\Models\Provincia;

use App\Exports\MunicipioExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['municipio'] = Municipio::with(['provincia'])->get();

        return Inertia::render('Admin/Municipio/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['municipio'] = Municipio::with(['provincia'])->get();
        $data['provincias'] = Provincia::select('id', 'designacao As text')->get();
        return Inertia::render('Admin/Municipio/Create', $data);
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
            $data = Municipio::create([
                'designacao' => $request->designacao,
                'provincias_id' => $request->provincias_id,
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
        $data['municipio'] = Municipio::with(['provincia'])->where('id', $id)->first();
        $data['provincias'] = Provincia::select('id', 'designacao As text')->get();
        return Inertia::render('Admin/Municipio/Edit', $data);
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
            $municipio = Municipio::findOrFail($id);

            $municipio->designacao = $request->designacao;
            $municipio->provincias_id = $request->provincias_id;
            $municipio->update();
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }
    }

    public function exportarExcel(){
        return Excel::download(new MunicipioExport(), 'municipio-excel.xlsx');
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
