<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Config;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class OperadorController extends Controller
{
    use Config;
    //
    public function index(Request $request)
    {
        $data['operadores'] = User::where('level', 2)
        ->get();

        return Inertia::render('Admin/Operadores/Index', $data);
    }

    public function create()
    {

        $data['operadores'] = [];

        return Inertia::render('Admin/Operadores/Create', $data);
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        $full = $request->input('nome');
        $usernames = preg_split('/\s+/', strtolower($full), -1, PREG_SPLIT_NO_EMPTY);

        $username = head($usernames) . '.' . last($usernames);
        
        // Exclui um post específico do banco de dados
        try {
            $user = User::create([
                'name' => $request->nome,
                'email' => $request->email,
                'username' => $username,
                'password' => Hash::make($request->password),
                'telefone' => "000-000-000",
                'is_admin' => 1,
                'level' => 2,
            ]);
      
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
        
        return redirect()->back();

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

}
