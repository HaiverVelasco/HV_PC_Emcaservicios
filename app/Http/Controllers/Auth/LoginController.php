<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // Tiempo de expiración del token en minutos (2 horas)
    protected $tokenExpiration = 120;

    public function showLoginOptions()
    {
        // Verificamos si ya hay una sesión de admin activa
        if ($this->checkAdminSession()) {
            return redirect()->route('equipment.list');
        }
        return view('auth.login');
    }

    public function showAdminLogin()
    {
        // Verificamos si ya hay una sesión de admin activa
        if ($this->checkAdminSession()) {
            return redirect()->route('equipment.list');
        }
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
            'session_id' => $request->session()->getId()
        ]);

        // Verificamos credenciales usando hash seguro
        if (
            $credentials['username'] === $adminUsername && 
            $adminPasswordHash && 
            Hash::check($credentials['password'], $adminPasswordHash)
        ) {
            // Regeneramos el id de sesión para prevenir session fixation
            $request->session()->regenerate();
            
            // Establecemos la sesión de administrador con valor estricto true
            $request->session()->put('is_admin', true);
            
            // Generamos y almacenamos un token de sesión único
            $token = Str::random(60);
            $request->session()->put('admin_token', $token);
            
            // Establecemos el timestamp de expiración (2 horas desde ahora)
            $expiresAt = now()->addMinutes($this->tokenExpiration);
            $request->session()->put('admin_token_expires_at', $expiresAt);
            
            // Guardar el timestamp de inicio de sesión para las alertas
            $request->session()->put('admin_session_start_time', now()->timestamp * 1000); // En milisegundos para JavaScript
            
            // Registramos información de la sesión creada
            Log::info('Admin login successful', [
                'session_id' => $request->session()->getId(),
                'is_admin_set' => $request->session()->has('is_admin'),
                'token_generated' => !empty($token),
                'expires_at' => $expiresAt
            ]);
            
            return redirect()->route('equipment.list')->with('success', '¡Bienvenido, Administrador!');
        }

        return back()->withErrors([
            'username' => 'Las credenciales no son correctas.'
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Log::info('Logout called', [
            'session_id' => $request->session()->getId(),
            'had_is_admin' => $request->session()->has('is_admin'),
            'had_token' => $request->session()->has('admin_token')
        ]);
        
        // Eliminamos las variables de sesión
        $request->session()->forget(['is_admin', 'admin_token', 'admin_token_expires_at']);
        
        // Invalidamos la sesión y regeneramos el token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('info', 'Has cerrado sesión correctamente');
    }
    
    /**
     * Verifica si la sesión de administrador está activa y no ha expirado
     */
    protected function checkAdminSession()
    {
        if (session('is_admin') !== true) {
            return false;
        }
        
        // Verificar si hay un token y una fecha de expiración
        if (!session('admin_token') || !session('admin_token_expires_at')) {
            return false;
        }
        
        // Verificar si el token ha expirado
        $expiresAt = session('admin_token_expires_at');
        if (now()->gt($expiresAt)) {
            // Si ha expirado, limpiar la sesión y establecer mensaje de expiración
            session()->forget(['is_admin', 'admin_token', 'admin_token_expires_at']);
            session()->flash('error', 'EL TIEMPO EXPIRÓ, VUELVE A INICIAR SESIÓN');
            return false;
        }
        
        return true;
    }
}
