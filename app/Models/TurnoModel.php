<?php

namespace App\Models;

use CodeIgniter\Model;

class TurnoModel extends Model
{
    protected $table = 'turnos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['clinica_id', 'profesional_id', 'paciente_id', 'paciente_nombre', 'fecha_hora', 'estado', 'notificado'];

    public function getTurnosConProfesional($clinicaId)
    {
        return $this->select('turnos.*, profesionales.nombre as medico')
            ->join('profesionales', 'profesionales.id = turnos.profesional_id')
            ->where('turnos.clinica_id', $clinicaId)
            ->where('turnos.estado !=', 'cancelado')
            ->findAll();
    }

    public function estaDisponible($proId, $fechaHora, $turnoId = null)
    {
        // 1. Evitar fechas pasadas (con un margen de 1 minuto de gracia)
        if (strtotime($fechaHora) < (time() - 60)) {
            return 'pasado';
        }

        // 2. Verificar disponibilidad
        $builder = $this->where([
            'profesional_id' => $proId,
            'fecha_hora'     => $fechaHora,
            'estado !='      => 'cancelado'
        ]);

        // Ignoramos el turno actual si estamos editando
        if ($turnoId) {
            $builder->where('id !=', $turnoId);
        }

        return ($builder->countAllResults() === 0) ? 'disponible' : 'ocupado';
    }
}
