<?php
$nombre = "lentejas";
$marca = "Doña olla";
$precio = 3.1;
$stock = 49;
$categoria = "Menestras";

echo "------FICHA DE PRODUCTOS MASS-----" . "<br>"; 
echo "<br>";
echo "Nombre: " . $nombre . "<br>";
echo "Marca: " . $marca . "<br>";
echo "Precio: S/ " . number_format($precio, 2) . "<br>";
echo "Stock: " . $stock . " unidades" . "<br>";
echo "Categoria: " . $categoria . "<br>";

?>