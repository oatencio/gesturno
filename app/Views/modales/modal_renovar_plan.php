<div class="modal fade" id="modalRenovacion" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Renovar Suscripción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('superadmin/renovar-plan') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="clinica_id" id="renovar_clinica_id">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Clínica</label>
                        <input type="text" id="renovar_nombre_clinica" class="form-control-plaintext fw-bold text-primary p-0" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tiempo a renovar</label>
                        <select name="meses_a_sumar" class="form-select" required>
                            <option value="1">1 Mes</option>
                            <option value="3">3 Meses</option>
                            <option value="6">6 Meses</option>
                            <option value="12">1 Año (12 Meses)</option>
                        </select>
                        <div class="form-text">Se sumará a la fecha actual o de vencimiento.</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Confirmar Renovación</button>
                </div>
            </form>
        </div>
    </div>
</div>