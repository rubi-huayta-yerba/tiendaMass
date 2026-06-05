<?php
declare(strict_types=1);

require_once __DIR__ . '/config/conexion.php';

try {
    $pdo = getConexion();
    echo "✅ Conexión exitosa a minimarket_mass<br>";

    // Prueba real: contamos los productos del catálogo Mass
    $sql = "SELECT COUNT(*) AS total FROM productos";
    $fila = $pdo->query($sql)->fetch();

    echo "📦 Productos en la base de datos: {$fila['total']}";

} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}