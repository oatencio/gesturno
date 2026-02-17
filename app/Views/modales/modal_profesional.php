<div class="modal fade" id="modalProfesional" tabindex="-1">
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