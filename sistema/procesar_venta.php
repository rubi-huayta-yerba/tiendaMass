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

    // 3. Subtotal por producto
    $subtotal_prod = $prod['precio_unitario'] * $prod['cantidad'];
    $igv_prod = $subtotal_prod * $tasa_igv;
    $total_prod = $subtotal_prod + $igv_prod;

    // Acumular en los totales generales del carrito
    $total_subtotal_general += $subtotal_prod;
    $total_igv_general += $igv_prod;

    // Guardamos los datos calculados para usarlos luego en el HTML
    $lista_productos_procesados[] = [
        "nombre" => $prod['nombre'],
        "precio" => $prod['precio_unitario'],
        "cantidad" => $prod['cantidad'],
        "igv" => $igv_prod,
        "subtotal" => $subtotal_prod,
        "total" => $total_prod
    ];
}

$monto_base_descuento = $total_subtotal_general + $total_igv_general;

// 4. Descuento por monto total (if/elseif)
$porcentaje_desc_monto = 0.0;
if ($monto_base_descuento < 30) {
    $porcentaje_desc_monto = 0.0;
} elseif ($monto_base_descuento >= 30 && $monto_base_descuento < 100) {
    $porcentaje_desc_monto = 0.05;
} elseif ($monto_base_descuento >= 100 && $monto_base_descuento < 200) {
    $porcentaje_desc_monto = 0.10;
} else {
    $porcentaje_desc_monto = 0.15;
}
$descuento_monto = $monto_base_descuento * $porcentaje_desc_monto;

// 5. Descuento adicional por tipo de cliente
$porcentaje_desc_cliente = 0.0;
if ($cliente_tipo === "frecuente") {
    $porcentaje_desc_cliente = 0.02;
} elseif ($cliente_tipo === "vip") {
    $porcentaje_desc_cliente = 0.05;
} else {
    $porcentaje_desc_cliente = 0.00;
}
$descuento_cliente = $monto_base_descuento * $porcentaje_desc_cliente;

// Total de descuentos acumulados y cálculo del Total Final
$total_descuentos = $descuento_monto + $descuento_cliente;
$total_final_pagar = $monto_base_descuento - $total_descuentos;

// 6. Validación de método de pago (switch)
$instruccion_pago = "";
$advertencia_pago = "";

switch ($metodo_pago) {
    case 'efectivo':
        $instruccion_pago = "Pago en efectivo - exacto preferido";
        if ($total_final_pagar > 500) {
            $advertencia_pago = "Se sugiere otro método para montos altos";
        }
        break;
    case 'yape':
    case 'plin':
        $instruccion_pago = "Mostrar QR del comercio";
        break;
    case 'tarjeta':
        $instruccion_pago = "Insertar tarjeta en POS";
        break;
    default:
        $instruccion_pago = "Método de pago no reconocido";
        break;
}

// 7. Saludo según hora actual (date + if)
date_default_timezone_set('America/Lima'); // Ajuste a hora local de Perú
$hora_actual = (int)date('H'); 
$saludo = "";

if ($hora_actual >= 5 && $hora_actual <= 11) {
    $saludo = "Buenos días";
} elseif ($hora_actual >= 12 && $hora_actual <= 18) {
    $saludo = "Buenas tardes";
} elseif ($hora_actual >= 19 && $hora_actual <= 23) {
    $saludo = "Buenas noches";
} else {
    $saludo = "Tienda cerrada";
}
// SECCIÓN: OUTPUT (COMPROBANTE HTML)
// ============================================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pago - MASS</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background-color: #f4f4f4; padding: 20px; color: #333; }
        .ticket { background: #fff; max-width: 450px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .logo { text-align: center; font-size: 28px; font-weight: bold; color: #ffcc00; background-color: #cc0000; padding: 5px; margin-bottom: 10px; }
        .datos-tienda { text-align: center; font-size: 12px; margin-bottom: 15px; }
        .linea { border-top: 1px dashed #000; margin: 10px 0; }
        .tabla-productos { width: 100%; font-size: 13px; border-collapse: collapse; }
        .tabla-productos th { text-align: left; border-bottom: 1px solid #000; }
        .text-right { text-align: right; }
        .totales { font-size: 14px; font-weight: bold; margin-top: 10px; }
        .alert { background: #fff3cd; color: #856404; padding: 8px; font-size: 12px; border: 1px solid #ffeeba; margin-top: 10px; text-align: center; }
        .saludo { font-style: italic; text-align: center; margin: 10px 0; font-size: 14px; }
    </style>
</head>
<body>

<div class="ticket">
    <div class="logo">M A S S</div>
    <div class="datos-tienda">
        MINIMARKET MASS S.A.C.<br>
        RUC: 20123456789<br>
        Dirección: Av. Principal 123 - Arequipa<br>
        Fecha: <?php echo date('d/m/Y H:i:s'); ?>
    </div>

    <div class="linea"></div>
    
    <div class="saludo">
        <?php echo $saludo . ", " . $cliente_nombre . "!"; ?>
    </div>