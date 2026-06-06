<?php
declare(strict_types=1);

class Usuario {
    public function __construct(
        private int    $id,
        private string $username,
        private string $nombres,
        private string $apellidos,
        private string $rol,
        private string $tienda,
        private string $passwordHash
    ) {}

    public function getId(): int             { return $this->id; }
    public function getUsername(): string    { return $this->username; }
    public function getNombres(): string     { return $this->nombres; }
    public function getApellidos(): string   { return $this->apellidos; }
    public function getNombreCompleto(): string { return $this->nombres . ' ' . $this->apellidos; }
    public function getRol(): string         { return $this->rol; }
    public function getTienda(): string      { return $this->tienda; }

    public function verificarPassword(string $password): bool {
        return password_verify($password, $this->passwordHash);
    }
}