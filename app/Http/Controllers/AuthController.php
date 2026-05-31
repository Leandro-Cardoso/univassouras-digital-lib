<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registrar:
    public function register(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $dados['password'] = Hash::make($dados['password']);
        $user = User::create($dados);

        Auth::login($user);
        return redirect('/search-books'); // Redireciona.
    }

    // Login:
    public function login(Request $request)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();
            return redirect('/search-books'); // Redireciona.
        }

        return back()->withErrors(['login' => 'E-mail ou senha incorretos.']);
    }

    // Logout:
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Redireciona.
    }
}
