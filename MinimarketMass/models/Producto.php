<?php
declare(strict_types=1);

/**
 * Producto del Minimarket Mass.
 * Representa un artículo del catálogo: Inca Kola, Costeño, Gloria, etc.
 *
 * Esta clase ya la teníamos desde la sesión 3.
 * En la sesión 4 solo cambia de carpeta: antes estaba en clases/, ahora vive en models/.
 */
class Producto {
    private string $codigo;
    private string $nombre;
    private float $precio;
    private int $stock;

    private const TASA_IGV = 0.18;

    public function __construct(
        string $codigo,
        string $nombre,
        float $precio,
        int $stock = 0
    ) {
        if (empty(trim($codigo))) {
            throw new InvalidArgumentException("El código no puede estar vacío");
        }
        if ($precio < 0) {
            throw new InvalidArgumentException("El precio no puede ser negativo");
        }
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock  = max(0, $stock);
    }

    // === GETTERS ===
    public function getCodigo(): string { return $this->codigo; }
    public function getNombre(): string { return $this->nombre; }
    public function getPrecio(): float  { return $this->precio; }
    public function getStock():  int    { return $this->stock; }

    // === MÉTODOS DE NEGOCIO ===

    /** Precio con IGV peruano (18%) aplicado */
    public function precioConIGV(): float {
        return round($this->precio * (1 + self::TASA_IGV), 2);
    }

    /** ¿Tiene stock suficiente para una cantidad dada? */
    public function haySuficienteStock(int $cantidad): bool {
        return $this->stock >= $cantidad;
    }

    /** Descuenta cantidad del stock. Devuelve true si pudo */
    public function descontarStock(int $cantidad): bool {
        if (!$this->haySuficienteStock($cantidad)) {
            return false;
        }
        $this->stock -= $cantidad;
        return true;
    }
}
?>