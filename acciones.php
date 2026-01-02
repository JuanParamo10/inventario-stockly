<?php
/**
 * MOTOR DE ACCIONES - STOCKLY v2.0
 * Procesa: Ventas, Eliminación, Creación y Edición.
 */

require_once 'config/db.php';

// --- 1. ACCIÓN: VENDER (Restar 1 al stock) ---
if (isset($_GET['vender'])) {
    $id = intval($_GET['vender']);
    
    try {
        // Solo resta si el stock es mayor a 0 para evitar inventario negativo
        $stmt = $pdo->prepare("UPDATE productos SET stock = stock - 1 WHERE id = ? AND stock > 0");
        $stmt->execute([$id]);
        
        header("Location: index.php?res=venta_ok");
        exit();
    } catch (PDOException $e) {
        die("Error al procesar venta: " . $e->getMessage());
    }
}

// --- 2. ACCIÓN: ELIMINAR PRODUCTO ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    
    try {
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: index.php?res=eliminado");
        exit();
    } catch (PDOException $e) {
        die("Error al eliminar: " . $e->getMessage());
    }
}

// --- 3. ACCIÓN: GUARDAR O ACTUALIZAR (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos los datos del formulario (incluyendo la nueva ubicación)
    $id        = !empty($_POST['id']) ? intval($_POST['id']) : null;
    $nombre    = trim($_POST['nombre']);
    $ubicacion = trim($_POST['ubicacion']); // El nuevo campo
    $id_cat    = intval($_POST['id_categoria']);
    $precio    = floatval($_POST['precio']);
    $stock     = intval($_POST['stock']);

    try {
        if ($id) {
            // ACTUALIZAR PRODUCTO EXISTENTE
            $sql = "UPDATE productos SET 
                        nombre = ?, 
                        ubicacion = ?, 
                        id_categoria = ?, 
                        precio = ?, 
                        stock = ? 
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $ubicacion, $id_cat, $precio, $stock, $id]);
            $mensaje = "editado";
        } else {
            // INSERTAR NUEVO PRODUCTO
            $sql = "INSERT INTO productos (nombre, ubicacion, id_categoria, precio, stock) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $ubicacion, $id_cat, $precio, $stock]);
            $mensaje = "creado";
        }

        header("Location: index.php?res=" . $mensaje);
        exit();
        
    } catch (PDOException $e) {
        die("Error al guardar: " . $e->getMessage());
    }
}

// Si alguien intenta entrar a este archivo directamente sin acciones
header("Location: index.php");
exit();