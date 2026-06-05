<?php
// clases/Venta.php

class Venta {
    // Propiedades solicitadas
    private Cliente $cliente;
    private array $productosVendidos = []; // Estructura: [['producto' => Objeto, 'cantidad' => int]]
    private string $fecha;
    private string $metodoPago; // efectivo / yape / plin / tarjeta

    public function __construct(Cliente $cliente, string $metodoPago) {
        $this->cliente = $cliente;
        $this->metodoPago = $metodoPago;
        date_default_timezone_set('America/Lima');
        $this->fecha = date('d/m/Y H:i:s');
    }

    public function agregarProducto(Producto $producto, int $cantidad): void {
        $this->productosVendidos[] = [
            'producto' => $producto,
            'cantidad' => $cantidad
        ];
    }

    public function getCliente(): Cliente { return $this->cliente; }
    public function getMetodoPago(): string { return $this->metodoPago; }
    public function getFecha(): string { return $this->fecha; }
    public function getProductosVendidos(): array { return $this->productosVendidos; }

    // Métodos de cálculo globales
    public function calcularSubtotal(): float {
        $subtotalGeneral = 0.0;
        foreach ($this->productosVendidos as $item) {
            $subtotalGeneral += $item['producto']->getPrecio() * $item['cantidad'];
        }
        return $subtotalGeneral;
    }

    public function calcularIGV(): float {
        $igvGeneral = 0.0;
        foreach ($this->productosVendidos as $item) {
            $subtotal_prod = $item['producto']->getPrecio() * $item['cantidad'];
            $igvGeneral += $subtotal_prod * $item['producto']->obtenerTasaIgv();
        }
        return $igvGeneral;
    }

    public function calcularMontoBaseDescuento(): float {
        return $this->calcularSubtotal() + $this->calcularIGV();
    }

    // Regla de negocio 4: Descuento por monto total
    public function obtenerPorcentajeDescuentoMonto(): float {
        $montoBase = $this->calcularMontoBaseDescuento();
        if ($montoBase < 30) return 0.0;
        if ($montoBase < 100) return 0.05;
        if ($montoBase < 200) return 0.10;
        return 0.15;
    }

    public function calcularDescuentoMonto(): float {
        return $this->calcularMontoBaseDescuento() * $this->obtenerPorcentajeDescuentoMonto();
    }

    // Regla de negocio 5: Descuento adicional por tipo de cliente
    public function obtenerPorcentajeDescuentoCliente(): float {
        $tipo = $this->cliente->getTipo();
        if ($tipo === "frecuente") return 0.02;
        if ($tipo === "vip") return 0.05;
        return 0.00;
    }

    public function calcularDescuentoCliente(): float {
        return $this->calcularMontoBaseDescuento() * $this->obtenerPorcentajeDescuentoCliente();
    }

    public function calcularTotal(): float {
        $montoBase = $this->calcularMontoBaseDescuento();
        $totalDescuentos = $this->calcularDescuentoMonto() + $this->calcularDescuentoCliente();
        return $montoBase - $totalDescuentos;
    }

    // Regla de negocio 6: Validación de método de pago
    public function obtenerInstruccionPago(): string {
        switch ($this->metodoPago) {
            case 'efectivo': return "Pago en efectivo - exacto preferido";
            case 'yape':
            case 'plin': return "Mostrar QR del comercio";
            case 'tarjeta': return "Insertar tarjeta en POS";
            default: return "Método de pago no reconocido";
        }
    }

    public function obtenerAdvertenciaPago(): string {
        if ($this->metodoPago === 'efectivo' && $this->calcularTotal() > 500) {
            return "Se sugiere otro método para montos altos";
        }
        return "";
    }

    // Regla de negocio 7: Saludo según la hora actual
    public function obtenerSaludoPorHora(): string {
        $hora_actual = (int)date('H'); 
        if ($hora_actual >= 5 && $hora_actual <= 11) return "Buenos días";
        if ($hora_actual >= 12 && $hora_actual <= 18) return "Buenas tardes";
        if ($hora_actual >= 19 && $hora_actual <= 23) return "Buenas noches";
        return "Tienda cerrada";
    }
}