<div class="modal fade" id="modalDetalles" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">
                    <div class="text-center">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-person text-primary fs-3"></i>
                        </div>

                        <h5 id="det-paciente" class="fw-bold mb-1"></h5>
                        <div id="det-info-extra" class="badge bg-light text-dark border rounded-pill px-3 mb-4" style="font-weight: 500;"></div>

                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="p-2 border rounded-3 bg-light-subtle text-start h-100">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Médico</small>
                                    <span id="det-medico" class="fw-semibold small d-block text-truncate"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded-3 bg-light-subtle text-start h-100">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.7rem; text-transform: uppercase;">Horario</small>
                                    <span id="det-fecha" class="fw-semibold small d-block"></span>
                                </div>
                            </div>
                        </div>

                        <p class="small text-muted mb-2 fw-bold">CAMBIAR ESTADO</p>

                        <div class="d-grid gap-2">
                            <div class="btn-group w-100 shadow-sm" role="group">
                                <button type="button" class="btn btn-outline-warning btn-estado py-2" data-estado="espera" title="Poner en espera">
                                    <i class="bi bi-clock"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-estado py-2" data-estado="consulta" title="Iniciar atención">
                                    <i class="bi bi-person-check"></i> Atender
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-estado py-2" data-estado="finalizado" title="Finalizar turno">
                                    <i class="bi bi-check2-all"></i>
                                </button>
                            </div>
                        </div>

                        <input type="hidden" id="det-id">
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 pb-4 d-flex flex-column gap-2">
                    <button type="button" id="btnEliminar" class="btn btn-sm text-danger border-0 bg-transparent">
                        <i class="bi bi-trash me-1"></i> Cancelar Cita
                    </button>
                </div>
            </div>
        </div>
    </div>