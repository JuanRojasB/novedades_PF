<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Sistema de Novedades'; ?> - Pollo Fiesta</title>
    <link rel="icon" type="image/png" href="<?php echo asset_url('img/logo-pollo-fiesta.png'); ?>">
    <link rel="stylesheet" href="<?php echo asset_url('css/style.css'); ?>?v=<?php echo time(); ?>">
    <?php if (isset($css_files)): ?>
        <?php foreach ($css_files as $css): ?>
            <link rel="stylesheet" href="<?php echo asset_url('css/' . $css . '.css'); ?>?v=<?php echo time(); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
