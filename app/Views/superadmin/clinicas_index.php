<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Clínicas<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Panel de Gestión de Superadmin</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaClinica">
        <i class="bi bi-plus-lg me-1"></i> Nueva Clínica
    </button>
</div>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <h6 class="text-uppercase small fw-bold">Clínicas Activas</h6>
                <h2 class="mb-0"><?= $stats['activas'] ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-danger text-white">
            <div class="card-body">
                <h6 class="text-uppercase small fw-bold">Vencidas</h6>
                <h2 class="mb-0"><?= $stats['vencidas'] ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body">
                <h6 class="text-uppercase small fw-bold">Vencen en 7 días</h6>
                <h2 class="mb-0"><?= $stats['proximas'] ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-dark text-white">
            <div class="card-body">
                <h6 class="text-uppercase small fw-bold">Total Usuarios</h6>
                <h2 class="mb-0"><?= $stats['usuarios'] ?></h2>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Clínica</th>
                    <th>Plan</th>
                    <th>Estado</th>
                    <th>Vencimiento</th>
                    <th class="text-end pe-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $hoy = date('Y-m-d');
                $proximo_vencer = date('Y-m-d', strtotime('+7 days'));

                foreach ($clinicas as $c):
                    // Lógica de Alerta de Vencimiento
                    $claseFecha = "";
                    $badgeAviso = "";

                    if ($c->fecha_vencimiento) {
                        if ($c->fecha_vencimiento < $hoy) {
                            $claseFecha = "text-danger fw-bold"; // Ya venció
                            $badgeAviso = '<span class="badge bg-danger ms-1">Vencido</span>';
                        } elseif ($c->fecha_vencimiento <= $proximo_vencer) {
                            $claseFecha = "text-warning fw-bold"; // Vence en menos de 7 días
                            $badgeAviso = '<span class="badge bg-warning text-dark ms-1">¡Por vencer!</span>';
                        }
                    }
                ?>
                    <tr>
                        <td class="ps-3">
                            <strong><?= $c->nombre ?></strong>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">
                                <?= strtoupper($c->plan ?? 'Básico') ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= ($c->estado == 'activo') ? 'bg-success' : 'bg-danger' ?>">
                                <?= ucfirst($c->estado ?? 'activo') ?>
                            </span>
                        </td>
                        <td class="<?= $claseFecha ?>">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= $c->fecha_vencimiento ? date('d/m/Y', strtotime($c->fecha_vencimiento)) : 'N/A' ?>
                            <?= $badgeAviso ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-secondary">Editar</button>
                                <button class="btn btn-sm btn-outline-danger">Suspender</button>
                                <button class="btn btn-sm btn-success rounded-pill ms-2"
                                    onclick="abrirModalRenovacion(<?= $c->id ?>, '<?= $c->nombre ?>')">
                                    <i class="bi bi-calendar-check me-1"></i>Renovar
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= view('modales/modal_nueva_clinica') ?>
<?= view('modales/modal_renovar_plan') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    function abrirModalRenovacion(id, nombre) {
        $('#renovar_clinica_id').val(id);
        $('#renovar_nombre_clinica').val(nombre);

        const modal = new bootstrap.Modal(document.getElementById('modalRenovacion'));
        modal.show();
    }
</script>

<?= $this->endSection() ?>