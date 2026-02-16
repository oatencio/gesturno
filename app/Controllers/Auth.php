<?php namespace App\Controllers;

use App\Models\UsuarioModel; // Necesitarás crear este modelo simple

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
            $session->set([
                'usuario_id' => $user->id,
                'clinica_id' => $user->clinica_id,
                'nombre'     => $user->nombre,
                'isLoggedIn' => true
            ]);
            return redirect()->to(base_url('turnos'));
        }

        return redirect()->back()->with('error', 'Credenciales incorrectas');
    }

    public function logout() {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}