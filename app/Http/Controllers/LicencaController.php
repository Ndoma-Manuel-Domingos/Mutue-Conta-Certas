<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use App\Models\UserEmpresa;
use App\Models\Licenca;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class LicencaController extends Controller
{
    //
    public function index(){
        $data['licenca'] = Licenca::get();

        return Inertia::render('Licenca/Index', $data);
    }

    public function create(){

        $data['licenca'] = Licenca::get();
        return Inertia::render('Licenca/Create', $data);
    }

    public function store(Request $request){
        try {
            $data = Licenca::create([
                'titulo' => $request->titulo,
                'modulos' => $request->modulo,
                'designacao' => $request->designacao,
            ]);
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }

    }

    public function edit($id){

        $data['licenca'] = Licenca::where('id', $id)->first();

        return Inertia::render('Licenca/Edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $licenca = Licenca::findOrFail($id);

            $licenca->designacao = $request->designacao;
            $licenca->modulos = $request->modulos;
            $licenca->titulo = $request->titulo;
            $licenca->update();
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }
    }

    public function licencas()
    {
        return Inertia::render('Licenca');
    }



}
