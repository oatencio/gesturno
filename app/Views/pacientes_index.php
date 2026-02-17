<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pacientes<?= $this->endSection() ?>

<?= $this->section('content') ?>

<header class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">Fichero de Pacientes</h3>
        <p class="text-muted small mb-0">Administra la base de datos de tu clínica</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPaciente">
        <i class="bi bi-person-plus me-2"></i>Nuevo Paciente
    </button>
</header>

<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <ul class="nav nav-tabs custom-tabs border-0">
            <li class="nav-item">
                <a class="nav-link <?= !isset($view) ? 'active' : '' ?>" href="<?= base_url('pacientes') ?>">Activos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($view) && $view == 'inactivos') ? 'active' : '' ?>" href="<?= base_url('pacientes/inactivos') ?>">Inactivos</a>
            </li>
        </ul>
    </div>
    <div class="col-md-6">
        <div class="position-relative">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="busquedaPaciente" class="form-control search-input shadow-sm" placeholder="Buscar por DNI, Nombre o Apellido...">
        </div>
    </div>
</div>

<div class="card card-table">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tablaPacientes">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>DNI / Identificación</th>
                    <th>Obra Social</th>
                    <th>Contacto</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pacientes)): ?>
                    <?php foreach ($pacientes as $p): ?>
                        <tr class="paciente-row">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-patient me-3"><?= strtoupper(substr($p->apellido, 0, 1)) ?></div>
                                    <div>
                                        <div class="fw-semibold text-nombre"><?= $p->apellido ?>, <?= $p->nombre ?></div>
                                        <div class="text-muted small">Registro: #<?= $p->id ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="fw-medium text-dni"><?= $p->dni ?></span></td>
                            <td><span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><?= $p->obra_social ?: 'Particular' ?></span></td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-telephone text-muted me-1"></i> <?= $p->telefono ?: 'N/A' ?><br>
                                    <i class="bi bi-envelope text-muted me-1"></i> <?= $p->email ?: '-' ?>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <?php if (isset($view) && $view == 'inactivos'): ?>
                                    <a href="<?= base_url('pacientes/restaurar/' . $p->id) ?>" class="btn btn-outline-success btn-sm rounded-pill px-3">Reactivar</a>
                                <?php else: ?>
                                    <button class="btn btn-light btn-sm rounded-circle btn-edit-paciente"
                                        data-id="<?= $p->id ?>" data-dni="<?= $p->dni ?>" data-nombre="<?= $p->nombre ?>"
                                        data-apellido="<?= $p->apellido ?>" data-tel="<?= $p->telefono ?>"
                                        data-email="<?= $p->email ?>" data-os="<?= $p->obra_social ?>">
                                        <i class="bi bi-pencil text-primary"></i>
                                    </button>
                                    <a href="<?= base_url('pacientes/eliminar/' . $p->id) ?>" class="btn btn-light btn-sm rounded-circle text-danger ms-1" onclick="return confirm('¿Dar de baja al paciente?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No hay registros para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('modales/modal_paciente') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {
        // Buscador en tiempo real
        $("#busquedaPaciente").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".paciente-row").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Lógica Editar
        $('.btn-edit-paciente').on('click', function() {
            const id = $(this).data('id');
            const modal = $('#modalPaciente');
            modal.find('h5').text('Editar Ficha de Paciente');
            modal.find('form').attr('action', '<?= base_url('pacientes/editar/') ?>' + id);
            modal.find('input[name="dni"]').val($(this).data('dni'));
            modal.find('input[name="nombre"]').val($(this).data('nombre'));
            modal.find('input[name="apellido"]').val($(this).data('apellido'));
            modal.find('input[name="obra_social"]').val($(this).data('os'));
            modal.find('input[name="telefono"]').val($(this).data('tel'));
            modal.find('input[name="email"]').val($(this).data('email'));
            modal.modal('show');
        });

        // Reset modal
        $('#modalPaciente').on('hidden.bs.modal', function() {
            $(this).find('h5').text('Ficha de Paciente');
            $(this).find('form').attr('action', '<?= base_url('pacientes/guardar') ?>');
            $(this).find('form')[0].reset();
        });

        <?php if (session()->getFlashdata('success')): ?>
            toastr.success("<?= session()->getFlashdata('success') ?>");
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>