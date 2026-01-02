<?php
session_start();
require_once 'config/db.php';

// Seguridad: Si no es admin, lo mandamos al index
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Obtener todos los usuarios
$stmt = $pdo->query("SELECT id, nombre_completo, username, rol FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">INVENTARIO PRO</a>
        <div class="navbar-nav ms-auto">
            <span class="nav-link text-white">Hola, <?= $_SESSION['user_name'] ?> (Admin)</span>
            <a class="btn btn-light btn-sm ms-3" href="auth/logout.php">Cerrar Sesión</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Crear Nuevo Usuario</h5>
                    <form action="acciones/crear_usuario.php" method="POST">
                        <div class="mb-3">
                            <label class="small text-muted">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted">Nombre de Usuario (Login)</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted">Rol del Sistema</label>
                            <select name="rol" class="form-select">
                                <option value="vendedor">Vendedor (Solo Inventario)</option>
                                <option value="admin">Administrador (Acceso Total)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Registrar Usuario</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-4">Usuarios Activos</h5>
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($usuarios as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['nombre_completo']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($user['username']) ?></span></td>
                                <td>
                                    <span class="badge <?= $user['rol'] == 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                        <?= strtoupper($user['rol']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                                        <a href="acciones/eliminar_usuario.php?id=<?= $user['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('¿Eliminar este acceso?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>