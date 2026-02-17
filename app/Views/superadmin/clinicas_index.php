<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Clínicas<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Panel de Gestión de Superadmin</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaClinica">
        <i class="bi bi-plus-lg me-1"></i> Nueva Clínica
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Nombre de la Clínica</th>
                    <th>Plan</th>
                    <th>Estado</th>
                    <th class="text-end pe-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clinicas as $c): ?>
                    <tr>
                        <td class="ps-4"><?= $c['id'] ?></td>
                        <td><strong><?= $c['nombre'] ?></strong></td>
                        <td><span class="badge bg-info text-dark"><?= strtoupper($c['plan'] ?? 'Básico') ?></span></td>
                        <td>
                            <span class="badge <?= ($c['estado'] == 'activo') ? 'bg-success' : 'bg-danger' ?>">
                                <?= ucfirst($c['estado'] ?? 'activo') ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-secondary me-1">Editar</button>
                            <button class="btn btn-sm btn-outline-danger">Suspender</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('modales/modal_nueva_clinica') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<!-- Aquí puedes agregar scripts específicos para esta vista, como manejo de eventos para los botones de acción -->

<?= $this->endSection() ?>
