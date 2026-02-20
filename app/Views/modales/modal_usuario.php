<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Nuevo integrante del equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('usuarios/guardar') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Dra. Elena Smith" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">E-mail</label>
                            <input type="text" name="email" class="form-control" placeholder="elena.smith@ejemplo.com" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Rol</label>
                            <select name="rol" class="form-select" required>
                                <option value="recepcion">Recepción</option>
                                <option value="profesional">Profesional</option>
                                <option value="admin_clinica">Administrador de Clínica</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                        <div class="form-text">Mínimo 8 caracteres.</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>