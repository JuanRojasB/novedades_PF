<?php
// app/Helpers/url_helper.php - Funciones helper para URLs

function base_url($path = '') {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return $base . '/' . ltrim($path, '/');
}

function asset_url($path = '') {
    return base_url('assets/' . ltrim($path, '/'));
}

function is_active($route) {
    $current = trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']), '/');
    return strpos($current, $route) !== false ? 'active' : '';
}
