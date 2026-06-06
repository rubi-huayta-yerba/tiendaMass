<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/ProductoRepository.php';

/**
 * Controlador para todo lo relacionado con productos del Mass.
 *
 * Su trabajo es:
 * 1. Recibir peticiones (a través del router).
 * 2. Pedir los datos al Model (Repository).
 * 3. Pasar esos datos a la View para que se muestren.
 */
class ProductoController {

    private ProductoRepository $repo;

    public function __construct() {
        $this->repo = new ProductoRepository();
    }

    /**
     * Acción: mostrar la lista de todos los productos.
     */
    public function listar(): void {
        // 1. Pedir datos al Model
        $productos = $this->repo->obtenerTodos();

        // 2. Pasar los datos a la View
        require __DIR__ . '/../views/productos/lista.php';
    }

    /**
     * Acción: Muestra el formulario para crear un nuevo producto.
     */
    public function nuevo(): void {
        require __DIR__ . '/../views/productos/crear.php';
    }

    /**
     * Acción: Procesa el formulario (POST) e inserta el producto.
     */
    public function guardar(): void {
        $codigo    = trim($_POST['codigo'] ?? '');
        $nombre    = trim($_POST['nombre'] ?? '');
        $marca     = trim($_POST['marca'] ?? '');
        $categoria = (int)  ($_POST['categoria'] ?? 0);
        $precio    = (float)($_POST['precio'] ?? 0);
        $stock     = (int)  ($_POST['stock'] ?? 0);

        // Validación de campos
        if ($codigo === '' || $nombre === '' || $precio <= 0) {
            $error = 'Completa código, nombre y un precio mayor a 0.';
            require __DIR__ . '/../views/productos/crear.php';
            return;
        }

        // El código de barras es ÚNICO: si ya existe, no se repite
        if ($this->repo->buscarPorCodigo($codigo) !== null) {
            $error = 'Ya existe un producto con ese código de barras.';
            require __DIR__ . '/../views/productos/crear.php';
            return;
        }

        // Ejecuta la inserción pasándole el array estructurado
        $this->repo->crear([
            'codigo'    => $codigo, 
            'nombre'    => $nombre, 
            'marca'     => $marca,
            'categoria' => $categoria, 
            'precio'    => $precio, 
            'stock'     => $stock,
        ]);

        // Redirección segura para evitar duplicados al recargar (Pattern PRG)
        header('Location: index.php?accion=catalogo');  
        exit;
    }
} 