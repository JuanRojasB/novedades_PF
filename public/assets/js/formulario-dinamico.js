// Formulario Dinámico - Carga de Áreas según Sede
// BASE_URL is injected by PHP in the view that includes this script

// Cargar áreas directamente por sede (simplificado)
async function cargarAreasPorSede() {
    const sedeSelect = document.getElementById('sede');
    const areaSelect = document.getElementById('area_trabajo');
    
    // Obtener sede ID
    let sedeId;
    if (sedeSelect.tagName === 'SELECT') {
        const selectedOption = sedeSelect.options[sedeSelect.selectedIndex];
        sedeId = selectedOption.getAttribute('data-sede-id');
    } else {
        // Es un input hidden
        sedeId = sedeSelect.getAttribute('data-sede-id');
    }
    
    if (!sedeId) {
        areaSelect.innerHTML = '<option value="">Primero selecciona una sede</option>';
        return;
    }
    
    try {
        const response = await fetch(`${window.APP_BASE_URL}/api/areas/sede/${sedeId}`);
        const areas = await response.json();
        
        areaSelect.innerHTML = '<option value="">Selecciona el área</option>';
        
        areas.forEach(area => {
            const option = document.createElement('option');
            option.value = area.nombre;
            option.textContent = area.nombre;
            areaSelect.appendChild(option);
        });
        
        // Si solo hay un área, auto-seleccionar
        if (areas.length === 1) {
            areaSelect.selectedIndex = 1;
        }
    } catch (error) {
        console.error('Error cargando áreas:', error);
        alert('Error al cargar las áreas. Por favor recarga la página.');
    }
}
