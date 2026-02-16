<?php

namespace App\Models;

use CodeIgniter\Model;

class ClinicaModel extends Model
{
    protected $table            = 'clinicas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Cambiar a true si agregas columna deleted_at

    // Campos que permitimos que se carguen desde el formulario del Superadmin
    protected $allowedFields    = [
        'nombre', 
        'slug', 
        'email_contacto', 
        'plan', 
        'estado', 
        'fecha_vencimiento'
    ];

    // Fechas automáticas
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtener solo clínicas activas para procesos del sistema
     */
    public function getActivas()
    {
        return $this->where('estado', 'activo')->findAll();
    }

    /**
     * Verificar si una clínica específica está al día
     */
    public function estaVigente($id)
    {
        $clinica = $this->find($id);
        if (!$clinica) return false;
        
        // Si no tiene fecha de vencimiento, asumimos que es permanente o prueba
        if (empty($clinica['fecha_vencimiento'])) return true;

        return strtotime((string)$clinica['fecha_vencimiento']) >= time();
    }
}