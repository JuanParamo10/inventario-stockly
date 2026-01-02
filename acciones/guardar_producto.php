<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $cat = $_POST['id_categoria'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $minimo = $_POST['stock_minimo'];

    $sql = "INSERT INTO productos (nombre, id_categoria, precio, stock, stock_minimo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([$nombre, $cat, $precio, $stock, $minimo])) {
        header("Location: ../index.php?res=ok");
    } else {
        header("Location: ../index.php?res=error");
    }
}