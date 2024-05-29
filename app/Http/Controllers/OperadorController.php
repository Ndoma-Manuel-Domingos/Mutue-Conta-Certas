<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserEmpresa;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Exports\UserEmpresaExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class OperadorController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        $data['operadores'] = UserEmpresa::with(["empresa", "user"])
        ->where('empresa_id', $this->empresaLogada())
        ->get();

        return Inertia::render('Operadores/Index', $data);
    }

    public function create()
    {

        $data['operadores'] = [];

        return Inertia::render('Operadores/Create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $full = $request->input('nome');
        $usernames = preg_split('/\s+/', strtolower($full), -1, PREG_SPLIT_NO_EMPTY);

        $username = head($usernames) . '.' . last($usernames);

        $user = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'username' => $username,
            'password' => Hash::make($request->password),
            'telefone' => "000-000-000",
            'is_admin' => 0,
        ]);

        return response()->json(['message' => "Dados salvos com sucesso!"], 200);

    }

    public function get_diario($id)
    {

    }


    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        // Exclui um post específico do banco de dados
    }

    public function exportarExcel(){
        return Excel::download(new UserEmpresaExport(), 'operador-excel.xlsx');
    }
}
