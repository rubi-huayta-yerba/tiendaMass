<?php
$producto = "Inca Kola 1.5L";
$stock    = 7;

if ($stock === 0) {
    echo $producto . ": AGOTADO - reponer urgente";
} elseif ($stock < 10) {
    echo $producto . ": stock bajo (" . $stock . " unid.) - reponer pronto";
} elseif ($stock < 50) {
    echo $producto . ": stock normal";
} else {
    echo $producto . ": stock alto";
}
?>