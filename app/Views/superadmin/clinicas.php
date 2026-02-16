<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Panel de Clínicas</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaClinica">
            <i class="bi bi-plus-circle"></i> Nueva Clínica
        </button>
    </div>

    <table class="table table-hover bg-white shadow-sm rounded">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Plan</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($clinicas as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><strong><?= $c['nombre'] ?></strong></td>
                <td><span class="badge bg-info"><?= strtoupper($c['plan']) ?></span></td>
                <td><span class="badge bg-success"><?= $c['estado'] ?></span></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary">Editar</button>
                    <button class="btn btn-sm btn-outline-danger">Suspender</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalNuevaClinica" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= base_url('superadmin/guardarClinica') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header"><h5>Cargar Nueva Clínica</h5></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre de la Clínica</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email de contacto</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Plan Inicial</label>
                        <select name="plan" class="form-select">
                            <option value="basico">Básico</option>
                            <option value="pro">Pro</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Crear Clínica</button>
                </div>
            </div>
        </form>
    </div>
</div>