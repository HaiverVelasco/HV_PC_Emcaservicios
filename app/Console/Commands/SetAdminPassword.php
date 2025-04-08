<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class SetAdminPassword extends Command
{
    protected $signature = 'admin:set-password';
    protected $description = 'Establece la contraseña del administrador';

    public function handle()
    {
        $username = $this->ask('Ingrese el nombre de usuario del administrador');
        $password = $this->secret('Ingrese la nueva contraseña');
        $confirmPassword = $this->secret('Confirme la contraseña');

        if ($password !== $confirmPassword) {
            $this->error('Las contraseñas no coinciden');
            return 1;
        }

        $envContent = File::get(base_path('.env'));
        
        // Actualizar o agregar las variables de entorno
        $envContent = preg_replace('/^ADMIN_USERNAME=.*$/m', 'ADMIN_USERNAME=' . $username, $envContent);
        $envContent = preg_replace('/^ADMIN_PASSWORD=.*$/m', 'ADMIN_PASSWORD=' . Hash::make($password), $envContent);

        if (strpos($envContent, 'ADMIN_USERNAME') === false) {
            $envContent .= "\nADMIN_USERNAME=" . $username;
        }
        if (strpos($envContent, 'ADMIN_PASSWORD') === false) {
            $envContent .= "\nADMIN_PASSWORD=" . Hash::make($password);
        }

        File::put(base_path('.env'), $envContent);

        $this->info('Credenciales de administrador actualizadas correctamente');
        return 0;
    }
}
