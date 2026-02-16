<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClinicSoft | Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #4f46e5;
            --sidebar-width: 260px;
            --bg-body: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
            display: flex;
            align-items: center;
        }

        .nav-link {
            padding: 0.8rem 1.5rem;
            color: #64748b;
            font-weight: 500;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            transition: 0.2s;
        }

        .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
            background: #f5f3ff;
            border-left-color: var(--primary);
        }

        /* Contenido */
        #content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .card-calendar {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            background: #fff;
            padding: 1.5rem;
        }

        /* FullCalendar Custom */
        .fc {
            --fc-border-color: #f1f5f9;
            background: white;
            border: none;
        }

        .fc-toolbar-title {
            font-size: 1.2rem !important;
            font-weight: 700;
        }

        .fc-v-event {
            border-radius: 6px !important;
            border: none !important;
            padding: 2px 4px;
        }

        /* Botones de Estado */
        .btn-state-pill {
            border-radius: 50px;
            font-size: 0.8rem;
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-espera:hover {
            background: #fef3c7;
            color: #92400e;
            border-color: #f59e0b;
        }

        .btn-atender:hover {
            background: #e0f2fe;
            color: #075985;
            border-color: #0ea5e9;
        }

        .btn-finalizar:hover {
            background: #f1f5f9;
            color: #475569;
            border-color: #94a3b8;
        }

        /* Clases dinámicas de estado para el modal */
        .modal-estado-espera {
            border-top: 6px solid #f59e0b !important;
            background-color: #fef3c7 !important;
        }

        .modal-estado-consulta {
            border-top: 6px solid #0ea5e9 !important;
            background-color: #e0f2fe !important;
        }

        .modal-estado-finalizado {
            border-top: 6px solid #64748b !important;
            background-color: #f1f5f9 !important;
        }

        .modal-estado-pendiente {
            border-top: 6px solid #4ade80 !important;
            background-color: #dcfce7 !important;
        }

        /* Ajuste para que los iconos también cambien */
        .modal-estado-pendiente .bi-person {
            color: #4ade80 !important;
        }

        .modal-estado-espera .bi-person {
            color: #f59e0b !important;
        }

        .modal-estado-consulta .bi-person {
            color: #0ea5e9 !important;
        }

        .modal-estado-finalizado .bi-person {
            color: #64748b !important;
        }

        /* Estilo para resaltar el botón del estado actual */
        .btn-estado.active {
            border-width: 2px !important;
            font-weight: bold !important;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125) !important;
        }

        /* Colores específicos cuando están activos */
        .btn-outline-warning.active {
            background-color: #f59e0b !important;
            color: white !important;
            border-color: #d97706 !important;
        }

        .btn-outline-primary.active {
            background-color: #0ea5e9 !important;
            color: white !important;
            border-color: #0284c7 !important;
        }

        .btn-outline-secondary.active {
            background-color: #64748b !important;
            color: white !important;
            border-color: #475569 !important;
        }
    </style>
</head>

