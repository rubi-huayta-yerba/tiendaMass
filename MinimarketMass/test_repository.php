<?php
declare(strict_types=1);
require_once 'models/ProductoRepository.php';

/**
 * ════════════════════════════════════════════════════════════════════
 *  ¿POR QUÉ EXISTE EL REPOSITORY?
 * ════════════════════════════════════════════════════════════════════
 *
 * Este archivo NO es parte del sistema final. Es solo una demostración
 * para que se vea POR QUÉ vale la pena tener una clase aparte para
 * conseguir los productos, en vez de ponerlos directos en el Controller.
 *
 * Pregunta del día:
 *   "Si los datos están en un array hardcoded, ¿por qué crear una
 *    clase entera (ProductoRepository) en lugar de poner el array
 *    directo en el Controller?"
 *
 * Respuesta corta:
 *   El jueves (sesión 5) este array va a desaparecer y los datos
 *   van a venir de MySQL. Si los hubiéramos puesto en el Controller,
 *   tendríamos que reescribir el Controller entero. Como están en el
 *   Repository, SOLO cambia este archivo y nada más.
 *
 * URL para ejecutar:
 *   http://localhost:8080/MinimarketMass/test_repository.php
 * ════════════════════════════════════════════════════════════════════
 */

echo '<h1>🧪 Demostración del Repository</h1>';

$repo = new ProductoRepository();

// ─────────────────────────────────────────────────────────────────
// PRUEBA 1: Obtener todos los productos
// ─────────────────────────────────────────────────────────────────
echo '<h2>Prueba 1 — obtenerTodos()</h2>';
echo '<p>El Repository devuelve un array de objetos <strong>Producto</strong>.</p>';

$productos = $repo->obtenerTodos();

echo '<p>Total de productos en el catálogo: <strong>' . count($productos) . '</strong></p>';
echo '<ul>';
foreach ($productos as $p) {
    echo '<li>'
        . htmlspecialchars($p->getCodigo()) . ' — '
        . htmlspecialchars($p->getNombre()) . ' — '
        . 'S/ ' . number_format($p->getPrecio(), 2)
        . '</li>';
}
echo '</ul>';

// ─────────────────────────────────────────────────────────────────
// PRUEBA 2: Buscar un producto que SÍ existe
// ─────────────────────────────────────────────────────────────────
echo '<hr><h2>Prueba 2 — buscarPorCodigo("INC500")</h2>';
echo '<p>El Repository sabe buscar UN producto específico por su código.</p>';

$incaKola = $repo->buscarPorCodigo('INC500');

if ($incaKola !== null) {
    echo '<p>✅ Encontrado: <strong>' . htmlspecialchars($incaKola->getNombre()) . '</strong></p>';
    echo '<p>Precio sin IGV: S/ ' . number_format($incaKola->getPrecio(), 2) . '</p>';
    echo '<p>Precio con IGV: S/ ' . number_format($incaKola->precioConIGV(), 2) . '</p>';
}

// ─────────────────────────────────────────────────────────────────
// PRUEBA 3: Buscar un producto que NO existe
// ─────────────────────────────────────────────────────────────────
echo '<hr><h2>Prueba 3 — buscarPorCodigo("XXX999")</h2>';
echo '<p>Si el producto no existe, el Repository devuelve <code>null</code>.</p>';

$inexistente = $repo->buscarPorCodigo('XXX999');

if ($inexistente === null) {
    echo '<p>✅ Correcto: el código XXX999 no existe en el catálogo.</p>';
} else {
    echo '<p>❌ Algo está mal: debería haber devuelto null.</p>';
}

// ─────────────────────────────────────────────────────────────────
// EL PUNTO IMPORTANTE
// ─────────────────────────────────────────────────────────────────
echo '<hr>';
echo '<div style="background:#fff5e0; border-left:4px solid #FFB81C; padding:20px; margin-top:30px;">';
echo '<h2 style="margin-top:0;">🎯 La pregunta del millón</h2>';
echo '<p>Hoy los datos están aquí mismo, en un array dentro del Repository:</p>';
echo '<pre style="background:#0d1825;color:#dbe4f0;padding:14px;border-radius:6px;font-size:13px;">return [
    new Producto(\'INC500\', \'Inca Kola 500ml\', 3.50, 48),
    new Producto(\'GLO400\', \'Gloria evaporada 400g\', 3.80, 62),
    ...
];</pre>';
echo '<p><strong>El jueves (sesión 5)</strong> esto va a cambiar a:</p>';
echo '<pre style="background:#0d1825;color:#dbe4f0;padding:14px;border-radius:6px;font-size:13px;">$stmt = $pdo->query("SELECT * FROM productos");
return $stmt->fetchAll(...);  // datos vienen de MySQL</pre>';
echo '<p><strong>¿Qué cambia en el Controller?</strong> NADA. Sigue llamando <code>$repo->obtenerTodos()</code> y recibiendo objetos Producto.</p>';
echo '<p><strong>¿Qué cambia en la View?</strong> NADA. Sigue recibiendo $productos y mostrándolos en la tabla.</p>';
echo '<p style="margin-bottom:0;"><strong>Por eso existe el Repository:</strong> aísla el "de dónde vienen los datos" del resto del sistema.</p>';
echo '</div>';
?>