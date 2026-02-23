<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InstallGesturno extends Migration
{
    public function up()
    {
        // --- TABLA: CLINICAS ---
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nombre'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'direccion'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'telefono'          => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'email'             => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'plan'              => ['type' => 'ENUM', 'constraint' => ['basico', 'pro', 'premium'], 'default' => 'basico'],
            'estado'            => ['type' => 'ENUM', 'constraint' => ['activo', 'suspendido', 'pendiente'], 'default' => 'activo'],
            'fecha_vencimiento' => ['type' => 'DATE', 'null' => true],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('clinicas');

        // --- TABLA: PACIENTES ---
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'clinica_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dni'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'nombre'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'apellido'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'telefono'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'obra_social' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('clinica_id', 'clinicas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pacientes');

        // --- TABLA: PROFESIONALES ---
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'clinica_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nombre'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'especialidad' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'telefono'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('clinica_id', 'clinicas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('profesionales');

        // --- TABLA: TURNOS ---
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'clinica_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'profesional_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'paciente_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'paciente_nombre' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'fecha_hora'      => ['type' => 'DATETIME', 'null' => true],
            'estado'          => ['type' => 'ENUM', 'constraint' => ['pendiente', 'espera', 'consulta', 'finalizado', 'cancelado'], 'default' => 'pendiente'],
            'notificado'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('clinica_id', 'clinicas', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('profesional_id', 'profesionales', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('turnos');

        // --- TABLA: USUARIOS ---
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'clinica_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'rol'         => ['type' => 'ENUM', 'constraint' => ['superadmin', 'admin_clinica', 'profesional', 'recepcion'], 'default' => 'recepcion'],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'nombre'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addForeignKey('clinica_id', 'clinicas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
        $this->forge->dropTable('turnos');
        $this->forge->dropTable('profesionales');
        $this->forge->dropTable('pacientes');
        $this->forge->dropTable('clinicas');
    }
}
