<?php
declare(strict_types=1);

// La sesión debe arrancar ANTES de cualquier salida al navegador.
session_start();

require_once __DIR__ . '/../helpers/sesion.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ProductoController.php';

// Enrutamiento simple por ?accion=
$accion = $_GET['accion'] ?? 'catalogo';
$auth   = new AuthController();

switch ($accion) {

    case 'login':
        $auth->mostrarLogin();
        break;

    case 'procesar-login':
        $auth->procesarLogin();
        break;

    case 'logout':
        $auth->logout();
        break;
    
    case 'nuevo-producto':
        requiereLogin();
        (new ProductoController())->nuevo();
        break;

    case 'guardar-producto':
        requiereLogin();
        (new ProductoController())->guardar();
        break;    

    /**
     * REQUERIMIENTO A2: Acción "panel-admin" en el router.
     * Solo permite el acceso a usuarios con el rol 'admin'.
     */
    case 'panel-admin':
        requiereRol('admin');
        $usuario = usuarioActual();
        
        echo "<div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>";
        echo "<h1 style='color: #1e3a8a;'>Panel de administración</h1>";
        echo "<p>Bienvenido al área de gestión, <strong>" . htmlspecialchars($usuario['nombre']) . "</strong>.</p>";
        echo "<hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>";
        echo "<p><a href='index.php?accion=catalogo' style='color: #2563eb; text-decoration: none;'>← Volver al Catálogo de Productos</a></p>";
        echo "</div>";
        break;

    /**
     * BONUS +2: Segundo rol restringido.
     * Agrega una página protegida para el rol 'almacen' (Pantalla de inventario).
     */
    case 'panel-almacen':
        // 1. Validamos que el usuario en sesión tenga estrictamente el rol 'almacen'
        requiereRol('almacen');
        $usuario = usuarioActual();
        
        // 2. Renderizamos la pantalla de inventario solicitada por el bonus
        echo "<div style='font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; border-left: 5px solid #15803d;'>";
        echo "<h1 style='color: #15803d;'>Pantalla de Inventario</h1>";
        echo "<p>Área restringida para el personal de logística. Encargado actual: <strong>" . htmlspecialchars($usuario['nombre']) . "</strong>.</p>";
        echo "<div style='background-color: #f0fdf4; padding: 10px; border-radius: 4px; margin: 15px 0; color: #166534;'>";
        echo "✓ Control de Stock y Almacén central sincronizado con la tienda: <strong>" . htmlspecialchars($usuario['tienda']) . "</strong>.";
        echo "</div>";
        echo "<hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>";
        echo "<p><a href='index.php?accion=catalogo' style='color: #15803d; text-decoration: none; font-weight: bold;'>← Volver al Catálogo de Productos</a></p>";
        echo "</div>";
        break;

    case 'catalogo':
    default:
        requiereLogin();                      // sin sesión → manda al login
        (new ProductoController())->listar(); // ← llama al método REAL del controller
        break;
}