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

        return view('superadmin/clinicas', $data);
    }

    public function guardarClinica()
    {
        $clinicaModel = new \App\Models\ClinicaModel();
        $usuarioModel = new \App\Models\UsuarioModel(); // Asegúrate de tener este modelo

        // 1. Datos de la Clínica
        $slug = url_title($this->request->getPost('nombre'), '-', true);
        $datosClinica = [
            'nombre'         => $this->request->getPost('nombre'),
            'slug'           => $slug,
            'email_contacto' => $this->request->getPost('email'),
            'plan'           => $this->request->getPost('plan'),
            'estado'         => 'activo'
        ];

        $db = \Config\Database::connect();
        $db->transStart(); // Iniciamos una transacción para seguridad

        // 2. Insertar Clínica
        $clinicaId = $clinicaModel->insert($datosClinica);

        // 3. Crear el Usuario Administrador para esa Clínica
        $datosUsuario = [
            'clinica_id' => $clinicaId,
            'nombre'     => 'Admin ' . $this->request->getPost('nombre'),
            'email'      => $this->request->getPost('email'),
            'password'   => password_hash('123456', PASSWORD_DEFAULT), // Clave temporal
            'rol'        => 'admin_clinica'
        ];

        $usuarioModel->insert($datosUsuario);

        $db->transComplete(); // Finaliza la transacción

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Error al crear la clínica y su administrador.');
        }

        return redirect()->back()->with('success', 'Clínica y Administrador creados con éxito. Clave temporal: 123456');
    }
}
