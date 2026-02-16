<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfesionalModel extends Model
{
    protected $table            = 'profesionales';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['clinica_id', 'nombre', 'especialidad', 'email', 'telefono', 'deleted_at'];
    protected $returnType       = 'object'; // Para usar $p->nombre en la vista
    protected $useSoftDeletes = true; // CodeIgniter ahora filtrará los borrados automáticamente
    protected $deletedField   = 'deleted_at';
}
