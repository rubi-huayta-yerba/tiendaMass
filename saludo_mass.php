<?php

// Obtener la hora actual (formato 24h, 0-23)
$hora = (int) date("H");

// Determinar el turno según la hora
if ($hora >= 5 && $hora <= 11) {
    $turno = "mañana";
} elseif ($hora >= 12 && $hora <= 18) {
    $turno = "tarde";
} elseif ($hora >= 19 && $hora <= 23) {
    $turno = "noche";
} else {
    $turno = "cerrado"; // De 0 a 4
}

// Mostrar saludo usando switch sobre el turno
switch ($turno) {
    case "mañana":
        echo "Buenos días, bienvenido a Mass\n";
        break;

    case "tarde":
        echo "Buenas tardes, bienvenido a Mass\n";
        break;

    case "noche":
        echo "Buenas noches, bienvenido a Mass\n";
        break;

    case "cerrado":
        echo "Tienda cerrada en este horario\n";
        break;
}
