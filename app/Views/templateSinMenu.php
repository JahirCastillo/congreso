<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    $config = config('App\Config\MiConfiguracion');
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./images/favicon.png" type="image/png">
    <title><?= esc($config->nombreSistema)?></title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/Adminlte/adminlte.min.css'); ?>">
    <link rel="stylesheet" href="./css/bootstrap-icons.min.css">
    <script src="./js/jquery-3.7.1.min.js"></script>
    <script src="./js/utilerias.js"></script>
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .bg-primary {
            background-color: #18529D !important;
        }

        .btn-info {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-info:hover {
            color: #fff;
            background-color: #138496;
            border-color: #117a8b;
        }
    </style>
    <script>
        var base_url="<?= base_url(); ?>";
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                Sistema de consulta de perfil de ingreso
            </a>
        </div>
    </nav>

    <div class="container-fluid h-100">
        <div class="row h-100">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>