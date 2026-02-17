<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Profesionales<?= $this->endSection() ?>

<?= $this->section('content') ?>

<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Profesionales</h3>
        <p class="text-muted small mb-0">Administra los profesionales de tu clínica</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalProfesional">
        <i class="bi bi-person-plus me-2"></i>Nuevo Profesional
    </button>
</header>

<ul class="nav nav-tabs custom-tabs mb-3 border-0">
    <li class="nav-item">
        <a class="nav-link <?= !isset($view) ? 'active' : '' ?>" href="<?= base_url('profesionales') ?>">
            Activos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= (isset($view) && $view == 'inactivos') ? 'active' : '' ?>" href="<?= base_url('profesionales/inactivos') ?>">
            Inactivos (Bajas)
        </a>
    </li>
</ul>

<div class="card card-table">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nombre del Profesional</th>
                    <th>Especialidad</th>
                    <th>Información de Contacto</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($profesionales)): ?>
                    <?php foreach ($profesionales as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3"><?= strtoupper(substr($p->nombre, 0, 1)) ?></div>
                                    <div>
                                        <div class="fw-semibold"><?= $p->nombre ?></div>
                                        <div class="text-muted small">ID: #<?= $p->id ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                                    <?= $p->especialidad ?>
                                </span>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <i class="bi bi-envelope me-1"></i> <?= $p->email ?? 'No registrado' ?><br>
                                    <i class="bi bi-telephone me-1"></i> <?= $p->telefono ?? 'Sin teléfono' ?>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <?php if (isset($view) && $view == 'inactivos'): ?>
                                    <a href="<?= base_url('profesionales/restaurar/' . $p->id) ?>"
                                        class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reactivar
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm me-1 btn-edit"
                                        data-id="<?= $p->id ?>"
                                        data-nombre="<?= $p->nombre ?>"
                                        data-esp="<?= $p->especialidad ?>"
                                        data-email="<?= $p->email ?? '' ?>"
                                        data-tel="<?= $p->telefono ?? '' ?>">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </button>

                                    <a href="<?= base_url('profesionales/eliminar/' . $p->id) ?>"
                                        class="btn btn-light btn-sm rounded-circle shadow-sm text-danger btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            No hay profesionales en esta lista.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('modales/modal_profesional') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {
        // Notificaciones de CodeIgniter (Flashdata)
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success("<?= session()->getFlashdata('success') ?>");
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error("<?= session()->getFlashdata('error') ?>");
        <?php endif; ?>

        // Lógica Editar
        $('.btn-edit').on('click', function() {
            const id = $(this).data('id');
            const modal = $('#modalProfesional');

            modal.find('h5').text('Editar Profesional');
            modal.find('form').attr('action', '<?= base_url('profesionales/editar/') ?>' + id);

            modal.find('input[name="nombre"]').val($(this).data('nombre'));
            modal.find('input[name="especialidad"]').val($(this).data('esp'));
            modal.find('input[name="email"]').val($(this).data('email'));
            modal.find('input[name="telefono"]').val($(this).data('tel'));

            modal.modal('show');
        });

        // Confirmación de eliminación con mensaje claro
        $('.btn-delete').on('click', function(e) {
            if (!confirm('¿Estás seguro de dar de baja a este profesional? Los datos históricos se mantendrán pero no se podrán agendar nuevos turnos con él.')) {
                e.preventDefault();
            }
        });

        // Limpiar el modal cuando se cierra
        $('#modalProfesional').on('hidden.bs.modal', function() {
            $(this).find('h5').text('Nuevo Profesional');
            $(this).find('form').attr('action', '<?= base_url('profesionales/guardar') ?>');
            $(this).find('form')[0].reset();
        });
    });
</script>
<?= $this->endSection() ?>