<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClinicSoft | <?= $this->renderSection('title') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
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
            margin: 0;
        }

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: #fff;
            border-right: 1px solid #e2e8f0;
        }

        .sidebar-header {
            padding: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            font-size: 1.25rem;
        }

        .nav-link {
            padding: 0.8rem 1.5rem;
            color: #64748b;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #f5f3ff;
            color: var(--primary);
            border-left: 3px solid var(--primary);
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        #content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        .card-table {
            border: none;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
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

        /* turnos */

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .card-calendar {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            background: #fff;
            padding: 1.5rem;
        }

        /* FullCalendar Custom */
        .fc {
            --fc-border-color: #f1f5f9;
            background: white;
            border: none;
        }

        .fc-toolbar-title {
            font-size: 1.2rem !important;
            font-weight: 700;
        }

        .fc-v-event {
            border-radius: 6px !important;
            border: none !important;
            padding: 2px 4px;
        }

        /* Botones de Estado */
        .btn-state-pill {
            border-radius: 50px;
            font-size: 0.8rem;
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-espera:hover {
            background: #fef3c7;
            color: #92400e;
            border-color: #f59e0b;
        }

        .btn-atender:hover {
            background: #e0f2fe;
            color: #075985;
            border-color: #0ea5e9;
        }

        .btn-finalizar:hover {
            background: #f1f5f9;
            color: #475569;
            border-color: #94a3b8;
        }

        /* Clases dinámicas de estado para el modal */
        .modal-estado-espera {
            border-top: 6px solid #f59e0b !important;
            background-color: #fef3c7 !important;
        }

        .modal-estado-consulta {
            border-top: 6px solid #0ea5e9 !important;
            background-color: #e0f2fe !important;
        }

        .modal-estado-finalizado {
            border-top: 6px solid #64748b !important;
            background-color: #f1f5f9 !important;
        }

        .modal-estado-pendiente {
            border-top: 6px solid #4ade80 !important;
            background-color: #dcfce7 !important;
        }

        /* Ajuste para que los iconos también cambien */
        .modal-estado-pendiente .bi-person {
            color: #4ade80 !important;
        }

        .modal-estado-espera .bi-person {
            color: #f59e0b !important;
        }

        .modal-estado-consulta .bi-person {
            color: #0ea5e9 !important;
        }

        .modal-estado-finalizado .bi-person {
            color: #64748b !important;
        }

        /* Estilo para resaltar el botón del estado actual */
        .btn-estado.active {
            border-width: 2px !important;
            font-weight: bold !important;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125) !important;
        }

        /* Colores específicos cuando están activos */
        .btn-outline-warning.active {
            background-color: #f59e0b !important;
            color: white !important;
            border-color: #d97706 !important;
        }

        .btn-outline-primary.active {
            background-color: #0ea5e9 !important;
            color: white !important;
            border-color: #0284c7 !important;
        }

        .btn-outline-secondary.active {
            background-color: #64748b !important;
            color: white !important;
            border-color: #475569 !important;
        }
    </style>
</head>

<body>

    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shield-plus me-2"></i> GesTurno
        </div>

        <div class="mt-4">
            <?php if (session()->get('rol') === 'superadmin'): ?>
                <div class="small text-uppercase px-4 mb-2 text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.1em;">Master Control</div>

                <a href="<?= base_url('superadmin') ?>" class="nav-link <?= (uri_string() == 'superadmin') ? 'active' : '' ?>">
                    <i class="bi bi-building-gear"></i> Gestión de Clínicas
                </a>

                <a href="<?= base_url('superadmin/reportes') ?>" class="nav-link <?= (uri_string() == 'superadmin/reportes') ? 'active' : '' ?>">
                    <i class="bi bi-graph-up-arrow"></i> Estadísticas Globales
                </a>
            <?php endif; ?>

            <?php if (session()->get('rol') !== 'superadmin'): ?>
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
            <?php endif; ?>

            <div style="position: absolute; bottom: 20px; width: 100%;">
                <hr class="mx-4 text-muted border-0 border-top opacity-25">
                <div class="px-4 mb-2 d-flex align-items-center">
                    <div class="bg-primary rounded-circle me-2" style="width: 10px; height: 10px;"></div>
                    <small class="text-muted"><?= session()->get('nombre') ?></small>
                </div>
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <main id="content">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>