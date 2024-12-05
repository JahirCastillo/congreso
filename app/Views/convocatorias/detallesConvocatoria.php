<form id="formularioConvocatoria" class="needs-validation" novalidate>
    <input type="hidden" id="id" name="id" value="<?= $detallesConvocatoria['id'] ?? ''; ?>">

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre"
                value="<?= $detallesConvocatoria['nombre'] ?? ''; ?>" required>
            <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                value="<?= $detallesConvocatoria['fecha_inicio'] ?? ''; ?>" required>
            <div class="invalid-feedback">Por favor, ingrese la fecha.</div>
        </div>
        <div class="col-md-6">
            <label for="fecha_fin" class="form-label">Fecha Fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                value="<?= $detallesConvocatoria['fecha_fin'] ?? ''; ?>" required>
            <div class="invalid-feedback">Por favor, ingrese la fecha.</div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="fecha_inicio_recepcion_documentos" class="form-label">Fecha Inicio Recepción de
                Documentos.</label>
            <input type="date" class="form-control" id="fecha_inicio_recepcion_documentos" name="fecha_inicio_recepcion_documentos"
                value="<?= $detallesConvocatoria['fecha_inicio_recepcion_documentos'] ?? ''; ?>" required>
            <div class="invalid-feedback">Por favor, ingrese la fecha.</div>
        </div>
        <div class="col-md-6">
            <label for="fecha_limite_documentos" class="form-label">Fecha Límite de Documentos</label>
            <input type="date" class="form-control" id="fecha_limite_documentos" name="fecha_limite_documentos"
                value="<?= $detallesConvocatoria['fecha_limite_documentos'] ?? ''; ?>" required>
            <div class="invalid-feedback">Por favor, ingrese la fecha.</div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea id="descripcion" class="form-control"
                name="descripcion"><?= $detallesConvocatoria['descripcion'] ?? ''; ?></textarea>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                value="<?= $detallesConvocatoria['ubicacion'] ?? ''; ?>" required>
            <div class="invalid-feedback">Por favor, ingrese la ubicación.</div>
        </div>
        <div class="col-md-6">
            <label for="estatus" class="form-label">Estatus</label>
            <select id="estatus" class="form-control" name="estatus" required>
                <option value="A" <?= ($detallesConvocatoria['estatus'] ?? '') == 'A' ? 'selected' : ''; ?>>Activo</option>
                <option value="I" <?= ($detallesConvocatoria['estatus'] ?? '') == 'I' ? 'selected' : ''; ?>>Inactivo
                </option>
            </select>
            <div class="invalid-feedback">Por favor, seleccione un estatus.</div>
        </div>
    </div>
</form>
<div class="contenedorErrores hide"></div>


<script src="<?= base_url('js/summernote/summernote-es-ES.min.js') ?>"></script>

<script>
    $(document).ready(function () {
        // Inicializar Summernote en el campo de descripción
        $('#descripcion').summernote({
            placeholder: 'Ingrese el texto de la convocatoria',
            tabsize: 2,
            lang: 'es-ES',
            height: 120
        });
    });
</script>