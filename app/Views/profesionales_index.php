<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClinicSoft | Gestión de Profesionales</title>

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

        /* Pestañas Personalizadas */
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

        .avatar-circle {
            width: 38px;
            height: 38px;
            background: #e0e7ff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .badge-especialidad {
            background-color: #f5f3ff;
            color: var(--primary);
            border: 1px solid #ddd6fe;
            font-weight: 500;
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
                <h3 class="fw-bold mb-0">Cuerpo Médico</h3>
                <p class="text-muted small mb-0">Administra los profesionales de tu clínica</p>
            </div>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalMedico">
                <i class="bi bi-person-plus me-2"></i>Añadir Médico
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
                                        <span class="badge rounded-pill badge-especialidad px-3 py-2">
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
    </main>

    <div class="modal fade" id="modalMedico" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= base_url('profesionales/guardar') ?>" method="POST" class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold m-0">Nuevo Profesional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">NOMBRE COMPLETO</label>
                        <input type="text" name="nombre" class="form-control rounded-3" placeholder="Dr/a. Nombre Apellido" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">ESPECIALIDAD</label>
                        <input type="text" name="especialidad" class="form-control rounded-3" placeholder="Ej: Pediatría" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">EMAIL</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TELÉFONO</label>
                            <input type="text" name="telefono" class="form-control rounded-3" placeholder="+54...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">Guardar Registro</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
                const modal = $('#modalMedico');

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
            $('#modalMedico').on('hidden.bs.modal', function() {
                $(this).find('h5').text('Nuevo Profesional');
                $(this).find('form').attr('action', '<?= base_url('profesionales/guardar') ?>');
                $(this).find('form')[0].reset();
            });
        });
    </script>
</body>

</html>