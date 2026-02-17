<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Usuarios del Sistema</h3>
        <p class="text-muted small mb-0">Administración de accesos de todas las clínicas</p>
    </div>
</header>

<div class="card card-table">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Clínica</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold"><?= $u->nombre ?></div>
                        <div class="text-muted small">@<?= $u->nombre ?></div>
                    </td>
                    <td>
                        <span class="text-primary fw-medium">
                            <?= $u->clinica_nombre ?: '<span class="text-danger">SUPERADMIN</span>' ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge <?= $u->rol == 'admin' ? 'bg-info' : 'bg-dark' ?> rounded-pill">
                            <?= strtoupper($u->rol) ?>
                        </span>
                    </td>
                    <td>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" <?= !$u->deleted_at ? 'checked' : '' ?> disabled>
                        </div>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-light rounded-pill px-3 border" 
                                onclick="resetPassword(<?= $u->id ?>, '<?= $u->nombre ?>')">
                            <i class="bi bi-key me-1"></i> Reset Pass
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>