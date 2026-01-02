<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $rol = $_POST['rol'];
    
    // Ciframos la contraseña de forma segura
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $sql = "INSERT INTO usuarios (nombre_completo, username, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $username, $password, $rol]);
        
        header("Location: ../admin_usuarios.php?res=success");
    } catch (PDOException $e) {
        // Probablemente el nombre de usuario ya existe (UNIQUE en la BD)
        header("Location: ../admin_usuarios.php?res=error");
    }
}