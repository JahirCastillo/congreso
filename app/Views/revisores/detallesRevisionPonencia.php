<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white text-center">
        <h4 class="mb-0"><?= esc($po_titulo) ?></h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Ponente</strong>
                    </div>
                    <div class="card-body">
                        <p class="fs-5"><?= esc($ponente) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Institución</strong>
                    </div>
                    <div class="card-body">
                        <p class="fs-5"><?= esc($institucion) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Temática</strong>
                    </div>
                    <div class="card-body">
                        <p class="fs-5"><?= esc($tematica) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Subtemática</strong>
                    </div>
                    <div class="card-body">
                        <p class="fs-5"><?= esc($subtematica) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>País</strong>
                    </div>
                    <div class="card-body">
                        <p class="fs-5"><?= esc($pais) ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Fecha de Registro</strong>
                    </div>
                    <div class="card-body">
                        <p class="fs-5"><?= esc($po_fecha_registro) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header bg-success text-white">
        <strong>Archivo de la Ponencia</strong>
    </div>
    <div class="card-body">
        <p class="text-muted fs-6 mb-2">Haz clic para descargar el archivo asociado a esta ponencia.</p>
        <a href="<?= site_url('revisor/descargaArchivo/' . $id_ponente . '/' . $archivo) ?>"
            class="btn btn-outline-primary btn-lg w-100" download>
            <i class="fas fa-download me-2"></i> Descargar Ponencia
        </a>
    </div>
</div>

<?php if ($po_estatus == 'P'): ?>
    <div class="card shadow-sm mt-4">
        <div class="card-body text-center">
            <h5 class="mb-3">Acciones para esta ponencia</h5>
            <div class="d-flex justify-content-around">
                <!-- Botón para autorizar -->
                <button type="button" class="btn btn-success btn-lg w-100 me-2" id="btnAutorizar">
                    <i class="fas fa-check-circle me-2"></i> Autorizar
                </button>

                <!-- Botón para rechazar -->
                <button type="button" class="btn btn-danger btn-lg w-100" id="btnRechazar">
                    <i class="fas fa-times-circle me-2"></i> Rechazar
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if ($po_estatus === 'R'): ?>
    <div class="card shadow-sm mt-4 alert alert-warning">
        <div class="card-body text-center">
            <h5 class="mb-3">Motivo del rechazo</h5>
            <p class="fs-5"><?= esc($po_motivorechazo) ?></p>
        </div>
    </div>
<?php endif; ?>

<script>
    $('#btnAutorizar').on('click', function () {
        Swal.fire({
            title: '¿Estás seguro de autorizar esta ponencia?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, autorizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',  // Verde para autorizar
            cancelButtonColor: '#dc3545'  // Rojo para cancelar
        }).then((result) => {
            if (result.isConfirmed) {
                getObject('revisor/aceptarPonencia', { ponencia: <?= $po_id_ponencia ?> }, function (respuesta) {
                    if (respuesta.estatus == 'ok') {
                        Swal.fire({
                            title: 'Ponencia aceptada',
                            text: 'La ponencia ha sido aceptada.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: '¡Error!',
                            text: 'Ocurrió un error al aceptar la ponencia. Por favor, inténtelo de nuevo.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            }
        });
    });

    $('#btnRechazar').on('click', function () {
        $('#modalDetallesPonencia').hide();
        Swal.fire({
            title: '¿Estás seguro de rechazar esta ponencia?',
            text: "Por favor, indícanos el motivo del rechazo.",
            icon: 'question',
            input: 'textarea',  // Habilitar el área de texto
            inputPlaceholder: 'Escribe el motivo aquí...',  // Texto de marcador de posición
            showCancelButton: true,
            confirmButtonText: 'Rechazar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc3545',  // Rojo para rechazar
            cancelButtonColor: '#28a745',  // Verde para cancelar
            inputAttributes: {
                'aria-label': 'Motivo del rechazo',  // Descripción accesible
                'rows': 4  // Ajustar el tamaño del área de texto
            },
            backdrop: false,
            customClass: {
                popup: 'swal-popup',  // Aplicamos una clase personalizada
            }
        }).then((result) => {
            $('#modalDetallesPonencia').show();
            if (result.isConfirmed && result.value) {
                getObject('revisor/rechazarPonencia', { ponencia: <?= $po_id_ponencia ?>, motivo: result.value }, function (respuesta) {
                    if (respuesta.estatus == 'ok') {
                        Swal.fire({
                            title: 'Ponencia rechazada',
                            text: 'La ponencia ha sido rechazada correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: '¡Error!',
                            text: 'Ocurrió un error al rechazar la ponencia. Por favor, inténtalo de nuevo.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            } else if (result.isConfirmed) {
                // Si no se escribió motivo, mostrar advertencia
                Swal.fire({
                    title: '¡Error!',
                    text: 'Debes proporcionar un motivo para rechazar.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        });
    });
</script>