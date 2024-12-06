<?= $this->extend('template'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card mb-1">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <button type="button" class="btn btn-primary" onclick="editarUsuario(0)">
                                <i class="bi bi-plus"></i> Nuevo Usuario
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="position-relative mt-1 p-2">
                        <table id="tablaUsuarios" class="table table-hover table-sm display" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Temática</th>
                                    <th>Correo</th>
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
<div class="modal fade" id="modalDetallesUsuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalDetalleUsuario"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalleUsuario">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnGuardarCambios">
                    Guardar Cambios
                </button>
                <button type="button" class="btn btn-secondary" id="btnCerrarDetalleUsuario"
                    data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#tablaUsuarios').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('index.php/usuarios/getUsuarios'); ?>",
                "type": "POST",
                "dataSrc": "data"
            },
            "columns": [
                {
                    "data": "id",
                    "orderable": false
                },
                { "data": "login" },
                { "data": "nombre" },
                { "data": "tematica" },
                { "data": "correo" },
                { "data": "estatusCuenta" },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '<button class="btn btn-warning editar-usuario" onclick="editarUsuario(' + row.id + ')"><i class="bi bi-pencil"></i></button>';
                    },
                    "orderable": false
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '<button class="btn btn-danger eliminar-usuario" onclick="eliminarUsuario(' + row.id + ')"><i class="bi bi-trash"></i></button>';
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
    });
    function editarUsuario(id) {
        mostrarCargando();
        getValue('usuarios/getDetallesUsuario', { id: id }, function (htmlDetalle) {
            $('#tituloModalDetalleUsuario').html('Detalles del usuario');
            $('#contenidoDetalleUsuario').html(htmlDetalle);
            ocultarCargando();
            if (id == 0) {
                $('#btnGuardarCambios').html('<i class="bi bi-floppy"></i> Agregar usuario');
            } else {
                $('#btnGuardarCambios').html('<i class="bi bi-floppy"></i> Guardar cambios');
            }
            $('#modalDetallesUsuario').modal('show');
        });
    }
</script>
<?= $this->endSection(); ?>