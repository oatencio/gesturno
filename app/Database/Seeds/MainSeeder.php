<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // Insertar Clínica Inicial
        $clinica = [
            'id'     => 1,
            'nombre' => 'Centro Médico Pro',
            'plan'   => 'basico',
            'estado' => 'activo'
        ];
        $this->db->table('clinicas')->insert($clinica);

        // Insertar tu Super Usuario
        $usuario = [
            'clinica_id' => null,
            'rol'        => 'superadmin',
            'email'      => 'osandres.85@gmail.com',
            'password'   => '$2y$10$OIeZzNFkMx8drNItqL52fOPGpPQpOgYbwz47g/zXBukMGxVkzrmYC', // Tu password actual
            'nombre'     => 'Super Administrador',
        ];
        $this->db->table('usuarios')->insert($usuario);
    }
}