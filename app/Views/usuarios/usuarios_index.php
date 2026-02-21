<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Mi Equipo<?= $this->endSection() ?>

<?= $this->section('content') ?>
<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0 text-dark">Personal de la Clínica</h3>
        <p class="text-muted small mb-0">Gestiona los accesos de secretarios y asistentes</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUsuario">
        <i class="bi bi-person-plus-fill me-2"></i>Nuevo Usuario
    </button>
</header>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-circle p-3 me-3">
                    <i class="bi bi-people-fill fs-4"></i>
                </div>
                <div>
                    <h6 class="mb-0">Total Personal</h6>
                    <h4 class="fw-bold mb-0"><?= count($usuarios) ?></h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm card-table">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Nombre y Usuario</th>
                    <th>Rol / Permiso</th>
                    <th>Estado</th>
                    <th class="text-end pe-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            No hay otros usuarios registrados en esta clínica.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary-subtle text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= $u->nombre ?></div>
                                        <div class="text-muted small">@<?= $u->nombre ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($u->rol == 'admin'): ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">Administrador</span>
                                <?php else: ?>
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3"><?= ucfirst($u->rol) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" <?= !$u->deleted_at ? 'checked' : '' ?> disabled>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill me-1 btn-edit-usuario"
                                        data-id="<?= $u->id ?>"
                                        data-nombre="<?= $u->nombre ?>"
                                        data-email="<?= $u->email ?>"
                                        data-rol="<?= $u->rol ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php if ($u->id !== session()->get('id')): ?>
                                        <a href="<?= base_url('usuarios/eliminar/' . $u->id) ?>" class="btn btn-light btn-sm rounded-circle text-danger ms-1" onclick="return confirm('¿Dar de baja al usuario?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('modales/modal_usuario') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {

        // Lógica Editar
        $('.btn-edit-usuario').on('click', function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            const email = $(this).data('email');
            const rol = $(this).data('rol');

            // Cambiar título y acción del form
            $('#modalUsuario .modal-title').text('Editar Usuario');
            $('#modalUsuario form').attr('action', `<?= base_url('usuarios/editar') ?>/${id}`);

            // Llenar campos de texto
            $('#modalUsuario input[name="nombre"]').val(nombre);
            $('#modalUsuario input[name="email"]').val(email);
            $('#modalUsuario select[name="rol"]').val(rol);

            // --- GESTIÓN DE CONTRASEÑA ---
            const passwordInput = $('#modalUsuario input[name="password"]');
            passwordInput.val(''); // Limpiar campo
            passwordInput.prop('required', false); // NO es obligatoria al editar
            passwordInput.attr('placeholder', 'Dejar en blanco para mantener la actual');

            $('#modalUsuario').modal('show');
        });

    });
</script>
<?= $this->endSection() ?>