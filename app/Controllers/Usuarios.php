<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Usuarios extends BaseController
{
    public function index()
    {
        $model = new UsuarioModel();
        $clinicaId = session()->get('clinica_id');

        $data = [
            'usuarios' => $model->where('clinica_id', $clinicaId)->get()->getResult(),
            'titulo'   => 'Personal de la Clínica'
        ];

        return view('usuarios/usuarios_index', $data);
    }

    public function guardar()
    {
        $model = new UsuarioModel();
        
        $data = [
            'clinica_id' => session()->get('clinica_id'), // Asignación automática
            'nombre'     => $this->request->getPost('nombre'),
            'email'   => $this->request->getPost('email'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'rol'       => $this->request->getPost('rol')
        ];

        // Validación simple de duplicados
        if ($model->where('email', $data['email'])->first()) {
            return redirect()->back()->with('error', 'El email ya está en uso.')->withInput();
        }

        $model->insert($data);
        return redirect()->to(base_url('usuarios'))->with('success', 'Usuario creado correctamente.');
    }

    public function eliminar($id)
    {
        $model = new UsuarioModel();
        $clinicaId = session()->get('clinica_id');

        // Seguridad: solo puede eliminar si el usuario pertenece a su clínica
        // y no es él mismo
        if ($id == session()->get('id')) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        $model->where('id', $id)->where('clinica_id', $clinicaId)->delete();
        return redirect()->to(base_url('usuarios'))->with('success', 'Usuario eliminado.');
    }
}