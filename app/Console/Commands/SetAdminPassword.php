<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetAdminPassword extends Command
{
    /**
     * El nombre y la firma del comando.
     *
     * @var string
     */
    protected $signature = 'admin:set-password';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Establece o cambia las credenciales de administrador de forma segura';

    /**
     * Ejecutar el comando.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("\n=======================================================");
        $this->info("       CONFIGURACIÓN DE CREDENCIALES DE ADMIN          ");
        $this->info("=======================================================\n");

        // Solicitar el nombre de usuario
        $username = $this->ask('Introduce el nombre de usuario del administrador', 'admin');

        // Solicitar la contraseña
        $password = $this->secret('Introduce la contraseña del administrador');
        if (empty($password)) {
            $this->error('Error: La contraseña no puede estar vacía.');
            return 1;
        }

        // Confirmar la contraseña
        $confirmPassword = $this->secret('Confirma la contraseña del administrador');

        // Verificar que las contraseñas coinciden
        if ($password !== $confirmPassword) {
            $this->error('Error: Las contraseñas no coinciden.');
            return 1;
        }

        // Generar el hash de contraseña
        try {
            $hash = Hash::make($password);
            
            // Validar que el hash funciona correctamente
            if (!Hash::check($password, $hash)) {
                throw new Exception("No se pudo validar el hash generado.");
            }
            
            // Actualizar el archivo .env
            if ($this->updateEnvFile('ADMIN_USERNAME', $username) && 
                $this->updateEnvFile('ADMIN_PASSWORD_HASH', $hash)) {
                
                $this->info("\n¡Configuración completada con éxito!");
                $this->info("Se han actualizado las credenciales de administrador:");
                $this->info("- Usuario: {$username}");
                $this->info("- El hash de contraseña se ha almacenado de forma segura\n");
                
                // Mensaje sobre cómo funciona
                $this->info("Estas credenciales se utilizarán para el inicio de sesión.");
                $this->info("El sistema verifica la contraseña contra el hash almacenado en .env");
                $this->info("Para cambiar las credenciales, ejecuta este comando nuevamente.");
            } else {
                $this->error("Error: No se pudo actualizar el archivo .env");
                return 1;
            }
        } catch (Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }

        $this->info("\n=======================================================");
        return 0;
    }

    /**
     * Actualiza una variable en el archivo .env
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        
        if (!file_exists($path)) {
            $this->error("Error: No se encontró el archivo .env");
            return false;
        }
        
        // Leer el archivo .env
        $content = file_get_contents($path);
        
        // Escapar caracteres especiales en el valor (importante para el hash que contiene $)
        $value = str_replace('$', '\$', $value);
        
        // Verificar si la variable ya existe
        $pattern = "/^{$key}=.*/m";
        
        if (preg_match($pattern, $content)) {
            // Reemplazar la variable existente
            $content = preg_replace($pattern, "{$key}={$value}", $content);
        } else {
            // Agregar la variable al final
            $content .= "\n{$key}={$value}";
        }
        
        // Guardar los cambios
        if (file_put_contents($path, $content)) {
            return true;
        }
        
        return false;
    }
}
