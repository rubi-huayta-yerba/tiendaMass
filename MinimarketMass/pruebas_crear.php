<?php
require_once __DIR__ . '/models/ProductoRepository.php';

$repo = new ProductoRepository();
$ok = $repo->crear([
    'codigo'    => '7750999000047',
    'nombre'    => 'Chocolate Sublime',
    'marca'     => 'Nestle',
    'categoria' => 1,
    'precio'    => 1.80,
    'stock'     => 120,
]);

echo $ok ? "✅ Producto agregado" : "❌ No se pudo agregar";