<?php
// Configuración de la base de datos
$host     = 'localhost';
$db       = 'inventario_tienda'; // Asegúrate de que este sea el nombre en DataGrip
$user     = 'tu_usuario';          // Usuario por defecto
$password = 'tu_password';          // Tu contraseña de Docker/Postgres
$port     = '5432';              // Puerto por defecto

try {
    // Cadena de conexión para PostgreSQL (dsn)
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    
    // Crear instancia de PDO
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Opcional: echo "Conexión exitosa"; 
} catch (PDOException $e) {
    // Si hay error, lo muestra y detiene la ejecución
    die("Error de conexión: " . $e->getMessage());
}
?>