<?php
$dato = 100;                // es un entero
echo gettype($dato) . "<br>";

$dato = "Soles peruanos";    // ahora es un texto
echo gettype($dato) . "<br>";

$dato = 19.50;              // ahora es un decimal (double)
echo gettype($dato) . "<br>";

$dato = true;               // y ahora es booleano
echo gettype($dato);
?>