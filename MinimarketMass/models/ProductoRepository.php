<?php
declare(strict_types=1);
require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/../config/conexion.php';

/**
 * Repositorio de productos del Minimarket Mass.
 *
 * SESIÓN 4: usaba un array hardcoded.
 * SESIÓN 5: ahora lee de MySQL con PDO.
 *
 * El cambio es INTERNO: los métodos siguen devolviendo lo mismo
 * (array de Producto o ?Producto). Por eso el Controller y la View
 * NO se tocan. Ese es el payoff del MVC.
 */
class ProductoRepository {

    /**
     * Devuelve TODOS los productos del catálogo desde la BD.
     * @return Producto[]
     */
    public function obtenerTodos(): array {
        try {
            $pdo = getConexion();

            // codigo_barras AS codigo → la columna real es codigo_barras,
            // pero la clase Producto espera "codigo". El alias los empata.
            $stmt = $pdo->query(
                "SELECT codigo_barras AS codigo, nombre, precio, stock
                 FROM productos
                 ORDER BY nombre"
            );

            $productos = [];
            foreach ($stmt->fetchAll() as $f) {
                $productos[] = new Producto(
                    $f['codigo'],
                    $f['nombre'],
                    (float) $f['precio'],   // MySQL devuelve TODO como string
                    (int)   $f['stock']     // por eso casteamos a float/int
                );
            }
            return $productos;

        } catch (PDOException $e) {
            error_log('[ProductoRepository::obtenerTodos] ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca UN producto por su código.
     * Usa PREPARED STATEMENT → seguro contra SQL injection.
     */
    public function buscarPorCodigo(string $codigo): ?Producto {
        try {
            $pdo = getConexion();

            $stmt = $pdo->prepare(
                "SELECT codigo_barras AS codigo, nombre, precio, stock
                 FROM productos
                 WHERE codigo_barras = :codigo"
            );
            $stmt->execute([':codigo' => $codigo]);

            $fila = $stmt->fetch();
            if ($fila === false) {
                return null;
            }

            return new Producto(
                $fila['codigo'],
                $fila['nombre'],
                (float) $fila['precio'],
                (int)   $fila['stock']
            );

        } catch (PDOException $e) {
            error_log('[ProductoRepository::buscarPorCodigo] ' . $e->getMessage());
            return null;
        }
    }
}