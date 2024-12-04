<?= $this->extend('templateSinMenu') ?>
<?= $this->section('content') ?>

<style>
    .image-side {
        background: url('./images/login.jpeg') no-repeat center center;
        background-size: cover;
    }

    .form-side {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-form {
        width: 80%;
        max-width: 400px;
    }

    .logolis {
        width: 150px;
    }

    .iconoPassword {
        top: 25%;
        right: 10px;
        cursor: pointer;
    }
</style>

<div class="container-fluid h-100">
    <div class="row h-100">
        <!-- Left Side: Image -->
        <div class="col-md-6 d-none d-md-block image-side"></div>

        <!-- Right Side: Login Form -->
        <div class="col-md-6 form-side">
            <div class="login-form">
                <div class="text-center">
                    <img src="https://planea.uv.mx/planea/img/lis.png" alt="Logo" class="logo logolis">
                </div>
                <div class="mt-5">
                    <h3 class="text-center mb-4">Inicio de sesión</h3>
                </div>
                <div class="text-center mt-3 mb-5">
                    <small>Ahora ingrese con su cuenta institucional</small> <br> <small> tal como ingresa usted a
                        MiUV
                    </small>
                </div>
                <span class="badge rounded-pill text-bg-info mt-3 mb-3" id="msg_acceso"></span>
                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger">
                        <?= $validation->listErrors() ?>
                    </div>
                <?php endif; ?>
                <form id="formAcceso" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                        <div class="invalid-feedback">Por favor rellena el campo.</div>
                    </div>
                    <div class="mb-3 position-relative">
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Contraseña" required>
                        <div class="invalid-feedback">Por favor rellena el campo.</div>
                        <i class="bi bi-eye position-absolute iconoPassword" id="togglePassword"></i>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Recordarme</label>
                    </div>
                    <button type="button" id="btnIniciarSesion" class="btn btn-info w-100">Iniciar sesión</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#msg_acceso').hide();
        // Recuperar el email almacenado en localStorage al cargar la página
        const storedUsuario = localStorage.getItem('usuario');
        if (storedUsuario) {
            $('#usuario').val(storedUsuario);
            $('#rememberMe').prop('checked', true); // Marcar el checkbox si había un email guardado
        }
        $('#togglePassword').on('click', function () {
            // Alternar el tipo de input entre password y text
            const passwordField = $('#password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).toggleClass('bi-eye bi-eye-slash');
        });


        $("#btnIniciarSesion").click(function (e) {
            e.preventDefault();
            const form = $('#formAcceso')[0];
            if (!form.checkValidity()) {
                $(form).addClass('was-validated');
            } else {
                let usuario = $("#usuario").val();
                let password = $("#password").val();
                mostrarCargando();
                getObject('login/validaUsuario', {
                    usuario: usuario,
                    password: password
                }, function (data) {
                    if (data.IsValid == true) {
                        if ($('#rememberMe').is(':checked')) {
                            localStorage.setItem('usuario', usuario);
                        } else {
                            localStorage.removeItem('usuario');
                        }
                        redirectByPost('login/ponensesion', {
                            'usuario': usuario
                        }, false);
                    } else {
                        if (data.IsValid == false) {
                            $('#msg_acceso').show();
                            $('#msg_acceso').text(data.mensaje);
                        }
                    }
                    ocultarCargando();
                });
            }
        });

        $(document).keydown(function (tecla) {
            if (tecla.keyCode == 13) {
                $("#btnIniciarSesion").click();
            }
        });
    });
</script>
<?= $this->endSection() ?>