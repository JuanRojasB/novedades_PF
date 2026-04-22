// Formulario Dinámico - Carga de TODAS las Áreas (sin filtrar por sede)

// Cargar TODAS las áreas disponibles
async function cargarAreasPorSede() {
    const areaSelect = document.getElementById('area_trabajo');
    
    try {
        // Cargar TODAS las áreas sin filtrar por sede
        const response = await fetch(`${window.APP_BASE_URL}/api/areas`);
        const areas = await response.json();
        
        areaSelect.innerHTML = '<option value="">Selecciona el área</option>';
        
        // Ordenar áreas alfabéticamente
        areas.sort((a, b) => a.nombre.localeCompare(b.nombre));
        
        areas.forEach(area => {
            const option = document.createElement('option');
            option.value = area.nombre;
            option.textContent = area.nombre;
            areaSelect.appendChild(option);
        });
        
    } catch (error) {
        console.error('Error cargando áreas:', error);
        alert('Error al cargar las áreas. Por favor recarga la página.');
    }
}

// Cargar áreas al cargar la página
document.addEventListener('DOMContentLoaded', cargarAreasPorSede);
