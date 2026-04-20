<?php $css_files = ['form-header']; require_once APP_PATH . '/Views/layouts/header.php'; ?>

<body class="simple-layout">

<?php require_once APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="app-main">
    <div class="form-header-card">
        <div class="form-header-top">
            <div class="form-logo">
                <img src="<?php echo asset_url('img/logo-pollo-fiesta.png'); ?>" alt="Pollo Fiesta" class="logo-img">
            </div>
            <div class="form-header-content">
                <h1 class="form-main-title">FD GH-03 INFORME DE NOVEDADES DIARIAS DE PERSONAL POLLO FIESTA</h1>
                <div class="form-metadata">
                    <span><strong>Fecha de emisión:</strong> 01/06/2022</span>
                    <span><strong>Fecha de actualización:</strong> N.A.</span>
                    <span><strong>Versión:</strong> 01</span>
                    <span><strong>Código:</strong> FD GH-03</span>
                </div>
                <p class="form-description">Formulario de reporte de novedades diarias.</p>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-error">
            <span class="alert-icon">⚠️</span>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="form-container">
        <form action="<?php echo base_url('novedades/guardar'); ?>" method="POST" enctype="multipart/form-data" class="modern-form">
            <!-- Información Personal -->
            <div class="form-section">
                <div class="section-title">
                    <span class="section-icon"></span>
                    <h2>Información Personal</h2>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label class="required">1. Nombres y Apellidos Completos</label>
                        <input type="text" name="nombres_apellidos" required placeholder="Escriba su respuesta">
                    </div>
                    <div class="form-field">
                        <label class="required">2. Número de Cédula</label>
                        <input type="number" name="numero_cedula" required placeholder="Escriba su respuesta" min="0" step="1" pattern="[0-9]*">
                    </div>
                </div>
            </div>

            <!-- Información Laboral -->
            <div class="form-section">
                <div class="section-title">
                    <span class="section-icon"></span>
                    <h2>Información Laboral</h2>
                </div>

                <div class="form-row">
                    <?php if (count($sedes) === 1): ?>
                        <!-- Si solo tiene 1 sede, guardar como hidden (no mostrar nada) -->
                        <input type="hidden" name="sede" id="sede" value="<?php echo htmlspecialchars($sedes[0]['nombre']); ?>" data-sede-id="<?php echo $sedes[0]['id']; ?>">
                    <?php else: ?>
                        <!-- Si tiene múltiples sedes, mostrar selector -->
                        <div class="form-field">
                            <label class="required">3. Sede</label>
                            <select name="sede" id="sede" required onchange="cargarAreasPorSede()">
                                <option value="">Selecciona la respuesta</option>
                                <?php foreach ($sedes as $sede): ?>
                                    <option value="<?php echo htmlspecialchars($sede['nombre']); ?>" data-sede-id="<?php echo $sede['id']; ?>">
                                        <?php echo htmlspecialchars($sede['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-field">
                        <label class="required"><?php echo count($sedes) === 1 ? '3' : '4'; ?>. ÁREA DE TRABAJO</label>
                        <select name="area_trabajo" id="area_trabajo" required>
                            <option value="">Primero selecciona una sede</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Detalles de la Novedad -->
            <div class="form-section">
                <div class="section-title">
                    <span class="section-icon"></span>
                    <h2>Detalles de la Novedad</h2>
                </div>
                
                <div class="form-row">
                    <div class="form-field">
                        <label class="required"><?php echo count($sedes) === 1 ? '4' : '5'; ?>. FECHA NOVEDAD</label>
                        <input type="date" name="fecha_novedad" required placeholder="dd/mm/aaaa">
                    </div>
                    <div class="form-field">
                        <label class="required"><?php echo count($sedes) === 1 ? '5' : '6'; ?>. TURNO</label>
                        <select name="turno" required>
                            <option value="">Selecciona la respuesta</option>
                            <option value="DÍA">DÍA</option>
                            <option value="NOCHE">NOCHE</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label class="required"><?php echo count($sedes) === 1 ? '6' : '7'; ?>. NOVEDAD</label>
                        <select name="novedad" required>
                            <option value="">Selecciona la respuesta</option>
                            <?php foreach ($tipos_novedad as $tipo): ?>
                                <option value="<?php echo htmlspecialchars($tipo['nombre']); ?>">
                                    <?php echo htmlspecialchars($tipo['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-field">
                        <label class="required"><?php echo count($sedes) === 1 ? '7' : '8'; ?>. JUSTIFICACIÓN</label>
                        <div class="radio-group-inline">
                            <label class="radio-label">
                                <input type="radio" name="justificacion" value="SI" required onchange="handleJustificacionChange()">
                                <span>SI</span>
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="justificacion" value="NO" required onchange="handleJustificacionChange()">
                                <span>NO</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentación -->
            <div class="form-section" id="documentacion-section">
                <div class="section-title">
                    <span class="section-icon"></span>
                    <h2>Documentación de Soporte</h2>
                </div>

                <div class="form-field">
                    <label class="required" id="label-adjuntar"><?php echo count($sedes) === 1 ? '8' : '9'; ?>. ADJUNTAR SOPORTE</label>
                    <div class="file-upload-area">
                        <input type="file" name="archivos[]" id="archivos" multiple accept=".doc,.docx,.pdf,image/*" required>
                        <label for="archivos" class="file-upload-label">
                            <span class="upload-icon"></span>
                            <span class="upload-text">Haz clic o arrastra archivos aquí</span>
                            <span class="upload-hint">Máximo 3 archivos - Solo PDF, Word e Imágenes</span>
                        </label>
                    </div>
                    <div id="preview-container" class="preview-container"></div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="form-section">
                <div class="section-title">
                    <span class="section-icon"></span>
                    <h2>Información Adicional</h2>
                </div>

                <div class="form-row" id="descontar-dominical-container">
                    <div class="form-field">
                        <label class="required"><?php echo count($sedes) === 1 ? '9' : '10'; ?>. ¿SE DEBERÍA CONSIDERAR DESCONTAR EL DOMINICAL?</label>
                        <select name="descontar_dominical" id="descontar_dominical" required>
                            <option value="">Selecciona la respuesta</option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                </div>

                <div class="form-field">
                    <label class="required" id="label-observacion"><?php echo count($sedes) === 1 ? '10' : '11'; ?>. OBSERVACIÓN SOBRE LA NOVEDAD</label>
                    <select name="observacion_novedad" id="observacion_novedad" required>
                        <option value="">Selecciona la respuesta</option>
                        <option value="ENFERMEDAD GENERAL">ENFERMEDAD GENERAL</option>
                        <option value="ACCIDENTE DE TRABAJO">ACCIDENTE DE TRABAJO</option>
                        <option value="CITA MÉDICA">CITA MÉDICA</option>
                        <option value="AUSENCIA">AUSENCIA</option>
                        <option value="MATERNIDAD">MATERNIDAD</option>
                        <option value="PATERNIDAD">PATERNIDAD</option>
                        <option value="LICENCIA DE LUTO">LICENCIA DE LUTO</option>
                        <option value="CALAMIDAD DOMÉSTICA">CALAMIDAD DOMÉSTICA</option>
                        <option value="DEVUELVE POR LLEGAR TARDE">DEVUELVE POR LLEGAR TARDE</option>
                        <option value="DEVUELVE POR ENFERMEDAD">DEVUELVE POR ENFERMEDAD</option>
                        <option value="RETARDO">RETARDO</option>
                        <option value="SANCIÓN">SANCIÓN</option>
                        <option value="PERMISO PERSONAL U OTROS">PERMISO PERSONAL U OTROS</option>
                        <option value="VACACIONES">VACACIONES</option>
                        <option value="DÍA DE LA FAMILIA O CUMPLEAÑOS (LEY 1857-17)">DÍA DE LA FAMILIA O CUMPLEAÑOS (LEY 1857-17)</option>
                        <option value="COVID-19">COVID-19</option>
                        <option value="REINTEGRO DE INCAPACIDAD">REINTEGRO DE INCAPACIDAD</option>
                        <option value="REINTEGRO DE VACACIONES">REINTEGRO DE VACACIONES</option>
                        <option value="REINTEGRO DE AUSENCIA O SANCIÓN">REINTEGRO DE AUSENCIA O SANCIÓN</option>
                        <option value="NOTIFICA ESTAR EMBARAZADA">NOTIFICA ESTAR EMBARAZADA</option>
                        <option value="RENUNCIA">RENUNCIA</option>
                    </select>
                </div>

                <div class="form-field" style="margin-top: 1.5rem;">
                    <label id="label-nota"><?php echo count($sedes) === 1 ? '11' : '12'; ?>. OBSERVACIONES</label>
                    <textarea name="observaciones" rows="4" placeholder="Escriba su respuesta (opcional)"></textarea>
                </div>
            </div>
            </div>

            <!-- Botones -->
            <div class="form-actions">
                <button type="submit" class="btn-submit">Registrar Novedad</button>
            </div>
        </form>
    </div>
</main>

<script src="<?php echo asset_url('js/formulario-dinamico.js'); ?>"></script>
<script>
// Inicializar carga dinámica si solo hay 1 sede
document.addEventListener('DOMContentLoaded', function() {
    const sedeInput = document.getElementById('sede');
    
    // Si es un input hidden (solo 1 sede), cargar áreas automáticamente
    if (sedeInput && sedeInput.type === 'hidden') {
        cargarAreasPorSede();
    }
});

// Manejar cambio de justificación
function handleJustificacionChange() {
    const justificacionRadios = document.getElementsByName('justificacion');
    const documentacionSection = document.getElementById('documentacion-section');
    const archivosInput = document.getElementById('archivos');
    const descontarDominicalContainer = document.getElementById('descontar-dominical-container');
    const descontarDominicalSelect = document.getElementById('descontar_dominical');
    const labelObservacion = document.getElementById('label-observacion');
    const labelNota = document.getElementById('label-nota');
    
    // Determinar si hay 1 sede o múltiples (para ajustar numeración base)
    const sedeInput = document.getElementById('sede');
    const tieneSoloUnaSede = sedeInput && sedeInput.type === 'hidden';
    
    let justificacionValue = '';
    for (const radio of justificacionRadios) {
        if (radio.checked) {
            justificacionValue = radio.value;
            break;
        }
    }
    
    // Si justificación es NO, ocultar campos relacionados
    if (justificacionValue === 'NO') {
        // Ocultar sección de documentación
        documentacionSection.style.display = 'none';
        archivosInput.removeAttribute('required');
        
        // Ocultar campo de dominical
        descontarDominicalContainer.style.display = 'none';
        descontarDominicalSelect.value = 'NO';
        descontarDominicalSelect.removeAttribute('required');
        
        // Ajustar numeración (sin documentación ni dominical)
        if (tieneSoloUnaSede) {
            labelObservacion.innerHTML = '8. OBSERVACIÓN SOBRE LA NOVEDAD <span style="color: #ef4444;">*</span>';
            labelNota.innerHTML = '9. OBSERVACIONES';
        } else {
            labelObservacion.innerHTML = '9. OBSERVACIÓN SOBRE LA NOVEDAD <span style="color: #ef4444;">*</span>';
            labelNota.innerHTML = '10. OBSERVACIONES';
        }
    } else {
        // Si justificación es SI, mostrar todos los campos
        documentacionSection.style.display = 'block';
        archivosInput.setAttribute('required', 'required');
        
        descontarDominicalContainer.style.display = 'block';
        descontarDominicalSelect.setAttribute('required', 'required');
        descontarDominicalSelect.value = '';
        
        // Ajustar numeración (con documentación y dominical)
        if (tieneSoloUnaSede) {
            labelObservacion.innerHTML = '10. OBSERVACIÓN SOBRE LA NOVEDAD <span style="color: #ef4444;">*</span>';
            labelNota.innerHTML = '11. OBSERVACIONES';
        } else {
            labelObservacion.innerHTML = '11. OBSERVACIÓN SOBRE LA NOVEDAD <span style="color: #ef4444;">*</span>';
            labelNota.innerHTML = '12. OBSERVACIONES';
        }
    }
}

// Previsualización de archivos - Sistema acumulativo
let archivosSeleccionados = [];

document.getElementById('archivos').addEventListener('change', function(e) {
    const nuevosArchivos = Array.from(e.target.files);
    
    // Agregar nuevos archivos a la lista existente
    nuevosArchivos.forEach(file => {
        // Verificar que no exceda el límite
        if (archivosSeleccionados.length >= 3) {
            alert('Solo se permiten máximo 3 archivos');
            return;
        }
        
        // Verificar formato
        const fileExt = file.name.split('.').pop().toLowerCase();
        const allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (!allowedExts.includes(fileExt)) {
            alert(`Formato no permitido: ${file.name}. Solo se permiten: PDF, Word, Imágenes`);
            return;
        }
        
        // Verificar tamaño (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert(`El archivo ${file.name} excede el tamaño máximo de 10MB`);
            return;
        }
        
        archivosSeleccionados.push(file);
    });
    
    // Actualizar el input con todos los archivos
    actualizarInputArchivos();
    
    // Mostrar previsualización
    mostrarPrevisualizacion();
});

function actualizarInputArchivos() {
    const dt = new DataTransfer();
    archivosSeleccionados.forEach(file => dt.items.add(file));
    document.getElementById('archivos').files = dt.files;
}

function mostrarPrevisualizacion() {
    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = '';
    
    archivosSeleccionados.forEach((file, index) => {
        const fileExt = file.name.split('.').pop().toLowerCase();
        const previewItem = document.createElement('div');
        previewItem.className = 'preview-item';

        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}">
                    <span class="preview-name">${file.name}</span>
                    <button type="button" class="remove-file" data-index="${index}">×</button>
                `;
                agregarEventoEliminar(previewItem.querySelector('.remove-file'));
            };
            reader.readAsDataURL(file);
        } else if (fileExt === 'pdf') {
            previewItem.innerHTML = `
                <div class="file-icon pdf-icon">PDF</div>
                <span class="preview-name">${file.name}</span>
                <button type="button" class="remove-file" data-index="${index}">×</button>
            `;
            agregarEventoEliminar(previewItem.querySelector('.remove-file'));
        } else {
            previewItem.innerHTML = `
                <div class="file-icon doc-icon">DOC</div>
                <span class="preview-name">${file.name}</span>
                <button type="button" class="remove-file" data-index="${index}">×</button>
            `;
            agregarEventoEliminar(previewItem.querySelector('.remove-file'));
        }

        previewContainer.appendChild(previewItem);
    });
}

function agregarEventoEliminar(btn) {
    btn.addEventListener('click', function() {
        const index = parseInt(this.dataset.index);
        archivosSeleccionados.splice(index, 1);
        actualizarInputArchivos();
        mostrarPrevisualizacion();
    });
}
</script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
