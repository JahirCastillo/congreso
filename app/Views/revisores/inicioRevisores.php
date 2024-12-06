<?= $this->extend('template'); ?>
<?= $this->section('content'); ?>

<!-- Contenido -->
<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Lista de Ponencias</h3>
                </div>
                <div class="card-body">
                    <!-- Tabs for switching between different ponencias -->
                    <ul class="nav nav-tabs" id="ponenciasTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pendientes-tab" data-bs-toggle="tab"
                                href="#ponenciasPendientes" role="tab" aria-controls="ponenciasPendientes"
                                aria-selected="true">Pendientes</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="rechazadas-tab" data-bs-toggle="tab" href="#ponenciasRechazadas"
                                role="tab" aria-controls="ponenciasRechazadas" aria-selected="false">Rechazadas</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="aprobadas-tab" data-bs-toggle="tab" href="#ponenciasAprobadas"
                                role="tab" aria-controls="ponenciasAprobadas" aria-selected="false">Aprobadas</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="ponenciasTabContent">
                        <!-- Ponencias Pendientes Tab -->
                        <div class="tab-pane fade show active" id="ponenciasPendientes" role="tabpanel"
                            aria-labelledby="pendientes-tab">
                            <table id="tablaPonenciasPendientes" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Temática</th>
                                        <th>Institución</th>
                                        <th>País</th>
                                        <th>Ponente</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ponenciasPendientes as $row): ?>
                                        <tr>
                                            <td><?= esc($row['po_titulo']) ?></td>
                                            <td><?= esc($row['tematica']) ?></td>
                                            <td><?= esc($row['institucion']) ?></td>
                                            <td><?= esc($row['pais']) ?></td>
                                            <td><?= esc($row['ponente']) ?></td>
                                            <td>
                                                <button class="btn btn-success btn-sm"
                                                    onclick="verDetalles(<?= esc($row['po_id_ponencia']) ?>)">
                                                    <i class="bi bi-eye"></i> Ver más
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Ponencias Rechazadas Tab -->
                        <div class="tab-pane fade" id="ponenciasRechazadas" role="tabpanel"
                            aria-labelledby="rechazadas-tab">
                            <table id="tablaPonenciasRechazadas" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Temática</th>
                                        <th>Institución</th>
                                        <th>País</th>
                                        <th>Ponente</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ponenciasRechazadas as $row): ?>
                                        <tr>
                                            <td><?= esc($row['po_titulo']) ?></td>
                                            <td><?= esc($row['tematica']) ?></td>
                                            <td><?= esc($row['institucion']) ?></td>
                                            <td><?= esc($row['pais']) ?></td>
                                            <td><?= esc($row['ponente']) ?></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm"
                                                    onclick="verDetalles(<?= esc($row['po_id_ponencia']) ?>)">
                                                    <i class="bi bi-eye"></i> Ver más
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Ponencias Aprobadas Tab -->
                        <div class="tab-pane fade" id="ponenciasAprobadas" role="tabpanel"
                            aria-labelledby="aprobadas-tab">
                            <table id="tablaPonenciasAprobadas" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Temática</th>
                                        <th>Institución</th>
                                        <th>País</th>
                                        <th>Ponente</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ponenciasAprobadas as $row): ?>
                                        <tr>
                                            <td><?= esc($row['po_titulo']) ?></td>
                                            <td><?= esc($row['tematica']) ?></td>
                                            <td><?= esc($row['institucion']) ?></td>
                                            <td><?= esc($row['pais']) ?></td>
                                            <td><?= esc($row['ponente']) ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="verDetalles(<?= esc($row['po_id_ponencia']) ?>)">
                                                    <i class="bi bi-eye"></i> Ver más
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalDetallesPonencia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalDetallePonencia"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetallePonencia">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        aplicaDataTable('#tablaPonenciasPendientes');
        aplicaDataTable('#tablaPonenciasRechazadas');
        aplicaDataTable('#tablaPonenciasAprobadas');
    });

    function aplicaDataTable(selector) {
        $(selector).DataTable({
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
            responsive: true
        });
    }
    function verDetalles(id, ponente) {
        getValue('revisor/getDetallesPonencia', { id: id }, function (response) {
            $('#contenidoDetallePonencia').html(response);
            $('#modalDetallesPonencia').modal('show');
        });
    }
</script>
<?= $this->endSection(); ?>