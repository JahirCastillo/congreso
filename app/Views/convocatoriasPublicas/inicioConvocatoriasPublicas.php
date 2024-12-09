<?= $this->extend('templateSinMenu'); ?>
<?= $this->section('content'); ?>
<?php helper('date'); ?>
<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <h1 class="text-center text-primary mt-3">Convocatoria</h1>
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
                            <div class="mb-3">
                                <?= $convocatoria['descripcion']; ?>
                            </div>

                            <p class="text-black text-center">
                               <?= esc($convocatoria['ubicacion']). " a ".fechaEspaniol($convocatoria['fecha_inicio']); ?>
                            </p>
                            <a href="<?= base_url('registro'); ?>" class="btn btn-primary">Ir al registro</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>