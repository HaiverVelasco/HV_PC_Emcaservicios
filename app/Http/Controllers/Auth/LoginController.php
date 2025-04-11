<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Eliminamos las credenciales hardcodeadas

    public function showLoginOptions()
    {
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Obtenemos credenciales desde variables de entorno
        $adminUsername = env('ADMIN_USERNAME', 'admin');
        $adminPasswordHash = env('ADMIN_PASSWORD_HASH');

        // Agregamos logs para debugging (sin mostrar información sensible)
        Log::info('Intento de login:', [
            'input_username' => $credentials['username'],
            'expected_username' => $adminUsername,
            'username_matches' => $credentials['username'] === $adminUsername,
            'hash_exists' => !empty($adminPasswordHash),
        ]);

        // Verificamos credenciales usando hash seguro
        if (
            $credentials['username'] === $adminUsername && 
            $adminPasswordHash && 
            Hash::check($credentials['password'], $adminPasswordHash)
        ) {
            session(['is_admin' => true]);
            return redirect()->route('equipment.list')->with('success', '¡Bienvenido, Administrador!');
        }

        return back()->withErrors([
            'username' => 'Las credenciales no son correctas.'
        ])->withInput($request->except('password'));
    }

    public function logout()
    {
        session()->forget('is_admin');
        return redirect()->route('login')->with('info', 'Has cerrado sesión correctamente');
    }
}
