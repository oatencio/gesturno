<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClinicSoft - Gestión Global</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body { display: flex; min-height: 100vh; background-color: #f8f9fa; }
        #sidebar { width: 250px; background: #fff; border-right: 1px solid #dee2e6; transition: all 0.3s; }
        .nav-link { color: #333; padding: 10px 20px; display: block; text-decoration: none; }
        .nav-link:hover, .nav-link.active { background: #e9ecef; color: #0d6efd; }
        .content { flex: 1; padding: 30px; }
        .card { border: none; shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    </style>
</head>
<body>

    <nav id="sidebar">
        <div class="sidebar-header p-4 fw-bold text-primary">
            <i class="bi bi-shield-plus me-2"></i> ClinicSoft
        </div>
        
        <div class="mt-2">
            <div class="small text-uppercase px-4 mb-2 text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.1em;">Master Control</div>
            <a href="<?= base_url('superadmin') ?>" class="nav-link active">
                <i class="bi bi-building-gear me-2"></i> Gestión de Clínicas
            </a>
            
            <div style="position: absolute; bottom: 20px; width: 100%;">
                <hr class="mx-4 text-muted opacity-25">
                <div class="px-4 mb-2">
                    <small class="text-muted"><i class="bi bi-person-circle me-1"></i> <?= session()->get('nombre') ?></small>
                </div>
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left me-2"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <main class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Panel de Gestión de Clínicas</h2>
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
                        <?php foreach($clinicas as $c): ?>
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
    </main>

    <div class="modal fade" id="modalNuevaClinica" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?= base_url('superadmin/guardarClinica') ?>" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Nueva Clínica Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre Comercial</label>
                            <input type="text" name="nombre" class="form-control" placeholder="Ej: Centro Médico Integral" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email del Administrador</label>
                            <input type="email" name="email" class="form-control" placeholder="admin@clinica.com" required>
                            <div class="form-text">Se creará un usuario admin automáticamente con este correo.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Plan de Suscripción</label>
                            <select name="plan" class="form-select">
                                <option value="basico">Plan Básico</option>
                                <option value="pro">Plan Pro</option>
                                <option value="premium">Plan Premium</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Clínica y Usuario</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>