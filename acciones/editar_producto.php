<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cat = $_POST['id_categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $minimo = $_POST['stock_minimo'];

    try {
        $sql = "UPDATE productos SET 
                nombre = ?, 
                id_categoria = ?, 
                precio = ?, 
                stock = ?, 
                stock_minimo = ?,
                actualizado_en = CURRENT_TIMESTAMP 
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $cat, $precio, $stock, $minimo, $id]);

        header("Location: ../index.php?edit=success");
    } catch (PDOException $e) {
        header("Location: ../index.php?edit=error");
    }
}