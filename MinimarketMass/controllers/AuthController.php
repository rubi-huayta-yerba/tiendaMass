<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/UsuarioRepository.php';

class AuthController {

    public function mostrarLogin(string $error = ''): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function procesarLogin(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Inicializar el contador de intentos si no existe
        if (!isset($_SESSION['intentos'])) {
            $_SESSION['intentos'] = 0;
        }

        // Validar si ya superó el límite (demasiados intentos)
        if ($_SESSION['intentos'] >= 3) {
            $this->mostrarLogin('demasiados intentos');
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $this->mostrarLogin('Completa usuario y contraseña.');
            return;
        }

        $repo    = new UsuarioRepository();
        $usuario = $repo->buscarPorUsername($username);

        // Si el usuario no existe o la contraseña es incorrecta
        if ($usuario === null || !$usuario->verificarPassword($password)) {
            $_SESSION['intentos']++;
            
            if ($_SESSION['intentos'] >= 3) {
                $this->mostrarLogin('demasiados intentos');
            } else {
                $restantes = 3 - $_SESSION['intentos'];
                $this->mostrarLogin("Usuario o contraseña incorrectos. Intentos restantes: $restantes");
            }
            return;
        }

        // --- ¡LOGIN EXITOSO! ---
        
        // REQUERIMIENTO B1: Registrar acceso en la Base de Datos con prepared statement
        $repo->registrarAcceso($usuario->getId());

        // Reiniciamos los intentos acumulados al ingresar correctamente
        unset($_SESSION['intentos']);

        // Obtenemos la fecha y hora actual formateada para la visualización en la barra
        $fechaActual = date('d/m/Y H:i');

        // Estructura exacta solicitada para el helper usuarioActual()
        $_SESSION['usuario'] = [
            'id'             => $usuario->getId(),
            'username'       => $usuario->getUsername(),
            'nombre'         => $usuario->getNombreCompleto(), // Empatado con la nomenclatura estándar
            'rol'            => $usuario->getRol(),
            'tienda'         => $usuario->getTienda(),
            'ultimo_acceso'  => $fechaActual // Guardada en sesión para el Requerimiento B2
        ];

        // Redirección segura al catálogo de productos
        header('Location: index.php?accion=catalogo');
        exit;
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = [];
        session_destroy();
        
        header('Location: index.php?accion=login');
        exit;
    }
}