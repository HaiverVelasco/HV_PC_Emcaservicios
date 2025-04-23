<?php
// Este archivo contiene funciones helpers para la aplicación

if (!function_exists('isAdmin')) {
    /**
     * Verifica si el usuario actual es un administrador.
     * 
     * @return bool
     */
    function isAdmin()
    {
        // Verificar si existe request y sesión
        if (!request() || !request()->hasSession()) {
            return false;
        }
        
        // Verificar si el usuario es administrador
        if (request()->session()->get('is_admin') !== true) {
            return false;
        }
        
        // Verificar si existe un token y una fecha de expiración
        if (!request()->session()->has('admin_token') || !request()->session()->has('admin_token_expires_at')) {
            return false;
        }
        
        // Verificar si el token ha expirado
        $expiresAt = request()->session()->get('admin_token_expires_at');
        if (now()->gt($expiresAt)) {
            // Si ha expirado, limpiar la sesión y establecer mensaje
            request()->session()->forget(['is_admin', 'admin_token', 'admin_token_expires_at']);
            request()->session()->put('error', 'EL TIEMPO EXPIRÓ, VUELVE A INICIAR SESIÓN');
            return false;
        }
        
        return true;
    }
}