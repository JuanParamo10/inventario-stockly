<?php
/**
 * 1. Cargar la conexión a la base de datos.
 * Usamos __DIR__ para asegurar que la ruta sea relativa a este archivo.
 */
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. Capturar y limpiar datos para evitar espacios accidentales
    $nombre = isset($_POST['nombre_completo']) ? trim($_POST['nombre_completo']) : '';
    $user = isset($_POST['username']) ? trim($_POST['username']) : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';

    // Validación básica: que no haya campos vacíos
    if (empty($nombre) || empty($user) || empty($pass)) {
        header("Location: ../registro.php?error=vacio");
        exit();
    }

    /**
     * 3. Encriptar la contraseña.
     * NUNCA guardes contraseñas en texto plano. BCRYPT genera un hash seguro.
     */
    $password_hash = password_hash($pass, PASSWORD_BCRYPT);

    try {
        /**
         * 4. Insertar en la base de datos PostgreSQL.
         * Se asigna el rol 'usuario' por defecto.
         */
       // ... parte del código dentro de registro_process.php
        $sql = "INSERT INTO usuarios (nombre_completo, username, password, rol) 
        VALUES (:nombre, :user, :pass, 'usuario')"; // 'usuario' en minúsculas
// ...
        $stmt = $pdo->prepare($sql);
        
        $resultado = $stmt->execute([
            ':nombre' => $nombre,
            ':user'   => $user,
            ':pass'   => $password_hash
        ]);

        if ($resultado) {
            // ÉXITO: Redirigir al login con un mensaje de confirmación
            header("Location: ../login.php?mensaje=registrado");
            exit();
        }

    } catch (PDOException $e) {
        /**
         * 5. Manejo de errores.
         * El error 23505 en PostgreSQL indica una violación de unicidad (el usuario ya existe).
         */
        if ($e->getCode() == 23505) {
            header("Location: ../registro.php?error=duplicado");
        } else {
            // Otro tipo de error de base de datos
            header("Location: ../registro.php?error=db&detalle=" . urlencode($e->getMessage()));
        }
        exit();
    }
} else {
    // Si intentan entrar al archivo sin usar el formulario
    header("Location: ../registro.php");
    exit();
}