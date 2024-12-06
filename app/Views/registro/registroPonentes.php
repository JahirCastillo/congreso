<?= $this->extend('templateSinMenu'); ?>
<?= $this->section('content'); ?>
<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <h1 class="text-center text-primary mt-3">Registro de Ponentes</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white text-center">
                            <h5 class="card-title mb-0">Complete los campos para registrarse</h5>
                        </div>
                        <div class="card-body">
                            <form id="formRegistroPonentes" class="needs-validation" novalidate>
                                <!-- Nombre -->
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre completo</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        placeholder="Ingrese su nombre completo" required>
                                    <div class="invalid-feedback">Por favor, ingrese su nombre.</div>
                                </div>

                                <!-- Correo -->
                                <div class="mb-3">
                                    <label for="correo" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="correo" name="correo"
                                        placeholder="Ingrese su correo electrónico" required>
                                    <div class="invalid-feedback">Por favor, ingrese un correo válido.</div>
                                </div>

                                <!-- Contraseña -->
                                <div class="mb-3">
                                    <label for="contrasena" class="form-label">Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="contrasena" name="contrasena"
                                            placeholder="Ingrese una contraseña segura" required>
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="bi-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">
                                        La contraseña debe tener al menos 6 caracteres, incluir una letra mayúscula, una
                                        minúscula y un número.
                                    </small>
                                    <div class="invalid-feedback">Por favor, ingrese una contraseña válida.</div>
                                </div>

                                <!-- Repite Contraseña -->
                                <div class="mb-3">
                                    <label for="repiteContrasena" class="form-label">Repite la Contraseña</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="repiteContrasena"
                                            name="repiteContrasena" placeholder="Repita la contraseña" required>
                                        <button type="button" class="btn btn-outline-secondary"
                                            id="toggleConfirmaContrasena">
                                            <i class="bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                                </div>

                                <!-- Institución -->
                                <div class="mb-3">
                                    <label for="institucion" class="form-label">Institución</label>
                                    <input type="text" class="form-control" id="institucion" name="institucion"
                                        placeholder="Ingrese el nombre de su institución" required>
                                    <div class="invalid-feedback">Por favor, ingrese su institución.</div>
                                </div>

                                <!-- País -->
                                <div class="mb-3">
                                    <label for="pais" class="form-label">País</label>
                                    <select class="form-select" id="pais" name="pais" required>
                                        <option value="" selected disabled>Seleccione su país</option>
                                        <option value="México">México</option>
                                        <option value="Estados Unidos">Estados Unidos</option>
                                        <option value="España">España</option>
                                        <option value="Argentina">Argentina</option>
                                        <option value="Colombia">Colombia</option>
                                        <!-- Agregar más países según necesidad -->
                                    </select>
                                    <div class="invalid-feedback">Por favor, seleccione su país.</div>
                                </div>
                                <div class="contenedorErrores hide"></div>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary" id="btnGuardarRegistro">
                                        <i class="fas fa-user-plus me-2"></i>Registrarse
                                    </button>
                                </div>
                                <div class="d-grid mt-2">
                                    <a href="<?= base_url('registro'); ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Nota -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            ¿Ya tiene una cuenta? <a href="<?= base_url('registro'); ?>">Inicie sesión aquí</a>.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('document').ready(function () {
        $('#togglePassword').on('click', function () {
            let contrasena = $('#contrasena');
            let tipoCampoContrasena = contrasena.attr('type') === 'password' ? 'text' : 'password';
            contrasena.attr('type', tipoCampoContrasena);
            $(this).toggleClass('bi-eye bi-eye-slash');
        });
        $('#toggleConfirmaContrasena').on('click', function () {
            let contrasena = $('#repiteContrasena');
            let tipoCampoContrasena = contrasena.attr('type') === 'password' ? 'text' : 'password';
            contrasena.attr('type', tipoCampoContrasena);
            $(this).toggleClass('bi-eye bi-eye-slash');
        });

        $("#btnGuardarRegistro").click(function (e) {
            e.preventDefault();
            let form = $('#formRegistroPonentes')[0];
            let contrasena = $('#contrasena').val();
            let confirmContrasena = $('#repiteContrasena').val();
            let expresionRegularContrasena = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/;
            if (!expresionRegularContrasena.test(contrasena)) {
                pintaErrores('.contenedorErrores', 'La contraseña debe tener al menos 6 caracteres, incluir una letra mayúscula, una minúscula y un número.');
                return;
            }

            if (contrasena !== confirmContrasena) {
                pintaErrores('.contenedorErrores', 'Las contraseñas no coinciden');
                return;
            }

            if (!form.checkValidity()) {
                $(form).addClass('was-validated');
            } else {
                $('.contenedorErrores').hide();
                let datos = $('#formRegistroPonentes').serialize();
                getObject('registro/guardaRegistro', datos, function (response) {
                    if (response.esValido==true) {
                        window.location.href = '<?= base_url('ponencias'); ?>';
                    } else {
                        pintaErrores('.contenedorErrores', response.mensaje);
                    }
                });
            }
        });
    });
    function pintaErrores(selector, texto) {
        $(selector)
            .html(texto)
            .css({
                'display': 'block',
                'background-color': '#f8d7da',
                'color': '#721c24',
                'padding': '10px',
                'border-radius': '5px',
                'margin-top': '10px',
            });
    }
</script>

<?= $this->endSection(); ?>