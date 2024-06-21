<?php

namespace App\Http\Controllers;

use App\Models\Licenca;
use Illuminate\Http\Request;
use App\Models\LicencaUsuario;
use App\Models\User;
use Inertia\Inertia;


class LicencaUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['licenca_data'] = LicencaUsuario::with(['usuario', 'licenca'])->get();
        $data['licenca_em_utilizacao'] = LicencaUsuario::with(['usuario', 'licenca'])->where('estado', 1)->count();
        $data['licenca_sem_utilizacao'] = LicencaUsuario::with(['usuario', 'licenca'])->where('estado', 0)->count();

        return Inertia::render('Admin/LicencaUsuario/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['usuarios'] = User::select('id', 'name As text')->get();
        $data['licencas'] = Licenca::select('id', 'titulo As text')->get();

        return Inertia::render('Admin/LicencaUsuario/Create', $data);
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
            $data = LicencaUsuario::create([

                'id_licenca' => $request->licenca_id,
                'id_usuario' => $request->usuario_id,
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
        $data['licencaUsuario'] = LicencaUsuario::where('id', (int)$id)->first();

        $data['usuarios'] = User::select('id', 'name As text')->get();
        $data['licencas'] = Licenca::select('id', 'titulo As text')->get();

        return Inertia::render('Admin/LicencaUsuario/Edit', $data);
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

            $licencaUsuario = LicencaUsuario::findOrFail((int)$id);

            $licencaUsuario->id_licenca = $request->licenca_id;
            $licencaUsuario->id_usuario = $request->usuario_id;
            $licencaUsuario->update();

            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
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

    public function mudaEstadoLicenca($id)
    {

        $licencaUsuario = LicencaUsuario::where('id', (int)$id)->select('id', 'estado')->first();

        $estadoLicenccaUsuario = $licencaUsuario->estado;

        $estado1 = "";

        if ($estadoLicenccaUsuario == 1) {
            $estado1 = 0;
        } else {
            $estado1 = 1;
        }

        try {
            $licencaUsuario->estado = $estado1;
            $licencaUsuario->update();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }


        return response()->json(['message' => "Dados aletrados com sucesso!"], 200);
    }
}
