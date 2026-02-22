<?php

namespace App\Controllers;

use App\Models\TurnoModel;
use CodeIgniter\API\ResponseTrait;

class Turnos extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // 1. Verificamos sesión
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $turnoModel = new \App\Models\TurnoModel();
        $profModel = new \App\Models\ProfesionalModel();
        $pacModel = new \App\Models\PacienteModel();

        $clinicaId = session()->get('clinica_id');

        $data = [
            'turnos' => $turnoModel->getTurnosConProfesional($clinicaId),
            'profesionales' => $profModel->where('clinica_id', $clinicaId)->findAll(),
            'pacientes' => $pacModel->where('clinica_id', $clinicaId)->findAll(),
        ];

        return view('turnos_index', $data);
    }

    public function agendar()
    {
        $clinicaId = session()->get('clinica_id');
        if (!$clinicaId) return $this->response->setJSON(['status' => 'error', 'msg' => 'Sesión expirada']);

        $model = new TurnoModel();

        // 1. Construimos la fecha y obtenemos IDs
        $fecha_full = $this->request->getPost('fecha') . ' ' . $this->request->getPost('hora') . ':00';
        $pro_id     = $this->request->getPost('profesional_id');
        $pac_id     = $this->request->getPost('paciente_id'); // <-- Ahora recibimos el ID del paciente

        // 2. Validación de disponibilidad
        $estado = $model->estaDisponible($pro_id, $fecha_full);

        if ($estado === 'disponible') {
            // 3. Guardado con la nueva estructura
            $model->save([
                'clinica_id'     => $clinicaId,
                'profesional_id' => $pro_id,
                'paciente_id'    => $pac_id, // <-- Guardamos la relación numérica
                'fecha_hora'     => $fecha_full,
                'estado'         => 'pendiente' // Es buena práctica definir un estado inicial
            ]);

            return $this->response->setJSON(['status' => 'success', 'msg' => '¡Turno Agendado con éxito!']);
        }

        // 4. Manejo de errores de disponibilidad
        $mensaje = ($estado === 'pasado')
            ? 'No puedes agendar en una fecha pasada.'
            : 'Este horario ya está ocupado por otro profesional o paciente.';

        return $this->response->setJSON(['status' => 'error', 'msg' => $mensaje])->setStatusCode(400);
    }

    public function listarEventos()
    {
        $clinicaId = session()->get('clinica_id');
        $profesionalId = $this->request->getGet('profesional_id');

        if (!$clinicaId) {
            return $this->response->setJSON([]);
        }

        $model = new TurnoModel();

        // 1. Agregamos el JOIN con la tabla pacientes para obtener nombre y apellido
        $builder = $model->select('turnos.*, profesionales.nombre as medico, pacientes.nombre as p_nombre, pacientes.apellido as p_apellido, pacientes.dni as p_dni, pacientes.obra_social as p_obra_social')
            ->join('profesionales', 'profesionales.id = turnos.profesional_id')
            ->join('pacientes', 'pacientes.id = turnos.paciente_id') // <--- JOIN VITAL
            ->where([
                'turnos.clinica_id' => $clinicaId,
                'turnos.estado !='  => 'cancelado'
            ]);

        // 2. Filtro por médico si se selecciona uno
        if (!empty($profesionalId)) {
            $builder->where('turnos.profesional_id', $profesionalId);
        }

        $turnos = $builder->findAll();
        $eventos = [];

        foreach ($turnos as $t) {

            if ($t['estado'] == 'pendiente') $colorFinal = '#83dd67';
            if ($t['estado'] == 'espera') $colorFinal = '#ffc107';
            if ($t['estado'] == 'consulta') $colorFinal = '#17a2b8';
            if ($t['estado'] == 'finalizado') $colorFinal = '#6c757d';

            // 3. Construimos el título con los datos del JOIN de pacientes
            $nombreCompletoPaciente = $t['p_apellido'] . ", " . $t['p_nombre'];

            $eventos[] = [
                'id'    => $t['id'],
                'title' => $nombreCompletoPaciente,
                'start' => $t['fecha_hora'],
                'end'   => date('Y-m-d H:i:s', strtotime($t['fecha_hora'] . ' +30 minutes')),
                'color' => $colorFinal,
                'extendedProps' => [
                    'medico' => $t['medico'],
                    'estado' => $t['estado'],
                    'paciente' => $nombreCompletoPaciente, // Lo pasamos limpio al JS por si lo necesitas
                    'dni'         => $t['p_dni'],         // Asegúrate de pedir p_dni en el select
                    'obra_social' => $t['p_obra_social']
                ]
            ];
        }

        return $this->response->setJSON($eventos);
    }

    public function actualizarEvento()
    {
        $clinicaId = session()->get('clinica_id'); // <--- Esto es clave
        if (!$clinicaId) return $this->response->setJSON([]);

        $model = new TurnoModel();
        $id = $this->request->getPost('id');
        $nuevaFecha = $this->request->getPost('fecha_hora');

        $turnoActual = $model->find($id);
        // PASAMOS EL ID para que no se autobloquee
        $estado = $model->estaDisponible($turnoActual['profesional_id'], $nuevaFecha, $id);

        if ($estado === 'disponible') {
            $model->update($id, ['fecha_hora' => $nuevaFecha]);
            return $this->response->setJSON(['status' => 'success', 'msg' => 'Turno movido']);
        }

        $mensaje = ($estado === 'pasado') ? 'Error: No se puede mover al pasado.' : 'Error: Horario ocupado.';
        return $this->response->setJSON(['status' => 'error', 'msg' => $mensaje], 400);
    }

    public function eliminar($id)
    {
        $clinicaId = session()->get('clinica_id'); // <--- Esto es clave
        if (!$clinicaId) return $this->response->setJSON([]);

        $model = new TurnoModel();

        // Verificamos que el turno pertenezca a la clínica de la sesión (Seguridad)
        $turno = $model->where(['id' => $id, 'clinica_id' => session()->get('clinica_id')])->first();

        if ($turno) {
            $model->update($id, ['estado' => 'cancelado']);
            return $this->response->setJSON(['status' => 'success', 'msg' => 'Turno cancelado correctamente']);
        }

        return $this->response->setJSON(['status' => 'error', 'msg' => 'No tienes permiso para eliminar este turno'], 403);
    }

    public function cambiarEstado()
    {
        $clinicaId = session()->get('clinica_id'); // <--- Esto es clave
        if (!$clinicaId) return $this->response->setJSON([]);

        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');

        $model = new TurnoModel();
        $model->update($id, ['estado' => $estado]);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function historialPaciente($pacienteId)
    {
        $clinicaId = session()->get('clinica_id'); // <--- Esto es clave
        if (!$clinicaId) return $this->response->setJSON([]);

        $model = new \App\Models\TurnoModel();
        $historial = $model->select('turnos.*, profesionales.nombre as medico')
            ->join('profesionales', 'profesionales.id = turnos.profesional_id')
            ->where('paciente_id', $pacienteId)
            ->orderBy('fecha_hora', 'DESC')
            ->findAll();

        return $this->response->setJSON($historial);
    }

    public function vencido()
    {
        // Es buena idea pasarle los datos de la clínica para que sepan qué venció
        return view('errors/suscripcion_vencida');
    }

    public function obtenerPendientesNotificar()
    {
        $clinicaId = session()->get('clinica_id');
        $model = new \App\Models\TurnoModel();

        $mañana = date('Y-m-d', strtotime('+1 day'));

        $pendientes = $model->select('turnos.*, pacientes.nombre as p_nombre, pacientes.telefono as p_tel, profesionales.nombre as prof_nombre')
            ->join('pacientes', 'pacientes.id = turnos.paciente_id')
            ->join('profesionales', 'profesionales.id = turnos.profesional_id')
            ->where([
                'turnos.clinica_id' => $clinicaId,
                'DATE(turnos.fecha_hora)' => $mañana,
                'turnos.notificado' => 0,
                'turnos.estado !=' => 'cancelado'
            ])->findAll();

        return $this->response->setJSON($pendientes);
    }

    public function marcarComoNotificado($id)
    {
        $model = new \App\Models\TurnoModel();
        $model->update($id, ['notificado' => 1]);
        return $this->response->setJSON(['status' => 'success']);
    }
}
