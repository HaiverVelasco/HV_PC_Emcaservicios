<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    protected $adminCredentials = [
        'username' => 'FabianMazorra',
        'password' => 'Emca4322Servicios?'
    ];

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

        // Agregamos logs para debugging
        \Log::info('Intento de login:', [
            'input_username' => $credentials['username'],
            'expected_username' => $this->adminCredentials['username'],
            'username_matches' => $credentials['username'] === $this->adminCredentials['username'],
            'password_matches' => $credentials['password'] === $this->adminCredentials['password']
        ]);

        if (
            $credentials['username'] === $this->adminCredentials['username'] && 
            $credentials['password'] === $this->adminCredentials['password']
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
