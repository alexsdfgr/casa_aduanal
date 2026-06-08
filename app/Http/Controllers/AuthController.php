<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check())
            return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Buscar usuario manualmente
        $usuario = Usuario::where('username', $request->username)
            ->where('activo', true)
            ->first();

        // Verificar que existe y que la contraseña es correcta
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return back()
                ->withInput(['username' => $request->username])
                ->with('error', 'Usuario o contraseña incorrectos.');
        }

        // Iniciar sesión manualmente
        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}