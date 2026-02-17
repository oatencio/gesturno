<div class="modal fade" id="modalNuevo" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formTurno" class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="fw-bold m-0">Nuevo Turno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">PACIENTE</label>
                        <div class="input-group">
                            <select name="paciente_id" id="selectPaciente" class="form-select select2-simple" required>
                                <option value="">Seleccionar o buscar paciente...</option>
                                <?php foreach ($pacientes as $pac): ?>
                                    <option value="<?= $pac->id ?>">
                                        <?= $pac->dni ?> - <?= $pac->apellido ?>, <?= $pac->nombre ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalPacienteRapido">
                                <i class="bi bi-person-plus"></i>
                            </button>
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem;">Busca por DNI o Apellido</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">MÉDICO</label>
                        <select name="profesional_id" class="form-select rounded-3">
                            <?php foreach ($profesionales as $p): ?>
                                <option value="<?= $p->id ?>"><?= $p->nombre ?> (<?= $p->especialidad ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <input type="date" name="fecha" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <input type="time" name="hora" class="form-control rounded-3" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill">Confirmar Cita</button>
                </div>
            </form>
        </div>
    </div>