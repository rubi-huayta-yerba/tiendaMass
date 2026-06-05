<?php
// clases/Cliente.php

class Cliente {
    // Propiedades solicitadas
    private string $dni;
    private string $nombre;
    private string $apellido;
    private string $tipo; // regular / frecuente / vip (para reglas de Mass)

    public function __construct(string $dni, string $nombre, string $apellido, string $tipo) {
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->tipo = $tipo;
        $this->validarDni(); // Validación automática al instanciar
    }

    public function getDni(): string { return $this->dni; }
    public function getTipo(): string { return $this->tipo; }

    public function nombreCompleto(): string {
        return $this->nombre . " " . $this->apellido;
    }

    private function validarDni(): void {
        // Regla de negocio 1: Validar con regex de 8 dígitos numéricos
        if (!preg_match('/^[0-9]{8}$/', $this->dni)) {
            echo "<div style='color: red; font-weight: bold; padding: 20px; border: 2px solid red;'>";
            echo "ERROR CRÍTICO: El DNI proporcionado no es válido. Debe contener exactamente 8 dígitos numéricos.";
            echo "</div>";
            exit; // Detiene la ejecución por completo
        }
    }
}