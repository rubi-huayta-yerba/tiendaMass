<?php
declare(strict_types=1);

/**
 * Conexión a la base de datos del Minimarket Mass
 * Entorno: Laragon (MySQL 8.4 en puerto 3306)
 */
function getConexion(): PDO
{
    $host    = 'localhost';
    $puerto  = 3307;
    $bd      = 'minimarket_mass';
    $usuario = 'root';
    $clave   = '';            // Laragon: root sin contraseña
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$puerto;dbname=$bd;charset=$charset";

    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // que reviente con excepción si algo falla
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // resultados como arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                   // prepared statements reales (más seguro)
    ];

    return new PDO($dsn, $usuario, $clave, $opciones);
}