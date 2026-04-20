@echo off
echo ========================================
echo   Sistema de Novedades - Pollo Fiesta
echo   Arquitectura MVC
echo ========================================
echo.
echo Verificando PHP...
php -v
if %errorlevel% neq 0 (
    echo.
    echo ERROR: PHP no esta instalado
    echo.
    echo Por favor instala PHP o usa XAMPP
    echo.
    pause
    exit
)

echo.
echo Iniciando servidor en http://localhost:8000/public/
echo.
echo Presiona Ctrl+C para detener el servidor
echo.
echo Abre tu navegador y ve a:
echo http://localhost:8000/public/
echo.
echo Credenciales:
echo Usuario: admin - Password: admin123
echo Usuario: supervisor - Password: super123
echo.
echo ========================================
cd public
php -S localhost:8000
