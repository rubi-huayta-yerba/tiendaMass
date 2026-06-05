<?php
declare(strict_types=1);

/**
 * FRONT CONTROLLER del sistema Minimarket Mass.
 *
 * Todas las URLs entran por aquí. El parámetro ?ruta=... decide
 * qué Controller y qué método responder.
 *
 * URLs de ejemplo:
 *   http://localhost:8080/MinimarketMass/public/index.php
 *   http://localhost:8080/MinimarketMass/public/index.php?ruta=productos
 *   http://localhost:8080/MinimarketMass/public/index.php?ruta=ventas
 */

require_once __DIR__. '/../controllers/ProductoController.php';

// 1. Leer la ruta solicitada (por defecto: productos)
$ruta = $_GET['ruta'] ?? 'productos';

// 2. Mapear ruta → controller/método
switch ($ruta) {
    case 'productos':
        $controller = new ProductoController();
        $controller->listar();
        break;

    default:
        http_response_code(404);
        echo '<h1>404 — Ruta no encontrada</h1>';
        echo '<p>La ruta solicitada (<code>' . htmlspecialchars($ruta) . '</code>) no existe.</p>';
        echo '<p><a href="index.php">Volver al inicio</a></p>';
        break;
}
?>