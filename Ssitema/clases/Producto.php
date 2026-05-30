<?php
// clases/Producto.php

class Producto {
    // Propiedades solicitadas
    private string $codigo;
    private string $nombre;
    private float $precio;
    private int $stock;
    private string $categoria; // Necesaria para la regla del IGV de Mass

    public function __construct(string $codigo, string $nombre, float $precio, int $stock, string $categoria) {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->categoria = $categoria;
    }

    // Getters esenciales
    public function getNombre(): string { return $this->nombre; }
    public function getPrecio(): float { return $this->precio; }
    public function getCategoria(): string { return $this->categoria; }

    // Métodos del negocio
    public function obtenerTasaIgv(): float {
        // Regla de negocio 2: IGV según categoría
        switch ($this->categoria) {
            case 'abarrotes':
            case 'bebidas':
            case 'lácteos':
            case 'limpieza':
            case 'aseo personal':
                return 0.18;
            case 'panadería':
            case 'frutas y verduras':
                return 0.00;
            default:
                return 0.18;
        }
    }

    public function precioConIGV(): float {
        return $this->precio * (1 + $this->obtenerTasaIgv());
    }

    public function haySuficienteStock(int $cantidad): bool {
        return $this->stock >= $cantidad;
    }

    public function descontarStock(int $cantidad): void {
        if ($this->haySuficienteStock($cantidad)) {
            $this->stock -= $cantidad;
        }
    }
}