<body>

    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-shield-plus me-2"></i> ClinicSoft
        </div>
        <div class="mt-4">
            <div class="small text-uppercase px-4 mb-2 text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.1em;">Principal</div>

            <a href="<?= base_url('turnos') ?>" class="nav-link <?= (uri_string() == 'turnos') ? 'active' : '' ?>">
                <i class="bi bi-calendar-event"></i> Agenda de Turnos
            </a>

            <div class="small text-uppercase px-4 mt-4 mb-2 text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.1em;">Administración</div>

            <a href="<?= base_url('profesionales') ?>" class="nav-link <?= (strpos(uri_string(), 'profesionales') !== false) ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Gestión de Médicos
            </a>

            <a href="<?= base_url('pacientes') ?>" class="nav-link <?= (strpos(uri_string(), 'pacientes') !== false) ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i> Gestión de Pacientes
            </a>

            <div style="position: absolute; bottom: 20px; width: 100%;">
                <hr class="mx-4 text-muted">
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <main id="content">
        <header class="top-bar">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Hola, <?= session()->get('nombre') ?></h4>
                <p class="text-muted small mb-0">Gestión de Clínica en tiempo real</p>
            </div>

            <div class="d-flex align-items-center gap-2 ms-3" style="max-width: 300px;">
                <div class="position-relative w-100">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                    <input type="text" id="buscarDniTurno" class="form-control form-control-sm rounded-pill ps-5" placeholder="Buscar turno por DNI...">
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div style="min-width: 220px;">
                    <select id="filtroMedico" class="form-select form-select-sm border-0 shadow-sm rounded-pill">
                        <option value="">Todos los profesionales</option>
                        <?php foreach ($profesionales as $p): ?>
                            <option value="<?= $p->id ?>"><?= $p->nombre ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="btn btn-primary rounded-pill shadow-sm btn-sm px-4" data-bs-toggle="modal" data-bs-target="#modalNuevo">
                    <i class="bi bi-plus-lg me-1"></i> Agendar Turno
                </button>
            </div>
        </header>

        <div class="card card-calendar">
            <div id="calendar"></div>
        </div>
    </main>

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

    <div class="modal fade" id="modalPacienteRapido" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Registro Rápido</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formPacienteRapido" class="modal-body">
                    <div class="mb-2">
                        <label class="small fw-bold">DNI</label>
                        <input type="text" name="dni" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">APELLIDO</label>
                        <input type="text" name="apellido" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold">NOMBRE</label>
                        <input type="text" name="nombre" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Registrar y Seleccionar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalResultadosBusqueda" tabindex="-1" style="z-index: 1070;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold"><i class="bi bi-search me-2"></i>Turnos encontrados para el paciente</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Fecha y Hora</th>
                                    <th>Médico / Especialidad</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-4">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoResultadosBusqueda">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right"
        };

        var calendar;

        var turnosEncontrados = [];

        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                firstDay: 1,
                slotMinTime: '08:00:00',
                slotMaxTime: '22:00:00',
                allDaySlot: false,
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                events: function(info, successCallback, failureCallback) {
                    $.get('<?= base_url('turnos/eventos') ?>', {
                        profesional_id: $('#filtroMedico').val()
                    }, (res) => successCallback(res));
                },
                dateClick: function(info) {
                    let p = info.dateStr.split('T');
                    $('input[name="fecha"]').val(p[0]);
                    $('input[name="hora"]').val(p[1] ? p[1].substring(0, 5) : '');
                    $('#modalNuevo').modal('show');
                },
                editable: true,
                eventDrop: function(info) {
                    $.post('<?= base_url('turnos/actualizar') ?>', {
                        id: info.event.id,
                        fecha_hora: info.event.startStr.replace('T', ' ').substring(0, 19)
                    }).done(res => {
                        if (res.status === 'error') {
                            toastr.error(res.msg);
                            info.revert();
                        } else {
                            toastr.success(res.msg);
                        }
                    }).fail(xhr => {
                        toastr.error(xhr.responseJSON.msg);
                        info.revert();
                    });
                },
                eventClick: function(info) {
                    mostrarTurnoEncontrado(info.event);
                }
            });

            calendar.render();

            $('#filtroMedico').change(() => calendar.refetchEvents());

            $('#formTurno').on('submit', function(e) {
                e.preventDefault();
                $.post('<?= base_url('turnos/agendar') ?>', $(this).serialize())
                    .done(res => {
                        $('#modalNuevo').modal('hide');
                        $('#formTurno')[0].reset();
                        calendar.refetchEvents();
                        toastr.success(res.msg);
                    }).fail(xhr => toastr.error(xhr.responseJSON.msg));
            });

            $('.btn-estado').click(function() {
                $.post('<?= base_url('turnos/cambiarEstado') ?>', {
                    id: $('#det-id').val(),
                    estado: $(this).data('estado')
                }).done(() => {
                    $('#modalDetalles').modal('hide');
                    calendar.refetchEvents();
                    toastr.success("Estado actualizado");
                });
            });

            $('#btnEliminar').click(function() {
                if (confirm("¿Cancelar turno?")) {
                    $.post('<?= base_url('turnos/eliminar/') ?>' + $('#det-id').val())
                        .done(() => {
                            $('#modalDetalles').modal('hide');
                            calendar.refetchEvents();
                            toastr.info("Cita cancelada");
                        });
                }
            });

            $('#formPacienteRapido').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.post('<?= base_url('pacientes/guardar_ajax') ?>', formData, function(res) {
                    if (res.status === 'success') {
                        const newOption = new Option(res.text, res.id, true, true);
                        $('#selectPaciente').append(newOption).trigger('change');
                        $('#modalPacienteRapido').modal('hide');
                        $('#formPacienteRapido')[0].reset();
                        toastr.success('Paciente creado');
                    }
                });
            });

            $('#buscarDniTurno').on('keyup', function(e) {
                let dniBusqueda = $(this).val().trim();

                // Solo buscamos si hay más de 3 caracteres
                if (dniBusqueda.length > 3) {
                    let eventos = calendar.getEvents();

                    // Filtramos coincidencias
                    turnosEncontrados = eventos.filter(ev =>
                        ev.extendedProps.dni && ev.extendedProps.dni.includes(dniBusqueda)
                    );

                    // Si presiona Enter y hay resultados
                    if (e.key === "Enter" && turnosEncontrados.length > 0) {
                        if (turnosEncontrados.length === 1) {
                            // Solo uno: levantamos modal directo
                            mostrarTurnoEncontrado(turnosEncontrados[0]);
                        } else {
                            // Varios: levantamos la grilla
                            mostrarGrillaResultados(turnosEncontrados);
                        }
                    }
                }
            });
        });

        // Función para mostrar la tabla de resultados
        function mostrarGrillaResultados(eventos) {
            let html = '';
            eventos.forEach((ev, index) => {
                const fechaFormateada = ev.start.toLocaleString('es-AR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                html += `
            <tr>
                <td class="ps-4 fw-medium">${fechaFormateada} hs</td>
                <td>${ev.extendedProps.medico}</td>
                <td><span class="badge bg-light text-dark border">${ev.extendedProps.estado || 'Pendiente'}</span></td>
                <td class="text-end pe-4">
                    <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="seleccionarTurnoDeGrilla(${index})">
                        Ver detalle
                    </button>
                </td>
            </tr>`;
            });

            $('#cuerpoResultadosBusqueda').html(html);
            $('#modalResultadosBusqueda').modal('show');
        }

        // Función para cuando eligen uno de la tabla
        function seleccionarTurnoDeGrilla(index) {
            $('#modalResultadosBusqueda').modal('hide');
            mostrarTurnoEncontrado(turnosEncontrados[index]);
        }

        // Función principal para abrir el detalle (se mantiene la lógica original)
        function mostrarTurnoEncontrado(evento) {
            debugger

            calendar.gotoDate(evento.start);

            colorearPorEstado(evento);

            // Llenar datos en el modal de detalles
            $('#det-paciente').text(evento.extendedProps.paciente);
            $('#det-info-extra').text(`DNI: ${evento.extendedProps.dni} | OS: ${evento.extendedProps.obra_social || 'Particular'}`);
            $('#det-medico').text(evento.extendedProps.medico);
            $('#det-fecha').text(evento.start.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            }) + " hs");
            $('#det-id').val(evento.id);

            $('#modalDetalles').modal('show');
        }

        function colorearPorEstado(evento) {
            const estado = evento.extendedProps.estado; // 'pendiente', 'espera', 'consulta', 'finalizado'
            const modalContent = $('#modalDetalles .modal-content');

            // 1. Manejo de colores de fondo (lo que ya teníamos)
            modalContent.removeClass('modal-estado-espera modal-estado-consulta modal-estado-finalizado modal-estado-pendiente');

            // 2. Resetear estados de los botones (quitar el sombreado de "activo")
            $('.btn-estado').removeClass('active aria-pressed');

            // 3. Aplicar estilos y activar botón según el estado
            switch (estado) {
                case 'espera':
                    modalContent.addClass('modal-estado-espera');
                    $('[data-estado="espera"]').addClass('active').attr('aria-pressed', 'true');
                    break;
                case 'consulta':
                    modalContent.addClass('modal-estado-consulta');
                    $('[data-estado="consulta"]').addClass('active').attr('aria-pressed', 'true');
                    break;
                case 'finalizado':
                    modalContent.addClass('modal-estado-finalizado');
                    $('[data-estado="finalizado"]').addClass('active').attr('aria-pressed', 'true');
                    break;
                default:
                    modalContent.addClass('modal-estado-pendiente');
                    break;
            }
        }
    </script>
</body>

</html>