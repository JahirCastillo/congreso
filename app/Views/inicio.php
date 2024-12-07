<?= $this->extend('template'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box">
            <span class="info-box-icon text-bg-primary shadow-sm">
                <i class="bi bi-hourglass-split"></i>
            </span>
            <div class="info-box-content"> <span class="info-box-text">Ponencias Pendientes</span>
                <span class="info-box-number">
                    <?= $conteosPonencias['ponencias_pendientes']; ?>
                    <small></small>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box">
            <span class="info-box-icon text-bg-success shadow-sm">
                <i class="bi bi-check-circle-fill"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Ponencias Aceptadas</span>
                <span class="info-box-number">
                    <?= $conteosPonencias['ponencias_aceptadas']; ?>
                    <small></small>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="info-box">
            <span class="info-box-icon text-bg-danger shadow-sm">
                <i class="bi bi-x-circle-fill"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Ponencias Rechazadas</span>
                <span class="info-box-number">
                    <?= $conteosPonencias['ponencias_rechazadas']; ?>
                    <small></small>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mx-auto">
                    <strong>Porcentaje de aceptación de ponencias</strong>
                </h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                        <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="sales-chart"></div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-4 col-6">
                        <div class="text-center border-end">
                            <h5 class="fw-bold mb-0"> <?php echo $conteosPonencias['ponencias_pendientes']; ?></h5>
                            <span class="text-uppercase">Ponencia(s) pendiente(s)</span>
                            <div class="mt-3">
                                <span class="text-info mt-3">
                                    <i class="bi bi-hourglass-split"></i>
                                    <?php echo $conteosPonencias['porcentaje_pendientes'] ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="text-center border-end">
                            <h5 class="fw-bold mb-0"> <?php echo $conteosPonencias['ponencias_aceptadas']; ?></h5>
                            <span class="text-uppercase">Ponencia(s) aceptada(s)</span>
                            <div class="mt-3">
                                <span class="text-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <?php echo $conteosPonencias['porcentaje_aceptadas']; ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="text-center">
                            <h5 class="fw-bold mb-0"><?php echo $conteosPonencias['ponencias_rechazadas']; ?></h5>
                            <span class="text-uppercase">Ponencia(s) rechazada(s)</span>
                            <div class="mt-3">
                                <span class="text-danger">
                                    <i class="bi bi-x-circle-fill"></i>
                                    <?php echo $conteosPonencias['porcentaje_rechazadas']; ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $.getJSON("<?= base_url('js/apex_es.json') ?>", function (data) {
        var idiomaEspaniol = data
        var options = {
            series: [
                {
                    data: [0<?php echo $conteosPonencias['ponencias_pendientes']; ?>, 0<?php echo $conteosPonencias['ponencias_aceptadas']; ?>, 0<?php echo $conteosPonencias['ponencias_rechazadas']; ?>]
                }
            ],
            chart: {
                height: 350,
                type: 'bar',
                locales: [idiomaEspaniol],
                defaultLocale: 'es',
                events: {
                    click: function (chart, w, e) {
                        // console.log(chart, w, e)
                    }
                }
            },
            colors: ['#0d6efd', '#28a745', '#dc3545'],
            plotOptions: {
                bar: {
                    columnWidth: '20%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: [
                    'Ponencias Pendientes',
                    'Ponencias Aceptadas',
                    'Ponencias Rechazadas'
                ],
                labels: {
                    style: {
                        colors: ['#000'],
                        fontSize: '12px'
                    }
                }
            }
        };

        const graficaConteos = new ApexCharts(
            document.querySelector("#sales-chart"),
            options,
        );
        graficaConteos.render();
    });
</script>
<style>
    .text-info {
        color: #0d6efd !important;
    }
</style>
<?= $this->endSection() ?>