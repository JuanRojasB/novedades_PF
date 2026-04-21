<?php
// config/config.cpanel.php - Configuración para cPanel
// RENOMBRAR ESTE ARCHIVO A config.php DESPUÉS DE SUBIR A CPANEL

return [
    'app' => [
        'name' => 'Sistema de Novedades - Pollo Fiesta',
        'version' => '1.0.0',
        'url' => 'https://pollo-fiesta.com', // CAMBIAR por tu dominio real
    ],
    
    'database' => [
        'type' => 'mysql',
        'json_file' => STORAGE_PATH . '/novedades.json',
        
        // Configuración MySQL para cPanel
        'host' => 'localhost',
        'database' => 'wwpoll_informe_novedades', // ✓ Ya configurado
        'username' => 'wwpoll_admin_novedades', // CAMBIAR: reemplazar USUARIO con el nombre real
        'password' => '^8znu9HDk[D2#)y-', // CAMBIAR: poner la contraseña de la BD
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
        'host'       => 'pollo-fiesta.com', // Servidor SMTP del dominio
        'port'       => 465,
        'encryption' => 'ssl',
        'username'   => 'innovacion@pollo-fiesta.com',
        'password'   => '^8znu9HDk[D2#)y-', // CAMBIAR: poner la contraseña real del correo
        'from_email' => 'innovacion@pollo-fiesta.com',
        'from_name'  => 'Sistema de Novedades - Pollo Fiesta',
    ]
];
