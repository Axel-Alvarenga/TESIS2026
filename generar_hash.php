<?php
// Configurar la contraseña que quieres usar
$password = 'Voces2026';

// Generar hash
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Contraseña: " . $password . "<br>";
echo "Hash generado: " . $hash . "<br>";
echo "<hr>";
echo "Copia este hash y úsalo en la base de datos:<br>";
echo "<strong>" . $hash . "</strong>";
?>