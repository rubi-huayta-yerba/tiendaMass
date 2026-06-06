<?php
declare(strict_types=1);
require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/../config/conexion.php';

class UsuarioRepository {

    public function buscarPorUsername(string $username): ?Usuario {
        try {
            $pdo  = getConexion();
            $stmt = $pdo->prepare(
                "SELECT id, username, nombres, apellidos, rol, tienda, password_hash
                 FROM usuarios
                 WHERE username = :username AND activo = 1"
            );
            $stmt->execute([':username' => $username]);
            $f = $stmt->fetch();
            if ($f === false) return null;
            return new Usuario(
                (int) $f['id'], $f['username'], $f['nombres'], $f['apellidos'],
                $f['rol'], $f['tienda'], $f['password_hash']
            );
        } catch (PDOException $e) {
            error_log('[UsuarioRepository] ' . $e->getMessage());
            return null;
        }
    }

    /**
     * REQUERIMIENTO B1: Registrar acceso.
     * Actualiza la columna ultimo_acceso en la base de datos con la hora actual del servidor.
     */
    public function registrarAcceso(int $id): void {
        try {
            $pdo = getConexion();
            $stmt = $pdo->prepare(
                "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id"
            );
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log('[UsuarioRepository::registrarAcceso] ' . $e->getMessage());
        }
    }
}