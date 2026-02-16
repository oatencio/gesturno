<?php

namespace App\Controllers;

use App\Models\PacienteModel;

class Pacientes extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new PacienteModel();
        $clinicaId = session()->get('clinica_id');

        $data = [
            'pacientes' => $model->where('clinica_id', $clinicaId)->findAll(),
            'titulo' => 'Gestión de Pacientes'
        ];
        return view('pacientes_index', $data);
    }

    public function inactivos()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new PacienteModel();
        $data = [
            'pacientes' => $model->where('clinica_id', session()->get('clinica_id'))->onlyDeleted()->findAll(),
            'view' => 'inactivos'
        ];
        return view('pacientes_index', $data);
    }

    public function guardar()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new PacienteModel();
        $data = [
            'clinica_id'  => session()->get('clinica_id'),
            'dni'         => $this->request->getPost('dni'),
            'nombre'      => $this->request->getPost('nombre'),
            'apellido'    => $this->request->getPost('apellido'),
            'telefono'    => $this->request->getPost('telefono'),
            'email'       => $this->request->getPost('email'),
            'obra_social' => $this->request->getPost('obra_social'),
        ];
        $model->insert($data);
        return redirect()->to(base_url('pacientes'))->with('success', 'Paciente registrado');
    }

    public function editar($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new \App\Models\PacienteModel();
        $clinicaId = session()->get('clinica_id');

        // Preparamos los datos del POST
        $data = [
            'dni'         => $this->request->getPost('dni'),
            'nombre'      => $this->request->getPost('nombre'),
            'apellido'    => $this->request->getPost('apellido'),
            'telefono'    => $this->request->getPost('telefono'),
            'email'       => $this->request->getPost('email'),
            'obra_social' => $this->request->getPost('obra_social'),
        ];

        // Actualizamos solo si el ID y la clínica coinciden
        $model->where('id', $id)
            ->where('clinica_id', $clinicaId)
            ->set($data)
            ->update();

        return redirect()->to(base_url('pacientes'))->with('success', 'Ficha de paciente actualizada correctamente.');
    }

    public function eliminar($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new PacienteModel();
        $model->delete($id);
        return redirect()->to(base_url('pacientes'))->with('success', 'Paciente dado de baja');
    }

    public function restaurar($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new PacienteModel();
        $model->withDeleted()->where('id', $id)->set(['deleted_at' => null])->update();
        return redirect()->to(base_url('pacientes'))->with('success', 'Paciente reactivado');
    }

    /**
     * Este método es exclusivo para la creación rápida desde la Agenda
     */
    public function guardar_ajax()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new PacienteModel();
        $clinicaId = session()->get('clinica_id');

        $data = [
            'clinica_id'  => $clinicaId,
            'dni'         => $this->request->getPost('dni'),
            'nombre'      => $this->request->getPost('nombre'),
            'apellido'    => $this->request->getPost('apellido'),
            'obra_social' => $this->request->getPost('obra_social') ?: 'Particular',
            // Agregamos datos vacíos para evitar errores de BD si no se envían
            'telefono'    => '',
            'email'       => ''
        ];

        // Insertamos y obtenemos el ID generado
        $nuevoId = $model->insert($data);

        if ($nuevoId) {
            // Devolvemos JSON para que el JavaScript de la Agenda lo procese
            return $this->response->setJSON([
                'status' => 'success',
                'id'     => $nuevoId,
                'text'   => $data['dni'] . " - " . $data['apellido'] . ", " . $data['nombre']
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo crear el paciente.'
            ], 500);
        }
    }
}
