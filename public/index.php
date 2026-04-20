<?php
// public/index.php - Punto de entrada único de la aplicación

session_start();

// Definir constantes
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');

// Autoloader simple
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Cargar configuración
require_once CONFIG_PATH . '/config.php';

// Cargar helpers
require_once APP_PATH . '/Helpers/url_helper.php';

// Definir la ruta base
define('BASE_URL', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$path = str_replace($base_path, '', parse_url($request_uri, PHP_URL_PATH));
$path = trim($path, '/');

// Router simple
$routes = [
    '' => ['controller' => 'Controllers\AuthController', 'method' => 'login'],
    'login' => ['controller' => 'Controllers\AuthController', 'method' => 'login'],
    'logout' => ['controller' => 'Controllers\AuthController', 'method' => 'logout'],
    'novedades/crear' => ['controller' => 'Controllers\NovedadController', 'method' => 'crear'],
    'novedades/guardar' => ['controller' => 'Controllers\NovedadController', 'method' => 'guardar'],
    'novedades' => ['controller' => 'Controllers\NovedadController', 'method' => 'index'],
    'estadisticas' => ['controller' => 'Controllers\NovedadController', 'method' => 'estadisticas'],
    'admin' => ['controller' => 'Controllers\AdminController', 'method' => 'index'],
    'admin/crearSede' => ['controller' => 'Controllers\AdminController', 'method' => 'crearSede'],
    'admin/actualizarSede' => ['controller' => 'Controllers\AdminController', 'method' => 'actualizarSede'],
    'admin/eliminarSede' => ['controller' => 'Controllers\AdminController', 'method' => 'eliminarSede'],
    'admin/crearArea' => ['controller' => 'Controllers\AdminController', 'method' => 'crearArea'],
    'admin/actualizarArea' => ['controller' => 'Controllers\AdminController', 'method' => 'actualizarArea'],
    'admin/eliminarArea' => ['controller' => 'Controllers\AdminController', 'method' => 'eliminarArea'],
    'admin/crearTipoNovedad' => ['controller' => 'Controllers\AdminController', 'method' => 'crearTipoNovedad'],
    'admin/actualizarTipoNovedad' => ['controller' => 'Controllers\AdminController', 'method' => 'actualizarTipoNovedad'],
    'admin/eliminarTipoNovedad' => ['controller' => 'Controllers\AdminController', 'method' => 'eliminarTipoNovedad'],
];

// Rutas API dinámicas
if (preg_match('#^api/zonas/(\d+)$#', $path, $matches)) {
    $controller = new Controllers\ApiController();
    $controller->zonas($matches[1]);
    exit;
}

if (preg_match('#^api/areas/zona/(\d+)$#', $path, $matches)) {
    $controller = new Controllers\ApiController();
    $controller->areasPorZona($matches[1]);
    exit;
}

if (preg_match('#^api/areas/sede/(\d+)$#', $path, $matches)) {
    $controller = new Controllers\ApiController();
    $controller->areasPorSede($matches[1]);
    exit;
}

// Ruta para obtener archivos de una novedad
if (preg_match('#^api/archivos/novedad/(\d+)$#', $path, $matches)) {
    $controller = new Controllers\ApiController();
    $controller->archivosNovedad($matches[1]);
    exit;
}

// Ruta para servir archivos (visualizar)
if (preg_match('#^api/archivo/(\d+)$#', $path, $matches)) {
    $controller = new Controllers\ApiController();
    $controller->servirArchivo($matches[1]);
    exit;
}

// Ruta para descargar archivos
if (preg_match('#^api/descargar/(\d+)$#', $path, $matches)) {
    $controller = new Controllers\ApiController();
    $controller->descargarArchivo($matches[1]);
    exit;
}

// Buscar ruta
if (isset($routes[$path])) {
    $route = $routes[$path];
    $controller = new $route['controller']();
    $method = $route['method'];
    $controller->$method();
} else {
    // 404
    http_response_code(404);
    echo "Página no encontrada";
}
