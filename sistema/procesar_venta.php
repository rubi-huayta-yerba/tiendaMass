<?php
// ============================================================================
// SECCIÓN: DATOS DE ENTRADA
// ============================================================================

// Datos del Cliente
$cliente_nombre = "Carlos Mendoza";
$cliente_dni = "72145698"; // Cambiar para probar validación (ej: "721456" o "7214A698")
$cliente_tipo = "vip"; // Opciones: regular / frecuente / vip

// Productos Comprados (Mínimo 3 usando estructura de Array Asociativo)
$productos = [
    [
        "nombre" => "Inca Kola 1.5L",
        "categoria" => "bebidas",
        "precio_unitario" => 6.50,
        "cantidad" => 2
    ],
    [
        "nombre" => "Arroz Costeño Extra 1kg",
        "categoria" => "abarrotes",
        "precio_unitario" => 4.80,
        "cantidad" => 3
    ],
    [
        "nombre" => "Leche Gloria Azul Sixpack",
        "categoria" => "lácteos",
        "precio_unitario" => 23.90,
        "cantidad" => 1
    ],
    [
        "nombre" => "Plátano de Seda 1kg",
        "categoria" => "frutas y verduras",
        "precio_unitario" => 3.50,
        "cantidad" => 2
    ]
];

// Método de Pago
$metodo_pago = "efectivo"; // Opciones: efectivo / yape / plin / tarjeta

// SECCIÓN: BLOQUES DE LÓGICA (REGLAS DE NEGOCIO)
// ============================================================================

// 1. Validación de DNI
if (strlen($cliente_dni) !== 8 || !ctype_digit($cliente_dni)) {
    echo "<div style='color: red; font-weight: bold; padding: 20px; border: 2px solid red;'>";
    echo "ERROR CRÍTICO: El DNI proporcionado no es válido. Debe contener exactamente 8 dígitos numéricos.";
    echo "</div>";
    exit; // Detiene la ejecución por completo
}

// Inicialización de acumuladores globales para el comprobante
$total_subtotal_general = 0.0;
$total_igv_general = 0.0;
$lista_productos_procesados = [];

// Procesamiento de productos (Reglas 2 y 3)
foreach ($productos as $prod) {
    // 2. Determinar IGV según categoría (switch)
    switch ($prod['categoria']) {
        case 'abarrotes':
        case 'bebidas':
        case 'lácteos':
        case 'limpieza':
        case 'aseo personal':
            $tasa_igv = 0.18;
            break;
        case 'panadería':
        case 'frutas y verduras':
            $tasa_igv = 0.00; // Inafecto
            break;
        default:
            $tasa_igv = 0.18; // Por defecto en caso de no estar mapeado
            break;
    }