<?php
declare(strict_types=1);

// Si la sesión aún no ha sido iniciada en este flujo, la arrancamos de inmediato
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Redirige al login si el usuario no ha iniciado sesión.
 */
function requiereLogin(): void {
    if (!isset($_SESSION['usuario'])) {
        header('Location: index.php?accion=login');
        exit;
    }
}

/**
 * Retorna los datos del usuario en sesión o null si es un invitado.
 * El array devuelto incluye: 'username', 'nombre', 'rol' y 'tienda'.
 */
function usuarioActual(): ?array {
    return $_SESSION['usuario'] ?? null; // incluye 'rol' => 'admin' | 'cajero'
}

/**
 * REQUERIMIENTO A1: Control de acceso por rol.
 * Verifica que el usuario esté logueado y que cuente con el rol solicitado.
 * Si no cumple, frena el flujo de forma segura con un mensaje.
 */
function requiereRol(string $rol): void {
    // 1. Primero nos aseguramos de que haya iniciado sesión (reutiliza requiereLogin)
    requiereLogin();
    
    // 2. Extraemos los datos del usuario actual
    $usuario = usuarioActual();
    
    // 3. Comparamos el rol guardado en la sesión contra el rol requerido
    if ($usuario === null || $usuario['rol'] !== $rol) {
        // Si el rol no coincide con el pedido, bloqueamos el paso de inmediato
        http_response_code(403); // Código HTTP: Acceso Prohibido
        echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
        echo "<h2 style='color:#b91c1c;'>Acceso denegado</h2>";
        echo "<p>No tienes los permisos necesarios para visualizar esta sección.</p>";
        echo "<p><a href='index.php?accion=catalogo'>Regresar al inicio</a></p>";
        echo "</div>";
        exit;
    }
}