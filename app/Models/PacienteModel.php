<?php

namespace App\Models;
use CodeIgniter\Model;

class PacienteModel extends Model
{
    protected $table            = 'pacientes';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true; // Activamos borrado lógico
    protected $allowedFields    = ['clinica_id', 'dni', 'nombre', 'apellido', 'telefono', 'email', 'obra_social', 'deleted_at'];
    protected $returnType       = 'object';
    protected $deletedField     = 'deleted_at';
}