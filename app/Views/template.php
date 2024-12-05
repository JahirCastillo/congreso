<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    $config = config('App\Config\MiConfiguracion');
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= base_url('./images/favicon.png'); ?>" type="image/png">
    <title><?= esc($config->nombreSistema) ?></title>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/Adminlte/adminlte.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('./css/bootstrap-icons.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('./css/custom.css'); ?>">
    <link
        href="https://cdn.datatables.net/v/bs5/dt-2.1.8/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/r-3.0.3/datatables.min.css"
        rel="stylesheet">


    <script src="<?= base_url('js/jquery-3.7.1.min.js'); ?>"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/dt-2.1.8/b-3.1.2/b-html5-3.1.2/b-print-3.1.2/r-3.0.3/datatables.min.js"></script>
    <script src="<?= base_url('./js/utilerias.js'); ?>"></script>
    <script src="<?= base_url('js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('js/adminlte.js') ?>"></script>

    <script src="<?= base_url('js/apexcharts.min.js') ?>"></script>
    <script>
        var base_url = "<?= base_url(); ?>";

    </script>
</head>


<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body"> <!--begin::Container-->
            <div class="container-fluid"> <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i
                                class="bi bi-list"></i> </a> </li>
                </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto"> <!--begin::Navbar Search-->
                    <li class="nav-item dropdown"> <button
                            class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                            id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                            data-bs-display="static" aria-label="Toggle theme (light)"> <span class="theme-icon-active">
                                <i class="bi bi-sun-fill me-2"></i> </span> <span class="d-lg-none ms-2"
                                id="bd-theme-text">Toggle theme</span> </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text"
                            style="--bs-dropdown-min-width: 8rem;">
                            <li> <button type="button" class="dropdown-item d-flex align-items-center active"
                                    data-bs-theme-value="light" aria-pressed="true"> <i class="bi bi-sun-fill me-2"></i>
                                    Claro
                                    <i class="bi bi-check-lg ms-auto d-none"></i> </button> </li>
                            <li> <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="dark" aria-pressed="false"> <i
                                        class="bi bi-moon-fill me-2"></i>
                                    Oscuro
                                    <i class="bi bi-check-lg ms-auto d-none"></i> </button> </li>
                            <li> <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="auto" aria-pressed="false"> <i
                                        class="bi bi-circle-fill-half-stroke me-2"></i>
                                    Auto
                                    <i class="bi bi-check-lg ms-auto d-none"></i> </button> </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                        </a>
                    </li> <!--end::Fullscreen Toggle--> <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu"> <a href="#" class="nav-link dropdown-toggle nombreUsuario"
                            data-bs-toggle="dropdown"> <img src="<?= base_url('images/perfil.png'); ?>"
                                class="user-image rounded-circle shadow" alt="User Image"> <span
                                class="d-none d-md-inline"><?php echo session('nombre'); ?></span> </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <!--begin::User Image-->
                            <li class="user-header text-bg-primary">
                                <p>
                                    <?= session('nombre'); ?>
                                    <small></small>
                                </p>
                            </li> <!--end::User Image--> <!--begin::Menu Body-->
                            <li class="user-body"> <!--begin::Row-->
                                <div class="row">
                                    <div class="col-3 text-center"></div>
                                    <div class="col-3 text-center"></div>
                                    <div class="col-6 text-center">
                                        <a href="<?= base_url('index.php/login/destruirSesion'); ?>">Cerrar sesión</a>
                                    </div>
                                </div> <!--end::Row-->
                            </li> <!--end::Menu Body--> <!--begin::Menu Footer-->
                        </ul>
                    </li> <!--end::User Menu Dropdown-->
                </ul> <!--end::End Navbar Links-->
            </div> <!--end::Container-->
        </nav> <!--end::Header--> <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <a class="brand-link">
                    <span class="brand-text fw-light"
                        title="Sistema de consulta de perfil de ingreso"><strong><?= esc($config->nombreSistema) ?></strong></span>
                </a>
                <!--end::Brand Link-->
            </div> <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2"> <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/inicio'); ?>" class="nav-link">
                                <i class="bi bi-house-door me-1"></i> <span> Inicio</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/convocatorias'); ?>" class="nav-link">
                                <i class="bi bi-megaphone me-1"></i> <span> Convocatorias</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/itinerarios'); ?>" class="nav-link">
                                <i class="bi bi-calendar-event me-1"></i> <span> Itinerarios</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/investigadores'); ?>" class="nav-link">
                                <i class="bi bi-search me-1"></i> <span> Investigadores</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/revisores'); ?>" class="nav-link">
                                <i class="bi bi-check-circle me-1"></i> <span> Revisores</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php/usuarios'); ?>" class="nav-link">
                                <i class="bi bi-people me-1"></i> <span> Usuarios del sistema</span>
                            </a>
                        </li>

                    </ul> <!--end::Sidebar Menu-->
                </nav>
            </div> <!--end::Sidebar Wrapper-->
        </aside> <!--end::Sidebar--> <!--begin::App Main-->
        <main class="app-main"> <!--begin::App Content Header-->
            <div class="app-content-header"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-12">
                        </div>
                    </div> <!--end::Row-->
                </div>
            </div>
            <div class="app-content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </main>
        <footer class="app-footer"> <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Anything you want</div> <!--end::To the end-->
            <!--begin::Copyright--> <strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer> <!--end::Footer-->
    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
</body><!--end::Body-->

</html>