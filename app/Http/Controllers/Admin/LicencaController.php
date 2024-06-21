<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Licenca;
use App\Models\LicencaModulo;
use App\Models\Modulo;
use App\Models\User;
use App\Models\UserModulo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LicencaController extends Controller
{
    //
    public function index()
    {

        $data['licenca'] = Licenca::with('modulos.modulo')->get();

        return Inertia::render('Admin/Licenca/Index', $data);
    }

    public function create(){

        $data['licenca'] = Licenca::get();
        $data['modulos'] = Modulo::select('id', 'designacao As text')->get();

        return Inertia::render('Admin/Licenca/Create', $data);
    }

    public function store(Request $request){

        try {
            $create = Licenca::create([
                'titulo' => $request->titulo,
                'preco' => $request->preco,
                'designacao' => $request->designacao,
            ]);

            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }

    }

    public function edit($id)
    {

        $data['licenca'] = Licenca::with('modulos')->where('id', $id)->first();
        $data['modulos'] = Modulo::select('id', 'designacao As text')->get();

        return Inertia::render('Admin/Licenca/Edit', $data);
    }

    public function show($id)
    {
        $data['licenca'] = Licenca::findOrFail($id);

        $data['modulos_licenca'] = LicencaModulo::with(["licenca", "modulo"])->where('licenca_id', $data['licenca']->id)->get();

        $data['modulos'] = Modulo::select('id', 'designacao As text')->get();

        return Inertia::render('Admin/Licenca/Show', $data);
    }


    public function licenca_modulo(Request $request)
    {
        $request->validate([
            'modulo_id' => 'required',
            'licenca_id' => 'required',
        ], []);

        $verificar = LicencaModulo::where('modulo_id', $request->modulo_id)->where('licenca_id', $request->licenca_id)->get();

        if(count($verificar) == 0){
            LicencaModulo::create([
                'modulo_id' => $request->modulo_id,
                'licenca_id' => $request->licenca_id
            ]);

            return redirect()->back();
        }
        return response()->json(['message' => "Este modulo já existe para esta licenca!"], 200);

    }

    public function licenca_modulo_eliminar($id)
    {
        $modulos_licenca = LicencaModulo::findOrFail($id);
        $modulos_licenca->delete();

        return redirect()->back();
    }


    public function update(Request $request, $id)
    {
        try {
            $licenca = Licenca::findOrFail($id);

            $licenca->designacao = $request->designacao;
            $licenca->preco = $request->preco;
            $licenca->titulo = $request->titulo;

            $licenca->update();

            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 201);
        }
    }

    public function licencas()
    {
        $data['licencas'] = Licenca::with(['modulos'])->get();

        return Inertia::render('Admin/Licenca', $data);
    }


    public function assinar_licencas($id)
    {
        $users = User::with('empresa')->findOrFail(auth()->user()->id);

        $licenca = Licenca::findOrFail($id);

        $create = UserModulo::create([
            'modulo_id' => $licenca->id,
            'user_id' => $users->id,
        ]);

        return redirect()->route('mf.dashboard');
    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
        try {

            $licenca = Licenca::findOrFail((int)$id);

            $verificar = LicencaModulo::where('licenca_id', $licenca->id)->get();

            foreach ($verificar as $item) {
                $delete = LicencaModulo::findOrFail($item->id);
                $delete->delete();
            }

            $licenca->delete();

            return response()->json(['message' => "O registo foi eliminado com sucesso!"], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function associarUsuarioLicenca($usuario_id, $licenca_id){
        dd($usuario_id, $licenca_id);

    }

}
