<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Registramos información de depuración
        Log::info('AdminMiddleware: Checking admin status', [
            'has_session' => $request->session()->has('is_admin'),
            'is_admin_value' => $request->session()->get('is_admin'),
            'has_token' => $request->session()->has('admin_token'),
            'session_id' => $request->session()->getId(),
            'route' => $request->route()->getName()
        ]);

        // Verificar si el usuario está autenticado como administrador
        if (!$this->isValidAdminSession($request)) {
            // Si no es administrador o la sesión expiró, redirigir al login
            Log::info('AdminMiddleware: Access denied - redirecting to login');
            return redirect()->route('login');
        }
        
        // Si es administrador con sesión válida, continuar con la solicitud
        Log::info('AdminMiddleware: Access granted - continuing request');
        return $next($request);
    }

    /**
     * Verifica si la sesión de administrador es válida y no ha expirado
     */
    protected function isValidAdminSession(Request $request): bool
    {
        // Verificar si tiene el flag de administrador
        if ($request->session()->get('is_admin') !== true) {
            return false;
        }
        
        // Verificar si hay un token de sesión
        if (!$request->session()->has('admin_token')) {
            return false;
        }
        
        // Verificar si el token ha expirado
        $expiresAt = $request->session()->get('admin_token_expires_at');
        if (!$expiresAt || now()->gt($expiresAt)) {
            // Limpiar la sesión expirada
            $request->session()->forget(['is_admin', 'admin_token', 'admin_token_expires_at']);
            // Establecer mensaje de expiración
            $request->session()->put('error', 'EL TIEMPO EXPIRÓ, VUELVE A INICIAR SESIÓN');
            return false;
        }
        
        return true;
    }
}
