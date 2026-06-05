<?php
// ============================================================================
// INCLUSIÓN DE CONTROLADORES/CLASES
// ============================================================================
require_once 'clases/Producto.php';
require_once 'clases/Cliente.php';
require_once 'clases/Venta.php';

// ============================================================================
// SECCIÓN: DATOS DE ENTRADA (INSTANCIACIÓN DE OBJETOS)
// ============================================================================

// Instanciación del Cliente (Regla 1 se ejecuta internamente en el constructor)
$cliente = new Cliente("72145698", "Carlos", "Mendoza", "vip");

// Instanciación de la Venta vinculando al cliente y método de pago
$venta = new Venta($cliente, "efectivo");

// Carga de Productos a la Venta usando los métodos de la Clase
$venta->agregarProducto(new Producto("P001", "Inca Kola 1.5L", 6.50, 100, "bebidas"), 2);
$venta->agregarProducto(new Producto("P002", "Arroz Costeño Extra 1kg", 4.80, 50, "abarrotes"), 3);
$venta->agregarProducto(new Producto("P003", "Leche Gloria Azul Sixpack", 23.90, 20, "lácteos"), 1);
$venta->agregarProducto(new Producto("P004", "Plátano de Seda 1kg", 3.50, 40, "frutas y verduras"), 2);

// Extracción de variables requeridas para mantener intacto el diseño HTML original
$cliente_nombre = $venta->getCliente()->nombreCompleto();
$cliente_dni    = $venta->getCliente()->getDni();
$cliente_tipo   = $venta->getCliente()->getTipo();
$saludo         = $venta->obtenerSaludoPorHora();

$total_subtotal_general = $venta->calcularSubtotal();
$total_igv_general      = $venta->calcularIGV();

$porcentaje_desc_monto   = $venta->obtenerPorcentajeDescuentoMonto();
$descuento_monto         = $venta->calcularDescuentoMonto();
$porcentaje_desc_cliente = $venta->obtenerPorcentajeDescuentoCliente();
$descuento_cliente       = $venta->calcularDescuentoCliente();

$total_final_pagar      = $venta->calcularTotal();
$instruccion_pago       = $venta->obtenerInstruccionPago();
$advertencia_pago       = $venta->obtenerAdvertenciaPago();
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
        Fecha: <?php echo $venta->getFecha(); ?>
    </div>

    <div class="linea"></div>
    
    <div class="saludo">
        <?php echo $saludo . ", " . $cliente_nombre . "!"; ?>
    </div>

    <div>
        <strong>Cliente:</strong> <?php echo $cliente_nombre; ?><br>
        <strong>DNI:</strong> <?php echo $cliente_dni; ?><br>
        <strong>Tipo de Tarjeta:</strong> <?php echo strtoupper($cliente_tipo); ?>
    </div>

    <div class="linea"></div>

    <table class="tabla-productos">
        <thead>
            <tr>
                <th>Cant. Descrip.</th>
                <th class="text-right">P.Unit</th>
                <th class="text-right">IGV</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($venta->getProductosVendidos() as $item): 
                $p = $item['producto'];
                $cant = $item['cantidad'];
                $subtotal_item = $p->getPrecio() * $cant;
                $igv_item = $subtotal_item * $p->obtenerTasaIgv();
                $total_item = $subtotal_item + $igv_item;
            ?>
                <tr>
                    <td><?php echo $cant . " x " . $p->getNombre(); ?></td>
                    <td class="text-right">S/ <?php echo number_format($p->getPrecio(), 2); ?></td>
                    <td class="text-right">S/ <?php echo number_format($igv_item, 2); ?></td>
                    <td class="text-right">S/ <?php echo number_format($total_item, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="linea"></div>

    <div class="totales">
        <table style="width: 100%;">
            <tr>
                <td>SUBTOTAL GENERAL:</td>
                <td class="text-right">S/ <?php echo number_format($total_subtotal_general, 2); ?></td>
            </tr>
            <tr>
                <td>TOTAL IGV GENERAL:</td>
                <td class="text-right">S/ <?php echo number_format($total_igv_general, 2); ?></td>
            </tr>
            <tr>
                <td>DESC. MONTO (<?php echo ($porcentaje_desc_monto * 100); ?>%):</td>
                <td class="text-right">- S/ <?php echo number_format($descuento_monto, 2); ?></td>
            </tr>
            <tr>
                <td>DESC. TIPO (<?php echo ($porcentaje_desc_cliente * 100); ?>%):</td>
                <td class="text-right">- S/ <?php echo number_format($descuento_cliente, 2); ?></td>
            </tr>
            <tr style="font-size: 16px; border-top: 1px solid #000;">
                <td><strong>TOTAL A PAGAR:</strong></td>
                <td class="text-right"><strong>S/ <?php echo number_format($total_final_pagar, 2); ?></strong></td>
            </tr>
        </table>
    </div>

    <div class="linea"></div>

    <div style="text-align: center; font-size: 13px; font-weight: bold;">
        Estación de Pago: <?php echo $instruccion_pago; ?>
    </div>

    <?php if (!empty($advertencia_pago)): ?>
        <div class="alert">
            ⚠️ <strong>ADVERTENCIA:</strong> <?php echo $advertencia_pago; ?>
        </div>
    <?php endif; ?>

    <div class="linea"></div>
    <div style="text-align: center; font-size: 11px;">
        ¡Gracias por su compra en Mass!<br>
        Ahorro masivo, todos los días.
    </div>
</div>

</body>
</html>