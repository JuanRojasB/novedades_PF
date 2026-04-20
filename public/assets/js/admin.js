// Tabs
function showTab(tabName) {
    // Ocultar todos los tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Desactivar todos los botones
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar tab seleccionado
    document.getElementById('tab-' + tabName).classList.add('active');
    
    // Activar botón correspondiente
    event.target.classList.add('active');
}

// Modales
function showModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Cerrar modal al hacer clic fuera
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}

// ===== SEDES =====
function editarSede(sede) {
    document.getElementById('edit-sede-id').value = sede.id;
    document.getElementById('edit-sede-nombre').value = sede.nombre;
    document.getElementById('edit-sede-activo').checked = sede.activo == 1;
    showModal('modal-editar-sede');
}

function eliminarSede(id) {
    if (confirm('¿Estás seguro de eliminar esta sede?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = BASE_URL + '/admin/eliminarSede';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

// ===== ÁREAS =====
function editarArea(area) {
    document.getElementById('edit-area-id').value = area.id;
    document.getElementById('edit-area-nombre').value = area.nombre;
    document.getElementById('edit-area-activo').checked = area.activo == 1;
    showModal('modal-editar-area');
}

function eliminarArea(id) {
    if (confirm('¿Estás seguro de eliminar esta área?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = BASE_URL + '/admin/eliminarArea';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

// ===== TIPOS DE NOVEDAD =====
function editarTipo(tipo) {
    document.getElementById('edit-tipo-id').value = tipo.id;
    document.getElementById('edit-tipo-nombre').value = tipo.nombre;
    document.getElementById('edit-tipo-activo').checked = tipo.activo == 1;
    showModal('modal-editar-tipo');
}

function eliminarTipo(id) {
    if (confirm('¿Estás seguro de eliminar este tipo de novedad?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = BASE_URL + '/admin/eliminarTipoNovedad';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
