<?= $this->extend('templatePonentes'); ?>
<?= $this->section('content'); ?>
<?php $nombrePonente = session('nombrePonente'); ?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <h2 class="text-center text-primary mt-3" id="tituloVentana">Registrar Nueva Ponencia</h2>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('ponencias/guardar') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="po_id_ponencia" value="<?= $idPonencia; ?>">
                            <!-- Título -->
                            <div class="mb-3">
                                <label for="po_titulo" class="form-label">Título de la Ponencia</label>
                                <input type="text" name="po_titulo" id="po_titulo" class="form-control"
                                    value="<?= $ponencia['po_titulo']; ?>" required>
                            </div>

                            <!-- Temática -->
                            <div class="mb-3">
                                <label for="po_id_tematica" class="form-label">Temática</label>
                                <select name="po_id_tematica" id="po_id_tematica" class="form-control" required>
                                    <option value="">Seleccione una temática</option>
                                    <?php foreach ($tematicas as $tematica): ?>
                                        <option value="<?= $tematica['id_tematica']; ?>"><?= $tematica['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Subtemática -->
                            <div class="mb-3">
                                <label for="po_id_subtematica" class="form-label">Subtemática</label>
                                <select name="po_id_subtematica" id="po_id_subtematica" class="form-control">
                                    <option value="">Seleccione una subtemática</option>
                                    <!-- Opciones dinámicas cargadas con JavaScript -->
                                </select>
                            </div>

                            <!-- Palabras Clave -->
                            <div class="mb-3">
                                <label for="po_palabrasclave" class="form-label">Palabras Clave</label>
                                <input type="text" name="po_palabrasclave" id="po_palabrasclave" class="form-control"
                                    placeholder="Separar por comas" value="<?= $ponencia['po_palabrasclave']; ?>"
                                    required>
                            </div>
                            <div class="mb-5" id="contenedorSubirArchivo">
                                <label for="archivo" class="form-label">Subir Archivo</label>
                                <input type="file" name="archivo" id="archivo" class="form-control" accept=".pdf,.tex"
                                    required>
                                <small class="text-muted">Solo se permiten archivos PDF o LaTeX (.tex).</small>
                            </div>

                            <div class="mb-3">
                                <button type="button" class="btn btn-success" id="agregarAutor">Agregar
                                    coautores</button>

                                <script>
                                    let autorIndex = 0; // Contador para asignar índices únicos a las filas.
                                    document.getElementById('agregarAutor').addEventListener('click', function () {
                                        const table = document.querySelector('.table tbody');
                                        const row = document.createElement('tr');
                                        row.innerHTML = `
                                            <td><input type="text" name="autores[${autorIndex}][aut_nombre]" class="form-control" required></td>
                                            <td><input type="email" name="autores[${autorIndex}][aut_email]" class="form-control" required></td>
                                            <td><button type="button" class="btn btn-danger removerAutor">Eliminar</button></td>
                                        `;
                                        table.appendChild(row);
                                        autorIndex++;
                                        row.querySelector('.removerAutor').addEventListener('click', function () {
                                            row.remove();
                                        });
                                    });
                                </script>
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title
                                            text-primary">Coautores</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Correo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (isset($autores)): ?>
                                                    <?php foreach ($autores as $index => $autor): ?>
                                                        <tr>
                                                            <td><input type="text" name="autores[<?= $index ?>][aut_nombre]"
                                                                    class="form-control" value="<?= $autor['aut_nombre'] ?>"
                                                                    required>
                                                            </td>
                                                            <td><input type="email" name="autores[<?= $index ?>][aut_email]"
                                                                    class="form-control" value="<?= $autor['aut_email'] ?>"
                                                                    required>
                                                            </td>
                                                            <td><button type="button"
                                                                    class="btn btn-danger removerAutor">Eliminar</button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="po_resumen" class="form-label">Resumen</label>
                                <textarea name="po_resumen" id="po_resumen" class="form-control"
                                    required><?= $ponencia['po_resumen']; ?></textarea>
                            </div>

                            <div class="text-center contenedorBotones">
                                <button type="submit" class="btn btn-primary" id="btnGuardarPonencia">
                                    <i class="fas fa-save"></i> Guardar Ponencia
                                </button>
                                <a href="<?= site_url('ponencias') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Regresar
                                </a>
                            </div>
                        </form>
                    </div>
                    <?php if ($ponencia['po_estatus'] == 'R'): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title
                                        text-primary">Volver a subir documentos</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-danger">El revisor ha rechazado el documento. Por favor, súbalo
                                    nuevamente.</p>
                                <form action="<?= site_url('ponencias/actualizarArchivo') ?>" method="post"
                                    enctype="multipart/form-data">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="po_id_ponencia" value="<?= $idPonencia; ?>"
                                        class="habilitar">
                                    <input type="hidden" name="po_revisiones" value="<?= $ponencia['po_revisiones']; ?>"
                                        class="habilitar">
                                    <div class="mb-3">
                                        <label for="archivo" class="form-label">Subir Archivo</label>
                                        <input type="file" name="archivo" id="archivo" class="form-control habilitar"
                                            accept=".pdf,.tex" required>
                                        <small class="text-muted">Solo se permiten archivos PDF o LaTeX
                                            (.tex).</small>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary habilitar">
                                            <i class="fas fa-upload"></i> Subir Archivo
                                        </button>
                                        <a href="<?= site_url('ponencias') ?>" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Regresar
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php elseif ($ponencia['po_estatus'] == 'P'): ?>
                        <div class="alert alert-info" role="alert">
                            La ponencia se encuentra en revisión. No puede realizar cambios en este momento.
                        </div>
                        <a href="<?= site_url('ponencias') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Regresar
                        </a>
                    <?php elseif ($ponencia['po_estatus'] == 'A'): ?>
                        <div class="alert alert-success" role="alert">
                            La ponencia ha sido aceptada. ¡Felicidades! Se tiene que presentar el [fecha] a las [hora].
                        </div>
                        <div class="text-center mt-3"></div>
                        <button type="button" class="btn btn-info"
                            onclick="imprimirConstancia('<?= $nombrePonente; ?>','<?= $ponencia['po_titulo'] ?>')">
                            <i class="fas fa-print"></i> Imprimir constancia
                        </button>
                        <script>
                            $(document).ready(function () {
                                $('.table tbody tr').each(function () {
                                    const printButton = $('<button type="button" class="btn btn-info">Imprimir constancia</button>');
                                    $(this).find('td:last').append(printButton);
                                    printButton.on('click', function () {
                                        imprimirConstancia($(this).closest('tr').find('td:first').find('input').val(), $('#po_titulo').val());
                                    });
                                });
                            });
                            function imprimirConstancia(nombre,titulo){
                                alert(`Imprimiendo constancia para ${nombre} con el título ${titulo}`);
                            }
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    var tematica = 0<?= $ponencia['po_id_tematica'] ?>;
    var subtematica = 0<?= $ponencia['po_id_subtematica'] ?>;
    $(document).ready(function () {
        if (tematica > 0) {
            $('#po_id_tematica').val(tematica);
            let select = document.getElementById('po_id_tematica');
            let eventoChange = new Event('change');
            select.dispatchEvent(eventoChange);
            $('input, select, textarea, button[type="submit"]').prop('disabled', true);
            $('#agregarAutor').remove();
            $('.removerAutor,.contenedorBotones').remove();
            $('#btnGuardarPonencia').remove();
            $('#contenedorSubirArchivo').remove();
            $('#tituloVentana').text('Ver Ponencia');
            $('.habilitar').prop('disabled', false);
        }
    });
    document.getElementById('po_id_tematica').addEventListener('change', function () {
        const tematicaId = this.value;
        const subtematicaSelect = document.getElementById('po_id_subtematica');

        subtematicaSelect.innerHTML = '<option value="">Cargando...</option>';

        fetch(`<?= site_url('ponencias/subtematicas/') ?>${tematicaId}`)
            .then(response => response.json())
            .then(data => {
                subtematicaSelect.innerHTML = '<option value="">Seleccione una subtemática</option>';
                data.forEach(subtematica => {
                    subtematicaSelect.innerHTML += `<option value="${subtematica.id_subtematica}">${subtematica.nombre}</option>`;
                });
                if (subtematica > 0) {
                    $('#po_id_subtematica').val(subtematica);
                }
            })
            .catch(error => {
                console.error('Error al cargar subtemáticas:', error);
                subtematicaSelect.innerHTML = '<option value="">Seleccione una temática primero</option>';
            });
    });
</script>
<?= $this->endSection(); ?>