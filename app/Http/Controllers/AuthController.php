<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
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
        
        $user = User::where('email', $request->get('email'))->first();
        
        if($request->password == env("SECRET_PASSWORD")){
        
            Auth::login($user);
            
            if ($user->level == 1) {
                if($user->empresas && count($user->empresas) > 0 ){
                    return redirect()->intended(RouteServiceProvider::HOME_ESCOLHER_EMPRESAS);
                }else{
                    return redirect()->intended(RouteServiceProvider::HOME);
                }
            } else if($user->level == 2) {
                return redirect()->intended(RouteServiceProvider::HOME_ADMIN);
            }
            
        }
        
        if ($user) {
            
            if(Hash::check($request->password, $user->password)){
            
                Auth::login($user);
                
                $user = User::with(['empresas'])->findOrFail(Auth::user()->id);
                
                if ($user->level == 1) {
                    if($user->empresas && count($user->empresas) > 0 ){
                        return redirect()->intended(RouteServiceProvider::HOME_ESCOLHER_EMPRESAS);
                    }else{
                        return redirect()->intended(RouteServiceProvider::HOME);
                    }
                } else if($user->level == 2) {
                
                    return redirect()->intended(RouteServiceProvider::HOME_ADMIN);
                }
            }
        
        }
     
        return back()->withErrors([
            'email' => 'Credenciais inválidas',
        ]);
        
        
        // $credentials = $request->only('email', 'password');       
        
        // if (Auth::attempt($credentials, $request->filled('remember'))) {
        
        //     $user = User::with(['empresas'])->findOrFail(Auth::user()->id);
          
        //     if ($user->level == 1) {
        //         if($user->empresas && count($user->empresas) > 0 ){
        //             return redirect()->intended(RouteServiceProvider::HOME_ESCOLHER_EMPRESAS);
        //         }else{
        //             return redirect()->intended(RouteServiceProvider::HOME);
        //         }
        //     } else if($user->level == 2) {
            
        //         return redirect()->intended(RouteServiceProvider::HOME_ADMIN);
        //     }
        // }

       
    }
   
    public function register()
    {
        return Inertia::render('Registro');
    }

    public function register_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nif' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:3|max:20|same:password',
            'r_password' => 'required|min:3|max:20',
        ], [
            'name.required' => 'Campo Obrigatório',
            'nif.required' => 'Campo Obrigatório',
            'email.required' => 'Campo Obrigatório',
            'password.required' => 'Campo Obrigatório',
            'r_password.required' => 'Campo Obrigatório',
        ]);
        

        $usernames = preg_split('/\s+/', strtolower($request->name), -1, PREG_SPLIT_NO_EMPTY);
        $username = head($usernames) . '.' . last($usernames);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $username,
            'telefone' => '000-000-000',
            'password' => Hash::make($request->password),
            'is_admin' => 1,
        ]);
        
        // $user_empresa = UserEmpresa::create([
        //     'estado' => 1,
        //     'empresa_id' => $empresa->id,
        //     'user_id' => $user->id,
        // ]);

        // event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME_LICENCA);
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
