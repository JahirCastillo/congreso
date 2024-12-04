<form id="formularioUsuario" class="row g-3 needs-validation" novalidate>
    <!-- ID (hidden) -->
    <input type="hidden" id="id" name="id" value="<?= $datosUsuario['id'] ?>">

    <!-- Login -->
    <div class="col-md-6">
        <label for="login" class="form-label">Usuario</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" id="login" name="login" class="form-control"
                value="<?= htmlspecialchars($datosUsuario['login']) ?>" required>
            <div class="invalid-feedback">Por favor, ingresa el usuario.</div>
        </div>
    </div>

    <!-- Contraseña -->
    <div class="col-md-6">
        <label for="password" class="form-label">Contraseña</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-lock"></i>
            </span>
            <input type="password" class="form-control" id="password" name="password"
                placeholder="Ingrese su contraseña" required minlength="6">
            <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                <i class="bi bi-eye"></i> <!-- Icono de mostrar/ocultar -->
            </button>
            <div class="invalid-feedback">
                Por favor, ingrese una contraseña válida (mínimo 6 caracteres).
            </div>
        </div>
    </div>
    <!-- Nombre -->
    <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
            <input type="text" id="nombre" name="nombre" class="form-control"
                value="<?= htmlspecialchars($datosUsuario['nombre']) ?>" required>
            <div class="invalid-feedback">Por favor, ingresa el nombre.</div>
        </div>
    </div>

    <!-- Apellido Paterno -->
    <div class="col-md-6">
        <label for="apaterno" class="form-label">Apellido Paterno</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" id="apaterno" name="apaterno" class="form-control"
                value="<?= htmlspecialchars($datosUsuario['apaterno']) ?>" required>
            <div class="invalid-feedback">Por favor, ingresa el apellido.</div>
        </div>
    </div>

    <!-- Apellido Materno -->
    <div class="col-md-6">
        <label for="amaterno" class="form-label">Apellido Materno</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
            <input type="text" id="amaterno" name="amaterno" class="form-control"
                value="<?= htmlspecialchars($datosUsuario['amaterno']) ?>">
        </div>
    </div>

    <!-- Correo -->
    <div class="col-md-6">
        <label for="correo" class="form-label">Correo Electrónico</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" id="correo" name="correo" class="form-control"
                value="<?= htmlspecialchars($datosUsuario['correo']) ?>">
            <div class="invalid-feedback">Por favor, ingresa un correo válido.</div>
        </div>
    </div>

    <!-- Rol -->
    <div class="col-md-6">
        <label for="rol" class="form-label">Rol</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-gear"></i></span>
            <select id="rol" name="rol" class="form-select" required>
                <option value="">Selecciona un rol</option>
                <?php if (isset($roles)): ?>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol['id'] ?>" <?= $datosUsuario['rol'] == $rol['id'] ? 'selected' : '' ?>>
                            <?= $rol['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <div class="invalid-feedback">Por favor, selecciona un rol.</div>
        </div>
    </div>

    <!-- Estatus de Cuenta -->
    <div class="col-md-6">
        <label for="estatusCuenta" class="form-label">Estatus de la Cuenta</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-toggle-on"></i></span>
            <select id="estatusCuenta" name="estatusCuenta" class="form-select" required>
                <option value="1" <?= $datosUsuario['estatusCuenta'] == 1 ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= $datosUsuario['estatusCuenta'] == 0 ? 'selected' : '' ?>>Inactivo</option>
            </select>
            <div class="invalid-feedback">Por favor, selecciona el estatus de la cuenta.</div>
        </div>
    </div>

    <div id="permisosContainer" class="mt-3">
        <h6 class="d-none" id="tituloPermisosRol">Permisos del Rol:</h6>
        <div id="contenedorTarjetasPermisos" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <!-- Las cards se cargarán aquí -->
        </div>
    </div>


    <script>
        $(document).ready(function () {
            var $button = $('#btnGuardarCambios');
            var $form = $('#formularioUsuario');

            // Maneja el evento de clic en el botón
            $button.on('click', function () {
                if ($form[0].checkValidity()) {
                    getObject('usuarios/actualizaUsuario', $form.serialize(), function (response) {
                        if (response.estatus === 'ok') {
                            // Actualizar la tabla
                            $('#tablaUsuarios').DataTable().ajax.reload();
                            // Cerrar el modal
                            $('#modalDetallesUsuario').modal('hide');
                        } else {
                            alert('Error al actualizar el usuario.');
                        }
                    });
                } else {
                    $form.addClass('was-validated');
                }
            });
            $('#togglePassword').on('click', function () {
                let campoPassword = $('#password');
                let tipo = campoPassword.attr('type') === 'password' ? 'text' : 'password';
                campoPassword.attr('type', tipo);

                // Cambia el icono del botón
                $(this).find('i').toggleClass('bi-eye bi-eye-slash');
            });

            $('#rol').change(function () {
                let rol = $(this).val();
                console.log(rol);
                if (rol != 1 && rol != '') {
                    getObject('usuarios/getPermisosRol', { rol: rol }, function (response) {
                        if (response.length === 0) {
                            $('#contenedorTarjetasPermisos').append('<p>No hay permisos disponibles para este rol.</p>');
                            return;
                        }
                        $('#contenedorTarjetasPermisos').empty();
                        $('#tituloPermisosRol').removeClass('d-none');
                        response.forEach(modulo => {
                            let cardHTML = `
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">${modulo.nombre}</h5>
                                    </div>
                                    <div class="card-body p-2">
                                        <ul class="list-group list-group-flush">
                            `;

                            modulo.permisos.forEach(permiso => {
                                let permisoHTML = `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            ${permiso.nombre}
                                        </span>
                                `;

                                // Verificar si el permiso está habilitado
                                if (permiso.tiene_permiso === "1") {
                                    permisoHTML += `<i class="bi bi-check-circle-fill text-success"></i>`;
                                }

                                permisoHTML += `</li>`;
                                cardHTML += permisoHTML;
                            });

                            cardHTML += `
                                        </ul>
                                    </div>
                                </div>
                            `;
                            // Insertar la tarjeta generada en el contenedor
                            $('#contenedorTarjetasPermisos').append(cardHTML);
                        });

                    });
                } else {
                    $('#contenedorTarjetasPermisos').empty();
                    $('#tituloPermisosRol').addClass('d-none');
                }
            });
        });
    </script>