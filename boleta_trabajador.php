<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tarea T01 - Boleta de Trabajo</title>
</head>
<body>

  <h1>Minimarket Mass</h1>
  <h2>Boleta de Pago de Trabajador</h2>

  <?php
  // 1. DATOS DEL TRABAJADOR (Variables básicas)
  $nombre        = "Carlos Eduardo Mamani Quispe";
  $cargo         = "Jefe de almacén";
  $tienda        = "Mass Cayma";

  // 2. COMPONENTES DE INGRESOS
  $sueldo_base   = 2850.00;
  $asig_familiar = 102.50;
  $horas_extras  = 12;
  $valor_hora    = 18.50;

  // 3. CÁLCULOS DE INGRESOS
  $pago_horas_extras = $horas_extras * $valor_hora;
  $total_ingresos    = $sueldo_base + $asig_familiar + $pago_horas_extras;

  // 4. CÁLCULOS DE DESCUENTOS (13% AFP y 8% Renta)
  $descuento_afp   = $total_ingresos * 0.13;
  $descuento_renta = $total_ingresos * 0.08;
  $total_descuentos = $descuento_afp + $descuento_renta;

  // 5. CÁLCULO DEL SUELDO NETO FINAL
  $sueldo_neto = $total_ingresos - $total_descuentos;
  ?>

  <p><strong>Trabajador:</strong> <?php echo $nombre; ?></p>
  <p><strong>Cargo:</strong> <?php echo $cargo; ?></p>
  <p><strong>Tienda:</strong> <?php echo $tienda; ?></p>
  
  <hr>

  <h3>Detalle de Ingresos</h3>
  <p><?php echo "Sueldo Base: S/ " . number_format($sueldo_base, 2); ?></p>
  <p><?php echo "Asignación Familiar: S/ " . number_format($asig_familiar, 2); ?></p>
  <p><?php echo "Pago por Horas Extras: S/ " . number_format($pago_horas_extras, 2); ?></p>
  <p><strong><?php echo "TOTAL INGRESOS: S/ " . number_format($total_ingresos, 2); ?></strong></p>

  <hr>

  <h3>Detalle de Descuentos</h3>
  <p><?php echo "Descuento AFP (13%): S/ " . number_format($descuento_afp, 2); ?></p>
  <p><?php echo "Descuento Renta 5ta (8%): S/ " . number_format($descuento_renta, 2); ?></p>
  <p><strong><?php echo "TOTAL DESCUENTOS: S/ " . number_format($total_descuentos, 2); ?></strong></p>

  <hr>

  <h2><?php echo "NETO A PAGAR EN EFECTIVO: S/ " . number_format($sueldo_neto, 2); ?></h2>

</body>
</html>