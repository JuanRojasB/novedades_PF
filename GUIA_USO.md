# Guía de Uso - Sistema de Novedades Pollo Fiesta

## Inicio de Sesión

1. Accede a: `http://localhost:8000` (o tu URL configurada)
2. Ingresa las credenciales:
   - **Usuario**: usuario
   - **Contraseña**: 123456
3. Serás redirigido al formulario de novedades

## Registrar una Novedad

### Campos del Formulario

1. **Nombres y Apellidos Completos**: Nombre completo del empleado
2. **Número de Cédula**: Solo números (sin puntos ni guiones)
3. **Sede**: Selecciona la sede correspondiente
4. **Área de Trabajo**: Selecciona el área (16 opciones disponibles)
5. **Fecha Novedad**: Fecha en que ocurrió la novedad
6. **Turno**: DÍA o NOCHE
7. **Novedad**: Tipo de novedad (11 opciones)
8. **Justificación**: SI o NO
9. **Foto del Soporte**: SI o NO
10. **Adjuntar Soporte**: 
    - Máximo 3 archivos
    - Formatos: PDF, Word (.doc, .docx), Imágenes (.jpg, .png, .gif)
    - Máximo 10MB por archivo
    - **Previsualización**: Verás una vista previa de los archivos antes de enviar
11. **¿Descontar Dominical?**: SI o NO
12. **Nota**: Descripción detallada de la novedad

### Previsualización de Archivos

Al seleccionar archivos:
- Las **imágenes** se muestran como miniatura
- Los **PDFs** se muestran con icono rojo
- Los **documentos Word** se muestran con icono azul
- Puedes **eliminar** archivos antes de enviar con el botón ❌

### Responsable

El responsable se registra automáticamente como el usuario que está logueado (no necesitas escribirlo).

## Dashboard

1. Haz clic en "Dashboard" en el formulario
2. Verás una tabla con todas las novedades registradas

### Filtros Disponibles

- **Área de Trabajo**: Filtra por zona específica
- **Sede**: Filtra por sede
- **Fecha Desde/Hasta**: Rango de fechas

### Estadísticas

En la parte superior verás:
- Total de novedades registradas
- Número de áreas activas

## Características del Sistema

✅ **Validación automática**: El sistema valida todos los campos antes de guardar

✅ **Formatos permitidos**: Solo PDF, Word e imágenes (por seguridad)

✅ **Previsualización**: Ve los archivos antes de enviar

✅ **Filtrado por zona**: Los jefes pueden ver solo su área

✅ **Responsable automático**: Se registra quién reportó la novedad

✅ **Base de datos MySQL**: Toda la información se guarda de forma segura

## Solución de Problemas

### No puedo subir archivos
- Verifica que el formato sea PDF, Word o imagen
- Verifica que no exceda 10MB por archivo
- Máximo 3 archivos por novedad

### No veo las novedades
- Verifica que hayas iniciado sesión
- Verifica los filtros aplicados
- Limpia los filtros con el botón "Limpiar"

### Error de base de datos
- Verifica que MySQL esté corriendo
- Verifica que hayas importado `database/schema.sql`
- Revisa las credenciales en `config/config.php`

## Cerrar Sesión

Haz clic en "Cerrar Sesión" en la esquina superior derecha.

## Soporte Técnico

Para más información técnica, consulta:
- `README.md` - Instalación general
- `INSTALACION_BD.md` - Configuración de base de datos
