<?php
$subtotal =  "120.50";
$igv = $subtotal * 0.18;
$total = $subtotal + $igv;
Echo "BOLETA DE PAGO CON IGV";
echo "<br>";
echo "Subtotal: " . $subtotal . "<br>";
echo "IGV: ". number_format($igv, 2)  . "<br>";
echo "Total: ". number_format($total, 2)  . "<br>";
?>
