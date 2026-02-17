<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Turnos<?= $this->endSection() ?>

<?= $this->section('content') ?>

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

<?= view('modales/modal_nuevo_turno') ?>
<?= view('modales/modal_detalles') ?>
<?= view('modales/modal_paciente_rapido') ?>
<?= view('modales/modal_resultados_busqueda') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

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
<?= $this->endSection() ?>