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

    public function editar($id)
    {
        $model = new UsuarioModel();
        $clinicaId = session()->get('clinica_id');

        // Verificamos que el usuario pertenezca a la clínica antes de hacer nada
        $usuario = $model->where('id', $id)->where('clinica_id', $clinicaId)->first();

        if (!$usuario) {
            return redirect()->to(base_url('usuarios'))->with('error', 'Usuario no encontrado o sin permisos.');
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'email'  => $this->request->getPost('email'),
            'rol'    => $this->request->getPost('rol')
        ];

        // Lógica para la contraseña: solo se actualiza si se escribe algo nuevo
        $nuevaPassword = $this->request->getPost('password');
        if (!empty($nuevaPassword)) {
            $data['password'] = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        }

        // Actualizamos usando el ID y asegurando la clinica_id por seguridad extra
        $model->where('id', $id)->where('clinica_id', $clinicaId)->set($data)->update();

        return redirect()->to(base_url('usuarios'))->with('success', 'Usuario actualizado correctamente.');
    }

    public function eliminar($id)
    {
        $model = new UsuarioModel();
        $clinicaId = session()->get('clinica_id');

        // 1. Seguridad: No eliminarse a sí mismo
        if ($id == session()->get('id')) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        // 2. Seguridad: Verificar pertenencia a la clínica
        $usuario = $model->where('id', $id)->where('clinica_id', $clinicaId)->first();

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        // 3. Borrado Lógico
        $model->delete($id);

        return redirect()->to(base_url('usuarios'))->with('success', 'Usuario desactivado correctamente.');
    }
}
