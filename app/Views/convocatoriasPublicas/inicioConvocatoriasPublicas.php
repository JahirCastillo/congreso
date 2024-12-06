<?= $this->extend('templateSinMenu'); ?>
<?= $this->section('content'); ?>
<?php helper('date'); ?>
<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <h1 class="text-center text-primary mt-3">Convocatorias Disponibles</h1>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <?php foreach ($convocatorias as $convocatoria): ?>
                <div class="col-md-12">
                    <div class="card shadow-lg mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><?= esc($convocatoria['nombre']); ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="text-dark fw-bold">
                                Fechas del Congreso:
                            </p>
                            <p class="text-muted">
                                Del
                                <strong><?= fechaEspaniol($convocatoria['fecha_inicio']); ?></strong>
                                al
                                <strong><?= fechaEspaniol($convocatoria['fecha_fin']); ?></strong>
                            </p>

                            <p class="text-dark fw-bold">
                                Recepción de documentos:
                            </p>
                            <p class="text-info mb-3">
                                Desde
                                <strong><?= fechaEspaniol($convocatoria['fecha_inicio_recepcion_documentos']); ?></strong>
                                hasta <strong><?= fechaEspaniol($convocatoria['fecha_limite_documentos']); ?></strong>
                            </p>
                            <p class="text-success">
                                <strong>Ubicación:</strong> <?= esc($convocatoria['ubicacion']); ?>
                            </p>
                            <div class="mb-3">
                                <?= $convocatoria['descripcion']; ?>
                            </div>
                            <a href="<?= base_url('registro'); ?>" class="btn btn-primary">Ir al Registro</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>