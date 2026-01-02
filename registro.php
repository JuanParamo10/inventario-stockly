<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Inventario Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { border: none; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .btn-success { background-color: #10b981; border: none; padding: 10px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card p-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Crear Cuenta</h3>
            <p class="text-muted small">Regístrate para acceder al sistema</p>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2 small text-center">
                <?php 
                    if($_GET['error'] == 'duplicado') echo "El nombre de usuario ya existe.";
                    elseif($_GET['error'] == 'db') echo "Error de validación en la base de datos.";
                    else echo "Error: " . htmlspecialchars($_GET['detalle'] ?? 'Intente de nuevo');
                ?>
            </div>
        <?php endif; ?>

        <form action="auth/registro_process.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Nombre Completo</label>
                <input type="text" name="nombre_completo" class="form-control" required placeholder="Juan Paramo">
            </div>
            <div class="mb-3">
                <label class="form-label small fw-bold">Nombre de Usuario</label>
                <input type="text" name="username" class="form-control" required placeholder="juanp">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Contraseña</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-success w-100">Registrar Usuario</button>
            <div class="text-center mt-3">
                <a href="login.php" class="text-muted small text-decoration-none">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </form>
    </div>
</body>
</html>