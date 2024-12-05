<?= $this->extend('template'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card mb-1">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="editarConvocatoria(0)">
                                <i class="bi bi-plus"></i> Nueva convocatoria
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="position-relative mt-1 p-2">
                        <table id="tablaConvocatorias" class="table table-hover table-sm display" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nombre</th>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Ubicación</th>
                                    <th>Estatus</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- La tabla estará vacía inicialmente, ya que cargaremos los datos mediante AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Modal detalles usuario -->
<div class="modal fade" id="modalDetallesConvocatoria" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalDetalleConvocatoria"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleConvocatoria">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnGuardarCambios">
                    Guardar Cambios
                </button>
                <button type="button" class="btn btn-secondary" id="btnCerrarDetalleConvocatoria"
                    data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#tablaConvocatorias').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('index.php/convocatorias/getConvocatorias'); ?>",
                "type": "POST",
                "dataSrc": "data"
            },
            "columns": [
                {
                    "data": "id_convocatoria",
                    "orderable": false
                },
                { "data": "nombre" },
                { "data": "fecha_inicio" },
                { "data": "fecha_fin" },
                { "data": "ubicacion" },
                { "data": "estatus" },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '<button class="btn btn-warning editar-usuario" onclick="editarConvocatoria(' + row.id_convocatoria + ')"><i class="bi bi-pencil"></i></button>';
                    },
                    "orderable": false
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '<button class="btn btn-danger eliminar-usuario" onclick="eliminarConvocatoria(' + row.id_convocatoria + ')"><i class="bi bi-trash"></i></button>';
                    },
                    "orderable": false
                }
            ],
            "lengthMenu": [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "pageLength": 10,
            "searching": true,
            search: {
                return: true
            },
            "ordering": true,
            "order": [[1, 'asc']],
            "language": {
                "emptyTable": "No hay datos disponibles en la tabla",
                "loadingRecords": "Cargando...",
                "lengthMenu": "Mostrar _MENU_ registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "sSearch": "Buscar:",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "autoWidth": false,
            "columnDefs": [
                { targets: 0, "visible": false },
                { targets: 1 },
                { targets: 2 },
                { targets: 3 },
                { targets: 4 },
                { targets: 5 },
                { targets: 6, width: '50px' },
                { targets: 7, width: '50px' }
            ],
            responsive: true,
            stateSave: false
        });
        $('#btnGuardarCambios').on('click', function (event) {
            event.preventDefault();
            $('.contenedorErrores').hide();
            var formulario = $('#formularioConvocatoria'); // Obtener el formulario como un objeto DOM
            if (formulario[0].checkValidity()) {
                let fechaInicio = $('#fecha_inicio').val();
                let fechaFin = $('#fecha_fin').val();
                let fechaInicioRecepcion = $('#fecha_inicio_recepcion_documentos').val();
                let fechaLimiteDocumentos = $('#fecha_limite_documentos').val();
                let validacionFechas = validaFechasConvocatoria(fechaInicio, fechaFin, fechaInicioRecepcion, fechaLimiteDocumentos);
                if (!validacionFechas) {
                    return;
                }
                var datosFormulario = $('#formularioConvocatoria').serialize();
                var descripcion = $('#descripcion').summernote('code');
                datosFormulario += '&descripcionconvoactoria=' + encodeURIComponent(descripcion);
                getObject('convocatorias/guardaCambiosConvocatoria', datosFormulario, function (response) {
                    if (response.estatus === 'ok') {
                        $('#tablaConvocatorias').DataTable().ajax.reload();
                        $('#modalDetallesConvocatoria').modal('hide');
                    } else {
                        if (response.formularioValido === 'no') {
                            let erroresHtml = '<ul>';
                            for (var campo in response.errores) {
                                erroresHtml += '<li>' + response.errores[campo] + '</li>';
                            }
                            erroresHtml += '</ul>';
                            $('.contenedorErrores')
                                .html(erroresHtml)
                                .css({
                                    'display': 'block',
                                    'background-color': '#f8d7da',
                                    'color': '#721c24',
                                    'padding': '10px',
                                    'border-radius': '5px',
                                    'margin-top': '10px',
                                });
                            $('.contenedorErrores').show();
                        } else {
                            alert('Error al guardar los cambios.');
                        }
                    }
                });
            } else {
                formulario.addClass('was-validated');
            }

        });
    });
    function editarConvocatoria(id) {
        mostrarCargando();
        getValue('convocatorias/getDetallesConvocatoria', { id: id }, function (htmlDetalle) {
            $('#tituloModalDetalleConvocatoria').html('Detalles de la convocatoria');
            $('#contenidoDetalleConvocatoria').html(htmlDetalle);
            ocultarCargando();
            if (id == 0) {
                $('#btnGuardarCambios').html('<i class="bi bi-floppy"></i> Agregar convocatoria');
            } else {
                $('#btnGuardarCambios').html('<i class="bi bi-floppy"></i> Guardar cambios');
            }
            $('#modalDetallesConvocatoria').modal('show');
        });
    }

    function eliminarConvocatoria(id) {
        Swal.fire({
            title: '¿Está seguro de eliminar la convocatoria?',
            text: "No podrá revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
            getObject('convocatorias/eliminaConvocatoria', { id: id }, function (response) {
                if (response.estatus === 'ok') {
                $('#tablaConvocatorias').DataTable().ajax.reload();
                Swal.fire(
                    'Eliminado',
                    'La convocatoria ha sido eliminada.',
                    'success'
                );
                } else {
                Swal.fire(
                    'Error!',
                    'Hubo un problema al eliminar la convocatoria.',
                    'error'
                );
                }
            });
            }
        });
    }

    function validaFechasConvocatoria(fechaInicio, fechaFin, fechaInicioRecepcion, fechaLimiteDocumentos) {
        if (new Date(fechaInicio) > new Date(fechaFin) || new Date(fechaInicioRecepcion) > new Date(fechaLimiteDocumentos)) {
            $('.contenedorErrores')
                .html('<h6>La fecha de inicio no puede ser mayor a la fecha de fin.</h6>')
                .css({
                    'display': 'block',
                    'background-color': '#f8d7da',
                    'color': '#721c24',
                    'padding': '10px',
                    'border-radius': '5px',
                    'margin-top': '10px',
                });
            $('.contenedorErrores').show();
            return false; // Detener el proceso si la validación falla
        }
        return true;
    }
</script>
<?= $this->endSection(); ?>