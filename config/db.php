<?php
// Detectar la URL de la base de datos de Render
$databaseUrl = getenv('DATABASE_URL');

try {
    if ($databaseUrl) {
        // CONFIGURACIÓN PARA RENDER (Forma simplificada)
        // PDO en PostgreSQL acepta la URL directa como DSN
        $pdo = new PDO($databaseUrl);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } else {
        // CONFIGURACIÓN PARA TU MAC (Local)
        $host = '127.0.0.1';
        $db   = 'inventario_tienda';
        $user = 'tu_usuario';
        $password = 'admin123';
        
        $dsn = "pgsql:host=$host;port=5432;dbname=$db";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}