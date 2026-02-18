<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GesTurno | <?= $this->renderSection('title') ?></title>

    <!-- Fuentes y estilos externos -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #0ea5e9;
            --success: #4ade80;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-body: #f8fafc;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            margin: 0;
            line-height: 1.6;
        }

        /* ================= Sidebar ================= */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }

        .nav-link {
            padding: 0.75rem 1.5rem;
            color: #64748b;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            border-left: 3px solid var(--primary);
            transform: translateX(2px);
        }

        /* Sidebar footer */
        #sidebar div[style*="position: absolute"] {
            bottom: 20px;
            width: 100%;
        }

        /* ================= Main Content ================= */
        #content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* ================= Cards ================= */
        .card-table,
        .card-calendar {
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 1.75rem;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-table:hover,
        .card-calendar:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }

        /* ================= Avatares ================= */
        .avatar-patient {
            width: 38px;
            height: 38px;
            background: #f1f5f9;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* ================= Buscador ================= */
        .search-input {
            border-radius: 1.5rem;
            padding: 0.5rem 1.5rem 0.5rem 2.5rem;
            background-color: #fff;
            border: 1px solid #cbd5e1;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .top-bar-turnos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        /* ================= FullCalendar ================= */

        .fc {
            --fc-border-color: #f1f5f9;
            background: white;
            border: none;
        }

        .fc-toolbar-title {
            font-size: 1.3rem !important;
            font-weight: 700;
        }

        .fc .fc-event {
            border-radius: 0.5rem !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 4px 8px;
            border: none !important;
        }

        /* ================= Botones de estado ================= */
        .btn-state-pill {
            border-radius: 9999px;
            font-size: 0.85rem;
            padding: 0.5rem 1.2rem;
            border: none;
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .btn-state-pill:hover {
            transform: translateY(-1px);
        }

        .btn-espera:hover {
            background: #fef3c7;
            color: #92400e;
            border-color: var(--warning);
        }

        .btn-atender:hover {
            background: #e0f2fe;
            color: #075985;
            border-color: var(--secondary);
        }

        .btn-finalizar:hover {
            background: #f1f5f9;
            color: #475569;
            border-color: #94a3b8;
        }

        /* ================= Modal estados ================= */
        .modal-estado-espera {
            border-top: 6px solid var(--warning);
            background-color: #fef3c7 !important;
        }

        .modal-estado-consulta {
            border-top: 6px solid var(--secondary);
            background-color: #e0f2fe !important;
        }

        .modal-estado-finalizado {
            border-top: 6px solid #64748b;
            background-color: #f1f5f9 !important;
        }

        .modal-estado-pendiente {
            border-top: 6px solid var(--success);
            background-color: #dcfce7 !important;
        }

        .modal-estado-pendiente .bi-person {
            color: var(--success) !important;
        }

        .modal-estado-espera .bi-person {
            color: var(--warning) !important;
        }

        .modal-estado-consulta .bi-person {
            color: var(--secondary) !important;
        }

        .modal-estado-finalizado .bi-person {
            color: #64748b !important;
        }

        /* ================= Botón activo ================= */
        .btn-estado.active {
            border-width: 2px !important;
            font-weight: bold !important;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125) !important;
        }

        .btn-outline-warning.active {
            background-color: var(--warning) !important;
            color: white !important;
            border-color: #d97706 !important;
        }

        .btn-outline-primary.active {
            background-color: var(--secondary) !important;
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
                <a href="<?= base_url('superadmin/usuarios') ?>" class="nav-link <?= (strpos(uri_string(), 'usuarios') !== false) ? 'active' : '' ?>">
                    <i class="bi bi-shield-lock"></i> Usuarios Globales
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
                <?php if (session()->get('rol') == 'admin_clinica'): ?>
                    <div class="menu-label text-muted small fw-bold mt-4 mb-2 ps-3 text-uppercase" style="font-size: 0.7rem;">Configuración Clínica</div>

                    <a href="<?= base_url('usuarios') ?>" class="nav-link <?= (url_is('usuarios*')) ? 'active' : '' ?>">
                        <i class="bi bi-person-badge me-2"></i> Mi Equipo
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-gear me-2"></i> Datos de Clínica
                    </a>
                <?php endif; ?>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>