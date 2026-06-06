<?php
// Usamos la función helper que ya tienes definida para obtener los datos
$usuario = usuarioActual();

// Si por alguna razón no hay usuario, no mostramos nada
if (!$usuario) {
    return;
}

// Extraemos los datos del array empatados con las llaves del AuthController
$rol          = $usuario['rol'] ?? '';
$nombre       = $usuario['nombre'] ?? 'No identificado'; // Unificado a 'nombre'
$tienda       = $usuario['tienda'] ?? 'No asignada';
$ultimoAcceso = $usuario['ultimo_acceso'] ?? '--/--/---- --:--'; // REQUERIMIENTO B2
?>

<div class="barra-usuario" style="background-color: #0d47a1; color: white; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; font-family: Arial, sans-serif; border-radius: 4px; margin-bottom: 15px;">
    
    <div class="saludo-rol" style="font-weight: bold; font-size: 1.1em;">
        <?php 
        if ($rol === 'admin') {
            echo "Modo administrador";
        } elseif ($rol === 'cajero') {
            echo "Caja";
        } elseif ($rol === 'almacen') {
            // Ajuste exacto para que el usuario con rol almacen vea su respectivo título
            echo "Pantalla de Inventario";
        } else {
            echo "Bienvenido";
        }
        ?>
    </div>

    <div class="datos-usuario" style="font-size: 0.95em;">
        <span><strong>Cajero:</strong> <?= htmlspecialchars($nombre) ?></span>
        <span style="margin: 0 10px;">|</span>
        <span><strong>Tienda:</strong> <?= htmlspecialchars($tienda) ?></span>
        <span style="margin: 0 10px;">|</span>
        <span style="background-color: #1565c0; padding: 4px 8px; border-radius: 4px;">
            <strong>Último acceso:</strong> <?= htmlspecialchars($ultimoAcceso) ?>
        </span>
    </div>

    <div class="boton-salir">
        <a href="index.php?accion=logout" style="background-color: #d32f2f; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 0.9em; transition: background 0.2s;">Salir</a>
    </div>
</div>