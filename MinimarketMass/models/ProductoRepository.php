<?php
declare(strict_types=1);
require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/../config/conexion.php';

class ProductoRepository {

    /**
     * Devuelve TODOS los productos del catálogo desde la BD.
     * @return Producto[]
     */
    public function obtenerTodos(): array {
        try {
            $pdo = getConexion();
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
                    (float) $f['precio'],
                    (int)   $f['stock']
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

    /**
     * 1. buscarPorNombre(string $termino): array
     * Devuelve los productos cuyo nombre contenga el texto buscado.
     * @return Producto[]
     */
    public function buscarPorNombre(string $termino): array {
        try {
            // Conectar
            $pdo = getConexion();

            // Consultar (Prepared Statement con LIKE)
            $stmt = $pdo->prepare(
                "SELECT codigo_barras AS codigo, nombre, precio, stock
                 FROM productos
                 WHERE nombre LIKE :termino
                 ORDER BY nombre"
            );

            // El comodín % se une en PHP, no en el SQL string
            $stmt->execute([':termino' => '%' . $termino . '%']);

            // Convertir filas en objetos Producto
            $productos = [];
            foreach ($stmt->fetchAll() as $f) {
                $productos[] = new Producto(
                    $f['codigo'],
                    $f['nombre'],
                    (float) $f['precio'],
                    (int)   $f['stock']
                );
            }
            return $productos;

        } catch (PDOException $e) {
            error_log('[ProductoRepository::buscarPorNombre] ' . $e->getMessage());
            return [];
        }
    }

    /**
     * 2. obtenerPorCategoria(int $categoriaId): array
     * Devuelve los productos pertenecientes a una categoría específica.
     * @return Producto[]
     */
    public function obtenerPorCategoria(int $categoriaId): array {
        try {
            // Conectar
            $pdo = getConexion();

            // Consultar (Filtrar por el campo categoria_id)
            $stmt = $pdo->prepare(
                "SELECT codigo_barras AS codigo, nombre, precio, stock
                 FROM productos
                 WHERE categoria_id = :id
                 ORDER BY nombre"
            );
            $stmt->execute([':id' => $categoriaId]);

            // Convertir filas en objetos Producto
            $productos = [];
            foreach ($stmt->fetchAll() as $f) {
                $productos[] = new Producto(
                    $f['codigo'],
                    $f['nombre'],
                    (float) $f['precio'],
                    (int)   $f['stock']
                );
            }
            return $productos;

        } catch (PDOException $e) {
            error_log('[ProductoRepository::obtenerPorCategoria] ' . $e->getMessage());
            return [];
        }
    }

    /**
     * 3. obtenerBajoStock(int $umbral): array
     * Devuelve los productos cuyo stock esté por debajo del límite, de menor a mayor.
     * @return Producto[]
     */
    public function obtenerBajoStock(int $umbral): array {
        try {
            // Conectar
            $pdo = getConexion();

            // Consultar (Usar menor estricto '<' y ordenar ascendentemente)
            $stmt = $pdo->prepare(
                "SELECT codigo_barras AS codigo, nombre, precio, stock
                 FROM productos
                 WHERE stock < :umbral
                 ORDER BY stock ASC"
            );
            $stmt->execute([':umbral' => $umbral]);

            // Convertir filas en objetos Producto
            $productos = [];
            foreach ($stmt->fetchAll() as $f) {
                $productos[] = new Producto(
                    $f['codigo'],
                    $f['nombre'],
                    (float) $f['precio'],
                    (int)   $f['stock']
                );
            }
            return $productos;

        } catch (PDOException $e) {
            error_log('[ProductoRepository::obtenerBajoStock] ' . $e->getMessage());
            return [];
        }
    }

    /**
     * 4. contarTotalProductos(): int
     * Devuelve la cantidad total de productos registrados en la tabla.
     */
    public function contarTotalProductos(): int {
        try {
            // Conectar
            $pdo = getConexion();

            // Consultar usando la función de agregación COUNT(*)
            $stmt = $pdo->query("SELECT COUNT(*) FROM productos");

            // PISTA: fetchColumn() trae directamente el único valor numérico
            $total = $stmt->fetchColumn();

            // Retornar casteado a entero (int)
            return $total !== false ? (int) $total : 0;

        } catch (PDOException $e) {
            error_log('[ProductoRepository::contarTotalProductos] ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * BONUS +2: obtenerMasCaros(int $limite): array
     * Devuelve los N productos con mayor precio en el catálogo.
     * Sirve para identificar rápidamente los productos de mayor valor.
     * @return Producto[]
     */
    public function obtenerMasCaros(int $limite): array {
        try {
            $pdo = getConexion();

            // Usamos ORDER BY precio DESC para poner los más caros arriba
            // Y LIMIT :limite para traer solo la cantidad que pidamos
            $stmt = $pdo->prepare(
                "SELECT codigo_barras AS codigo, nombre, precio, stock
                 FROM productos
                 ORDER BY precio DESC
                 LIMIT :limite"
            );

            // IMPORTANTE: PDO por defecto puede pasar el LIMIT como string y causar error en MySQL.
            // Para asegurar la regla de oro y evitar fallos, vinculamos el parámetro como entero estricto:
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();

            $productos = [];
            foreach ($stmt->fetchAll() as $f) {
                $productos[] = new Producto(
                    $f['codigo'],
                    $f['nombre'],
                    (float) $f['precio'],
                    (int)   $f['stock']
                );
            }
            return $productos;

        } catch (PDOException $e) {
            error_log('[ProductoRepository::obtenerMasCaros] ' . $e->getMessage());
            return [];
        }
    }
} 