<?php

namespace App\Controllers;

use App\Models\ClinicaModel; // Debes crear este modelo si no existe
use App\Models\UsuarioModel;

class Superadmin extends BaseController
{
    public function index()
    {
        // Solo el Superadmin puede entrar aquí
        if (session()->get('rol') !== 'superadmin') {
            return redirect()->to('/dashboard')->with('error', 'No tienes permisos.');
        }

        $model = new ClinicaModel();
        $data['clinicas'] = $model->findAll();

        return view('superadmin/clinicas_index', $data);
    }

    public function guardarClinica()
    {
        $db = \Config\Database::connect();
        $clinicaModel = new \App\Models\ClinicaModel($db);
        $usuarioModel = new \App\Models\UsuarioModel($db);

        $email = $this->request->getPost('email');

        if ($usuarioModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'El email ya está registrado.');
        }

        $db->transBegin();

        $clinicaId = $clinicaModel->insert([
            'nombre' => $this->request->getPost('nombre'),
            'email'  => $email,
            'plan'   => $this->request->getPost('plan'),
        ]);

        if (!$clinicaId) {
            $db->transRollback();
            dd($clinicaModel->errors());
        }

        $usuarioId = $usuarioModel->insert([
            'clinica_id' => $clinicaId,
            'nombre'     => 'Admin ' . $this->request->getPost('nombre'),
            'email'      => $email,
            'password'   => password_hash('123456', PASSWORD_DEFAULT),
            'rol'        => 'admin_clinica'
        ]);

        if (!$usuarioId) {
            $db->transRollback();
            dd($usuarioModel->errors());
        }

        $db->transCommit();

        return redirect()->back()->with('success', 'Clínica y administrador creados correctamente.');
    }

    public function usuarios()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios u');
        $builder->select('u.*, c.nombre as clinica_nombre');
        $builder->join('clinicas c', 'c.id = u.clinica_id', 'left');
        $builder->orderBy('c.nombre', 'ASC');

        $data = [
            'usuarios' => $builder->get()->getResult(),
            'titulo'   => 'Gestión Global de Usuarios',
            'view'     => 'usuarios'
        ];

        return view('superadmin/usuarios_index', $data);
    }

    public function resetPassword($id)
    {
        $model = new UsuarioModel();
        $nuevaPass = password_hash('Clinica123*', PASSWORD_DEFAULT);

        $model->update($id, ['password' => $nuevaPass]);

        return redirect()->back()->with('success', 'Contraseña restablecida a: Clinica123*');
    }

    public function renovarPlan()
    {
        $model = new ClinicaModel();
        $id = $this->request->getPost('clinica_id');
        $meses = $this->request->getPost('meses_a_sumar');

        $clinica = $model->find($id);

        // Si ya está vencida, sumamos desde hoy. Si no, sumamos desde su vencimiento actual.
        $fechaBase = ($clinica->fecha_vencimiento < date('Y-m-d')) ? date('Y-m-d') : $clinica->fecha_vencimiento;
        $nuevaFecha = date('Y-m-d', strtotime($fechaBase . " + $meses months"));

        $model->update($id, [
            'fecha_vencimiento' => $nuevaFecha,
            'estado' => 'activo'
        ]);

        return redirect()->back()->with('success', "Plan renovado hasta $nuevaFecha");
    }
}
