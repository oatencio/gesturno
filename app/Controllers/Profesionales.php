<?php

namespace App\Controllers;

use App\Models\ProfesionalModel;
use CodeIgniter\Controller;

class Profesionales extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new ProfesionalModel();
        $clinicaId = session()->get('clinica_id');

        // Usamos el modelo para filtrar
        $data = [
            'profesionales' => $model->where('clinica_id', $clinicaId)->findAll()
        ];

        return view('profesionales_index', $data);
    }

    public function guardar()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new ProfesionalModel();

        $data = [
            'clinica_id'   => session()->get('clinica_id'),
            'nombre'       => $this->request->getPost('nombre'),
            'especialidad' => $this->request->getPost('especialidad'),
            'email'        => $this->request->getPost('email'),
            'telefono'     => $this->request->getPost('telefono'),
        ];

        // El modelo se encarga de la inserción de forma segura
        if ($model->insert($data)) {
            return redirect()->to(base_url('profesionales'))->with('success', 'Profesional añadido');
        } else {
            return redirect()->back()->with('error', 'No se pudo guardar');
        }
    }


    public function editar($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new ProfesionalModel();
        $clinicaId = session()->get('clinica_id');

        $data = [
            'nombre'       => $this->request->getPost('nombre'),
            'especialidad' => $this->request->getPost('especialidad'),
            'email'        => $this->request->getPost('email'),
            'telefono'     => $this->request->getPost('telefono'),
        ];

        // Solo actualiza si el profesional pertenece a esta clínica
        $model->where('id', $id)->where('clinica_id', $clinicaId)->set($data)->update();

        return redirect()->to(base_url('profesionales'))->with('success', 'Médico actualizado');
    }

    public function eliminar($id)
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $profModel = new \App\Models\ProfesionalModel();
        $turnoModel = new \App\Models\TurnoModel();
        $clinicaId = session()->get('clinica_id');

        // 1. Verificar que el profesional existe y pertenece a la clínica
        $profesional = $profModel->where('id', $id)->where('clinica_id', $clinicaId)->first();

        if (!$profesional) {
            return redirect()->back()->with('error', 'Profesional no encontrado.');
        }

        // 2. Validar turnos pendientes (de hoy en adelante)
        $hoy = date('Y-m-d H:i:s');
        $turnosPendientes = $turnoModel->where('profesional_id', $id)
            ->where('fecha_hora >=', $hoy)
            ->whereIn('estado', ['pendiente', 'espera'])
            ->countAllResults();

        if ($turnosPendientes > 0) {
            return redirect()->back()->with('error', "No se puede dar de baja. El profesional tiene $turnosPendientes turnos pendientes por atender o reasignar, deberá reasignar el turno a otro profesional.");
        }

        // 3. Borrado lógico
        $profModel->delete($id);
        return redirect()->to(base_url('profesionales'))->with('success', 'El profesional ha sido dado de baja correctamente.');
    }

    public function inactivos()
    {
        if (!session()->get('isLoggedIn')) return redirect()->to(base_url('login'));

        $model = new \App\Models\ProfesionalModel();
        $clinicaId = session()->get('clinica_id');

        $data = [
            'profesionales' => $model->where('clinica_id', $clinicaId)->onlyDeleted()->findAll(),
            'view' => 'inactivos' // Bandera para la vista
        ];

        return view('profesionales_index', $data);
    }

    public function restaurar($id)
    {
        $model = new \App\Models\ProfesionalModel();
        $clinicaId = session()->get('clinica_id');

        // 1. Usamos withDeleted() para que el modelo "vea" al profesional borrado
        // 2. Usamos set(['deleted_at' => null]) para limpiar la fecha de baja
        $model->withDeleted()
            ->where('id', $id)
            ->where('clinica_id', $clinicaId)
            ->set(['deleted_at' => null])
            ->update();

        return redirect()->to(base_url('profesionales'))->with('success', 'Profesional reactivado correctamente.');
    }
}
