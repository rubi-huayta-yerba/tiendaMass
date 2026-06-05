<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/ProductoRepository.php';

/**
 * Controlador para todo lo relacionado con productos del Mass.
 *
 * Su trabajo es:
 *   1. Recibir peticiones (a través del router).
 *   2. Pedir los datos al Model (Repository).
 *   3. Pasar esos datos a la View para que se muestren.
 *
 * NO hace lógica de negocio (eso vive en las clases del Model).
 * NO genera HTML directamente (eso vive en las Views).
 */
class ProductoController {

    private ProductoRepository $repo;

    public function __construct() {
        $this->repo = new ProductoRepository();
    }

    /**
     * Acción: mostrar la lista de todos los productos.
     * URL que la invoca: ?ruta=productos
     */
    public function listar(): void {
        // 1. Pedir datos al Model
        $productos = $this->repo->obtenerTodos();

        // 2. Pasar los datos a la View (AQUÍ ESTÁ LA LÍNEA 34 CORREGIDA)
        require __DIR__ . '/../views/productos/lista.php';
    }
    }
?>
