<?php
// generar_hashes.php - Generar hashes de contraseñas

$usuarios = [
    'ebecerra' => 'ebecerra2026*',
    'cortiz' => 'cortiz2026*',
    'cmartinez' => 'cmartinez2026*',
    'mvelandia' => 'mvelandia2026*'
];

echo "<h1>🔐 Hashes de Contraseñas</h1>";
echo "<p>Patrón: <code>usuario2026*</code></p>";
echo "<hr>";

foreach ($usuarios as $usuario => $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "<p><strong>{$usuario}</strong>: {$password}</p>";
    echo "<pre>{$hash}</pre>";
    echo "<hr>";
}

// Generar SQL completo
echo "<h2>📝 SQL Completo</h2>";
echo "<textarea style='width:100%;height:400px;font-family:monospace;'>";
echo "-- Agregar 4 usuarios de Gestión Humana con acceso al dashboard\n";
echo "-- Patrón de contraseña: usuario2026*\n\n";

foreach ($usuarios as $usuario => $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    $nombres = [
        'ebecerra' => ['nombre' => 'Elsa Becerra', 'email' => 'r.humanos@pollo-fiesta.com'],
        'cortiz' => ['nombre' => 'Catherine Ortiz', 'email' => 'AuxiliarGH2@pollo-fiesta.com'],
        'cmartinez' => ['nombre' => 'Carmenza Martinez', 'email' => 'AuxiliarGH1@pollo-fiesta.com'],
        'mvelandia' => ['nombre' => 'Michelle Velandia', 'email' => 'profesionalnomina@pollo-fiesta.com']
    ];
    
    $info = $nombres[$usuario];
    
    echo "-- {$info['nombre']} - Contraseña: {$password}\n";
    echo "INSERT INTO usuarios (username, password, nombre, email, rol, activo)\n";
    echo "VALUES ('{$usuario}', '{$hash}', '{$info['nombre']}', '{$info['email']}', 'admin', 1);\n\n";
}

echo "</textarea>";

echo "<p><button onclick='copiarSQL()'>📋 Copiar SQL</button></p>";

echo "<script>
function copiarSQL() {
    const textarea = document.querySelector('textarea');
    textarea.select();
    document.execCommand('copy');
    alert('SQL copiado al portapapeles');
}
</script>";
?>
