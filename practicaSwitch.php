<?php
$categoria = "bebidas";

switch ($categoria) {
    case "abarrotes":
        $pasillo = "Pasillo 1";
        $igv = 0.18;
        break;
    case "bebidas":
        $pasillo = "Pasillo 2";
        $igv = 0.18;
        break;
    case "lacteos":
        $pasillo = "Pasillo 3";
        $igv = 0.18;
        break;
    case "panaderia":
    case "frutas":
        $pasillo = "Zona fresca";
        $igv = 0; // inafecto
        break;
    default:
        $pasillo = "No definido";
        $igv = 0.18;
}

echo "Categoría: " . $categoria . "<br>";
echo "Pasillo: " . $pasillo . "<br>";
echo "IGV: " . ($igv * 100) . "%";
?>