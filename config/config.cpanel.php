<?php
// config/config.php - Configuración para PRODUCCIÓN (cPanel)

return [
    'app' => [
        'name' => 'Sistema de Novedades - Pollo Fiesta',
        'version' => '1.0.0',
        'url' => 'https://pollo-fiesta.com',
        'environment' => 'production',
    ],
    
    'database' => [
        'type' => 'mysql',
        'json_file' => STORAGE_PATH . '/novedades.json',
        
        // Configuración MySQL para cPanel
        'host' => 'localhost',
        'database' => 'wwpoll_informe_novedades',
        'username' => 'wwpoll_admin_novedades',
        'password' => '^8znu9HDk[D2#)y-',
        'charset' => 'utf8mb4'
    ],
    
    'upload' => [
        'path' => STORAGE_PATH . '/uploads',
        'max_files' => 3,
        'max_size' => 100 * 1024 * 1024, // 100MB
        'allowed_types' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mp3', 'wav'],
    ],
    
    'auth' => [
        'username' => 'usuario',
        'password' => '123456'
    ],

    'mail' => [
        // Modo: 'file' = Guardar como HTML (temporal) | 'smtp' = Enviar por correo (producción)
        'mode' => 'file', // ⚠️ Cambiar a 'smtp' cuando funcione el correo
        
        // Configuración SMTP
        'host'       => 'pollo-fiesta.com',
        'port'       => 465,
        'encryption' => 'ssl',
        'username'   => 'innovacion@pollo-fiesta.com',
        'password'   => 'Sistemas2026*',
        'from_email' => 'innovacion@pollo-fiesta.com',
        'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
    ]
];
