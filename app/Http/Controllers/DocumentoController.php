<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Documento::get();
      
        return Inertia::render('Documentos/Index', ['documentos' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['regime'] = [];

        return Inertia::render('Documentos/Create', ['vazio' => $data]);
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
            $data = Documento::create([
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
        $data['tipo_movimento'] = Documento::findOrFail($id);
        
        return Inertia::render('Documentos/Edit', $data);
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
            $tipo_credito = Documento::findOrFail($id);
            $tipo_credito->designacao = $request->designacao;
            $tipo_credito->update();
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
