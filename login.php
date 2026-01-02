<?php
session_start();
// Si ya hay sesión iniciada, saltar directamente al panel
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema | Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #eef2f7; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: white; padding: 2.5rem; border-radius: 1rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 400px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="text-center fw-bold mb-4">Inventario Pro</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2 text-center small">Usuario o clave incorrectos</div>
        <?php endif; ?>

        <form action="auth/auth_process.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Usuario</label>
                <input type="text" name="username" class="form-control" placeholder="ej: paramo" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Ingresar</button>
        </form>
        <div class="text-center mt-3">
    <p class="small">¿No tienes cuenta? <a href="registro.php" class="text-decoration-none fw-bold">Regístrate aquí</a></p>
</div>
    </div>
</body>
</html>