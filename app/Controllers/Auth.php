<?php namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController {
    
    public function login() {
        return view('login');
    }

    public function intentarLogin() {
        $session = session();
        $db = \Config\Database::connect();
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $db->table('usuarios')->where('email', $email)->get()->getRow();

        if ($user && password_verify($password, $user->password)) {
            // Guardamos el rol en la sesión para controlar accesos después
            $session->set([
                'usuario_id' => $user->id,
                'clinica_id' => $user->clinica_id, // Será NULL si es Superadmin
                'nombre'     => $user->nombre,
                'rol'        => $user->rol,        // <--- NUEVO: Guardamos el rol
                'isLoggedIn' => true
            ]);

            // REDIRECCIÓN DINÁMICA SEGÚN ROL
            if ($user->rol === 'superadmin') {
                return redirect()->to(base_url('superadmin')); // Tu panel maestro
            } else {
                return redirect()->to(base_url('turnos'));    // Panel de clínica normal
            }
        }

        return redirect()->back()->with('error', 'Credenciales incorrectas');
    }

    public function logout() {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}