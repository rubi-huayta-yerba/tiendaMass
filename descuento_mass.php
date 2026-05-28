<?php
// Declaramos la variable y le asignamos un monto de compra
$monto_original = 250.00; 

// 1. Determinar el porcentaje de descuento

//Si el monto es menor a 30 soles no se le aplica ningun descuento
if ($monto_original < 30) {
    $porcentaje_aplicado = 0;

//Si el monto es mayor a 30 soles pero menor a 99, se le aplica un 5% de descuento
} elseif ($monto_original < 100) {
    $porcentaje_aplicado = 5;

//Si el monto es mayor a 100 soles pero menor a 199, se le aplica un 10% de descuento
} elseif ($monto_original < 200) {
    $porcentaje_aplicado = 10;

//Si el monto es mayor a 200 soles, se le aplica un 15% de descuento
} else {
    $porcentaje_aplicado = 15;
}

// 2. Realizamos el calculo para determinar el descuento que se aplicara y el monto final a pagar
$monto_descuento = $monto_original * ($porcentaje_aplicado / 100);
$monto_final = $monto_original - $monto_descuento;

// 3. Mostramos los resultados en pantalla
echo "Monto original: S/ " . number_format($monto_original, 2) ;
echo "<br>" ;
echo "Porcentaje aplicado: " . $porcentaje_aplicado . "%";
echo "<br>" ;
echo "Monto del descuento: S/ " . number_format($monto_descuento, 2) ;
echo "<br>" ;
echo "Monto final: S/ " . number_format($monto_final, 2) ;
?>