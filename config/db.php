<?php
// Si Render nos da una URL de base de datos, la usamos, si no, usamos la local
$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // CONFIGURACIÓN PARA RENDER
    $dbopts = parse_url($databaseUrl);
    $host = $dbopts["host"];
    $port = $dbopts["port"];
    $user = $dbopts["user"];
    $password = $dbopts["pass"];
    $db = ltrim($dbopts["path"], '/');
} else {
    // CONFIGURACIÓN PARA TU MAC (Local)
    $host = '127.0.0.1';
    $db   = 'inventario_tienda';
    $user = 'tu_usuario';
    $password = 'admin123';
    $port = '5432';
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}