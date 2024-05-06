<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CentroDeCusto;
use Inertia\Inertia;

class CentroDeCustoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['centro_de_custo'] = CentroDeCusto::get();

        return Inertia::render('CentroDeCusto/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['centro_de_custo'] = CentroDeCusto::all();

        return Inertia::render('CentroDeCusto/Create', $data);
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

            $data = CentroDeCusto::create([
                'designacao' => $request->designacao,
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
            $data['centro_de_custo'] = CentroDeCusto::findOrFail($id);
            
            return Inertia::render('CentroDeCusto/Edit', $data);

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

            $centro = CentroDeCusto::findOrFail($id);
           
            $centro->designacao = $request->designacao;
            $centro->update();

            return response()->json(['message' => 'Registo actualizado com sucesso!'], 200);

        } catch (\Throwable $th) {
            return response()->json(['message' => 'Não foi possível actualizar o registo!'], 201);
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
