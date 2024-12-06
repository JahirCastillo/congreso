<?= $this->extend('templatePonentes'); ?>
<?= $this->section('content'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <h2 class="text-center text-primary mt-3">Lista de Ponencias</h2>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <?php if (empty($ponencias)): ?>
                        <div class="alert alert-info text-center" role="alert">
                            No tiene ponencias registradas, para registrar una ponencia dar clic en el botón siguiente:
                            <a href="<?= site_url('ponencias/nuevaPonencia/0') ?>" class="btn btn-success ms-auto">
                                <i class="fas fa-plus"></i> Agregar Nueva Ponencia
                            </a>
                        </div>

                    <?php else: ?>
                        <div class="card-header d-flex align-items-center">
                            <a href="<?= site_url('ponencias/nuevaPonencia') ?>" class="btn btn-success ms-auto">
                                <i class="fas fa-plus"></i> Agregar Nueva Ponencia
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="tablaPonencias">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Temática</th>
                                        <th>Hora de Inicio</th>
                                        <th>Estatus</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ponencias as $ponencia): ?>
                                        <tr>
                                            <td><?= $ponencia['po_titulo'] ?></td>
                                            <td><?= $ponencia['tematica'] ?></td>
                                            <td><?= $ponencia['po_hora_inicio'] ?></td>
                                            <td>
                                                <?php
                                                if ($ponencia['po_estatus'] == 'A') {
                                                    echo '<span class="badge bg-success">Aceptado</span>';
                                                } elseif ($ponencia['po_estatus'] == 'R') {
                                                    echo '<span class="badge bg-danger">Rechazado</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning text-bg-warning">Pendiente</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('ponencias/editar/' . $ponencia['po_id_ponencia']) ?>"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?= $this->endSection(); ?>