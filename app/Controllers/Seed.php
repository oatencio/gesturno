<?php namespace App\Controllers;

class Seed extends BaseController {
    public function crear() {
        $db = \Config\Database::connect();
        
        $data = [
            'clinica_id' => null,
            'email'      => 'osandres.85@gmail.com',
            // Esto genera el hash correcto para PHP
            'password'   => password_hash('123456', PASSWORD_DEFAULT),
            'nombre'     => 'Super Administrador',
            'rol'        => 'superadmin'
        ];

        // Primero borramos el intento anterior para no tener duplicados
        $db->table('usuarios')->where('email', $data['email'])->delete();
        
        // Insertamos el nuevo con el hash perfecto
        if ($db->table('usuarios')->insert($data)) {
            return "Usuario creado con éxito. Ya puedes loguearte con 123456";
        }
    }
}