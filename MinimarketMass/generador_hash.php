<?php
declare(strict_types=1);

/**
 * GENERADOR DE HASH — herramienta de aprendizaje.
 *
 * Ejecútalo en el navegador:
 *   http://localhost:8080/MinimarketMass/generar_hash.php
 *
 * Copia el hash que aparece y úsalo en un UPDATE para guardarlo en la BD.
 * Cada vez que recargues sale un hash DISTINTO (el salt es aleatorio),
 * pero todos validan la misma contraseña con password_verify().
 */

$clave = 'admin123';
$hash  = password_hash($clave, PASSWORD_DEFAULT);

echo "<h2>Generador de hash</h2>";
echo "<p>Contraseña: <b>" . htmlspecialchars($clave) . "</b></p>";
echo "<p>Hash generado:</p>";
echo "<pre style='background:#0e1726;color:#9fe6b0;padding:12px;border-radius:8px'>"
     . htmlspecialchars($hash) . "</pre>";

// Demostración: el hash recién creado valida la clave original.
$ok = password_verify($clave, $hash) ? 'SÍ ✅' : 'NO ❌';
echo "<p>¿password_verify('$clave', \$hash) devuelve true? <b>$ok</b></p>";
