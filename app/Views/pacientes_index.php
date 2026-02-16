<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClinicSoft | Gestión de Pacientes</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #4f46e5;
            --sidebar-width: 260px;
            --bg-body: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
            display: flex;
            align-items: center;
        }

        .nav-link {
            padding: 0.8rem 1.5rem;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            transition: 0.2s;
            text-decoration: none;
        }

        .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
            background: #f5f3ff;
            border-left-color: var(--primary);
        }

        /* Contenido Principal */
        #content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        .custom-tabs .nav-link {
            border: none;
            padding: 0.5rem 1rem;
            margin-right: 1rem;
            color: #64748b;
            border-bottom: 2px solid transparent;
        }

        .custom-tabs .nav-link.active {
            background: transparent;
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            font-weight: 600;
        }

        .card-table {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            background: #fff;
            overflow: hidden;
        }

        .table thead th {
            background: #f8fafc;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            color: #64748b;
            border: none;
            padding: 1rem 1.5rem;
        }

        .avatar-patient {
            width: 38px;
            height: 38px;
            background: #f1f5f9;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .search-input {
            border-radius: 20px;
            padding-left: 40px;
            background-color: #fff;
            border: 1px solid #e2e8f0;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shield-plus me-2"></i> ClinicSoft
        </div>
        <div class="mt-4">
            <div class="small text-uppercase px-4 mb-2 text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.1em;">Principal</div>

            <a href="<?= base_url('turnos') ?>" class="nav-link <?= (uri_string() == 'turnos') ? 'active' : '' ?>">
                <i class="bi bi-calendar-event"></i> Agenda de Turnos
            </a>

            <div class="small text-uppercase px-4 mt-4 mb-2 text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.1em;">Administración</div>

            <a href="<?= base_url('profesionales') ?>" class="nav-link <?= (strpos(uri_string(), 'profesionales') !== false) ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Gestión de Médicos
            </a>

            <a href="<?= base_url('pacientes') ?>" class="nav-link <?= (strpos(uri_string(), 'pacientes') !== false) ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i> Gestión de Pacientes
            </a>

            <div style="position: absolute; bottom: 20px; width: 100%;">
                <hr class="mx-4 text-muted">
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <main id="content">
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
    </main>

    <div class="modal fade" id="modalPaciente" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= base_url('pacientes/guardar') ?>" method="POST" class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold m-0">Ficha de Paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">DNI / IDENTIFICACIÓN</label>
                        <input type="text" name="dni" class="form-control rounded-3" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">NOMBRE</label>
                            <input type="text" name="nombre" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">APELLIDO</label>
                            <input type="text" name="apellido" class="form-control rounded-3" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">OBRA SOCIAL / COBERTURA</label>
                        <input type="text" name="obra_social" class="form-control rounded-3" placeholder="Ej: OSDE, PAMI, Particular">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">TELÉFONO</label>
                            <input type="text" name="telefono" class="form-control rounded-3">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">EMAIL</label>
                            <input type="email" name="email" class="form-control rounded-3">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">Guardar Paciente</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
</body>

</html>