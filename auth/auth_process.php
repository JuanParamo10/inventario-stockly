<?php
// 1. Iniciar la sesión para guardar los datos del usuario
session_start();

/**
 * 2. Cargar la conexión a la base de datos.
 * Usamos __DIR__ para que PHP sepa exactamente dónde está parado
 * y suba un nivel (../) para encontrar la carpeta config.
 */
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Obtener datos del formulario y limpiar espacios en blanco (trim)
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validar que no lleguen vacíos
    if (empty($username) || empty($password)) {
        header("Location: ../login.php?error=vacio");
        exit();
    }

    try {
        // 4. Preparar la consulta para buscar al usuario por su nombre de usuario
        $sql = "SELECT id, nombre_completo, username, password, rol FROM usuarios WHERE username = :user";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user' => $username]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        /**
         * 5. EL NÚCLEO DEL LOGIN: password_verify
         * Esta función toma la contraseña escrita ($password)
         * y la compara con el hash largo guardado en la DB ($user['password']).
         */
        if ($user && password_verify($password, $user['password'])) {
            
            // 6. Si es correcto, regeneramos el ID de sesión por seguridad
            session_regenerate_id(true);

            // 7. Guardamos los datos en la variable global $_SESSION
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre_completo'];
            $_SESSION['user_rol'] = $user['rol'];

            // 8. REDIRECCIÓN DE ÉXITO: Vamos al index de la raíz
            header("Location: ../index.php");
            exit();

        } else {
            // 9. REDIRECCIÓN DE ERROR: Usuario o clave no coinciden
            header("Location: ../login.php?error=incorrecto");
            exit();
        }

    } catch (PDOException $e) {
        // En caso de que la base de datos falle
        die("Error de conexión en el login: " . $e->getMessage());
    }

} else {
    // Si alguien intenta entrar a este archivo escribiendo la URL directamente
    header("Location: ../login.php");
    exit();
}