<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Turnos<?= $this->endSection() ?>

<?= $this->section('content') ?>

<header class="top-bar-turnos d-flex align-items-center justify-content-between p-3 bg-white border-bottom shadow-sm">
    <div class="d-none d-md-block">
        <h4 class="fw-bold mb-0 text-dark">Hola, <?= session()->get('nombre') ?> 👋</h4>
        <p class="text-muted small mb-0">Gestión de agenda y citas</p>
    </div>

    <div class="flex-grow-1 mx-4" style="max-width: 400px;">
        <div class="position-relative">
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" id="buscarDniTurno"
                class="form-control border-0 bg-light rounded-pill ps-5 py-2 shadow-none"
                placeholder="Buscar por DNI o Paciente..."
                style="transition: all 0.3s ease; border: 1px solid #eee !important;">
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <select id="filtroMedico" class="form-select form-select-sm border-0 bg-light rounded-pill shadow-sm px-3" style="min-width: 180px; height: 38px;">
            <option value="">Profesionales</option>
            <?php foreach ($profesionales as $p): ?>
                <option value="<?= $p->id ?>"><?= $p->nombre ?></option>
            <?php endforeach; ?>
        </select>

        <div class="position-relative">
            <button type="button" class="btn btn-light rounded-pill shadow-sm border px-3" onclick="cargarPendientes()" style="height: 38px;">
                <i class="bi bi-whatsapp text-success"></i>
            </button>
            <span id="badge-notificaciones"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white"
                style="display: none; font-size: 0.7rem; margin-top: 5px; margin-left: -5px;">
                0
            </span>
        </div>

        <button class="btn btn-primary rounded-pill shadow-sm px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#modalNuevo" style="height: 38px;">
            <i class="bi bi-plus-lg"></i> 
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
<?= view('modales/modal_notificaciones') ?>

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


        cargarPendientes();
    });

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

    function cargarPendientes() {
        $.get('<?= base_url('turnos/obtenerPendientesNotificar') ?>', function(res) {
            actualizarContadorNotificaciones(res);
            if (res.length > 0) {
                let html = '';
                res.forEach(t => {
                    html += `
                <div class="list-group-item d-flex justify-content-between align-items-center" id="fila-notif-${t.id}">
                    <div>
                        <strong>${t.p_nombre}</strong><br>
                        <small class="text-muted">${moment(t.fecha_hora).format('HH:mm')} - Dr. ${t.prof_nombre}</small>
                    </div>
                    <button class="btn btn-sm btn-success" onclick="enviarIndividual('${t.id}', '${t.p_tel}', '${t.p_nombre}', '${t.fecha_hora}', '${t.prof_nombre}')">
                        <i class="bi bi-whatsapp"></i> Enviar
                    </button>
                </div>`;
                });
                $('#listaPendientes').html(html);
                $('#modalNotificaciones').modal('show');
            }
        });
    }

    function enviarIndividual(id, tel, nombre, fecha, prof) {
        const hora = moment(fecha).format('HH:mm');
        const mensaje = encodeURIComponent(`Hola ${nombre}, te recordamos tu turno para mañana a las ${hora} con el profesional ${prof}. Por favor confirma tu asistencia.`);
        const url = `https://wa.me/${tel.replace(/\D/g, '')}?text=${mensaje}`;

        // 1. Abrimos WhatsApp
        window.open(url, '_blank');

        // 2. Marcamos en la DB como notificado para que no vuelva a salir
        $.post('<?= base_url('turnos/marcarComoNotificado/') ?>' + id, function() {
            $(`#fila-notif-${id}`).addClass('d-none');
        });
    }

    function actualizarContadorNotificaciones(res) {
        const count = res.length;
        const badge = $('#badge-notificaciones');

        if (count > 0) {
            badge.text(count).show();
        } else {
            badge.hide();
        }
    }
</script>
<?= $this->endSection() ?>