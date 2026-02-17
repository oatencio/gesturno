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
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control" placeholder="Ej: Calle Principal 123" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Ej: 555-1234" required>
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