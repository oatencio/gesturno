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