<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    //
    public function login()
    {
        return Inertia::render('Login');
    }
 
    public function login_store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], []);
        
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Autenticação bem-sucedida
            
            $user = User::with(['empresas'])->findOrFail(Auth::user()->id);
            
            if($user->empresas && count($user->empresas) > 0 ){
                // return redirect()->intended('/escolher-empresa-operar');
                return redirect()->intended(RouteServiceProvider::HOME_ESCOLHER_EMPRESAS);
            }else{
                return redirect()->intended(RouteServiceProvider::HOME);
            }
            
        }
 
        return back()->withErrors([
            'email' => 'Credenciais inválidas',
        ]);
       
    }
   
    public function register()
    {
        return Inertia::render('Registro');
    }

    public function register_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $empresa = Empresa::create([
            'nome' => $request->name,
            'email' => $request->email,
            'nif' => $request->nif,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'empresa_id' => $empresa->id,
            'is_admin' => 1,
        ]);

        // event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function logout(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->login = true;
        $user->update();
        
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
 

        return redirect('/');
    }
}
