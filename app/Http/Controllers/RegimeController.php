<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Regime;

class RegimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Regime::paginate(10);
        
        return Inertia::render('Regime/index', ['regimes' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['regime'] = [];

        return Inertia::render('Regime/create', ['vazio' => $data]);
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
            "estado" => "required",
        ], 
        [
            "designacao.required" => "Campo Obrigatório",
            "estado.required" => "Campo Obrigatório",
        ]);

        try {
            $data = Regime::create([
                'designacao' => $request->designacao,
                'estado' => $request->estado
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
        $data['regime'] = Regime::findOrFail($id);
        
        return Inertia::render('Regime/Edit', $data);
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
            $regime = Regime::findOrFail($id);
            $regime->designacao = $request->designacao;
            $regime->update();
            return response()->json(['message', 'Dados actualizado com sucesso'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message', 'Não foi possível actualizar os dados!'], 201);
        }
        
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
