<?php
require_once 'config/db.php';

try {
    // Reporte lateral (Top 5 con menos stock)
    $queryReporte = "SELECT p.nombre, p.stock, c.nombre as categoria 
                     FROM productos p 
                     JOIN categorias c ON p.id_categoria = c.id 
                     ORDER BY p.stock ASC LIMIT 5";
    $productosCriticos = $pdo->query($queryReporte)->fetchAll();

    // Consulta general
    $query = "SELECT p.*, c.nombre as categoria_nombre 
              FROM productos p 
              LEFT JOIN categorias c ON p.id_categoria = c.id 
              ORDER BY p.id DESC";
    $productos = $pdo->query($query)->fetchAll();
    $categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre ASC")->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Stockly Pro | Mobile Ready</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/all.min.css">
    
    <style>
        :root { --bg: #0f172a; --card: #1e293b; --neon-blue: #00f2ff; --neon-green: #39ff14; }
        
        body { 
            background-color: var(--bg); 
            color: #ffffff; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        /* Ajustes de Responsividad */
        .container-main { padding: 10px; }
        
        @media (max-width: 768px) {
            .navbar-brand { font-size: 1.2rem !important; }
            .product-title { font-size: 1.1rem !important; }
            .price-text { font-size: 1.3rem !important; }
            .panel-reporte { margin-bottom: 20px; }
        }

        /* Alertas de Stock */
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        .alerta-stock { border: 2px solid #ef4444 !important; animation: pulse-red 2s infinite; }

        .product-card { 
            background: var(--card); 
            border-radius: 15px; 
            border: 1px solid rgba(255,255,255,0.1);
            height: 100%;
        }

        .product-title { color: var(--neon-blue); font-weight: 800; }
        
        .search-bar { 
            background: #2d3a4f !important; 
            border: 2px solid #3b82f6 !important; 
            color: white !important;
            border-radius: 12px;
            font-size: 16px; /* Evita zoom automático en iPhone */
        }

        .btn-action { padding: 12px; font-weight: bold; border-radius: 10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark sticky-top py-2 shadow-sm" style="background: #1e293b;">
    <div class="container-fluid px-3">
        <a class="navbar-brand fw-800" href="#">TIENDA CAROLINA</a>
        <button class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalProducto" onclick="prepararAgregar()">+ NUEVO</button>
    </div>
</nav>

<div class="container-fluid container-main mt-3">
    <div class="row">
        
        <div class="col-12 col-lg-3">
            <div class="p-3 mb-4" style="background: rgba(255,255,255,0.05); border-radius: 15px;">
                <h6 class="text-warning fw-bold small mb-3"><i class="fas fa-chart-pie me-2"></i>REPOSICIÓN</h6>
                <?php foreach($productosCriticos as $pc): ?>
                    <div class="d-flex justify-content-between mb-2 p-2 bg-dark rounded-3">
                        <span class="small text-truncate" style="max-width: 120px;"><?= $pc['nombre'] ?></span>
                        <span class="badge <?= $pc['stock'] <= 3 ? 'bg-danger' : 'bg-secondary' ?>"><?= $pc['stock'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-12 col-lg-9">
            <div class="mb-4">
                <input type="text" id="busqueda" class="form-control form-control-lg search-bar" placeholder="Buscar producto o pasillo...">
            </div>

            <div class="row g-3" id="contenedor">
                <?php foreach ($productos as $p): 
                    $critico = ($p['stock'] <= 3 && $p['stock'] > 0);
                    $agotado = ($p['stock'] <= 0);
                ?>
                <div class="col-12 col-md-6 col-xxl-4 item-card" data-search="<?= strtolower($p['nombre'] . ' ' . $p['categoria_nombre'] . ' ' . $p['ubicacion']) ?>">
                    <div class="card product-card p-3 <?= $critico ? 'alerta-stock' : '' ?>">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="badge bg-primary opacity-75 small"><?= $p['categoria_nombre'] ?></span>
                            <span class="small text-white-50"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($p['ubicacion']) ?></span>
                        </div>
                        
                        <h4 class="product-title mb-1"><?= htmlspecialchars($p['nombre']) ?></h4>
                        <div class="h3 fw-bold mb-3" style="color: var(--neon-green)">$<?= number_format($p['precio'], 2) ?></div>
                        
                       <span style="color: #94a3b8;">Stock: </span>
<b class="fs-5 text-white"><?= $p['stock'] ?></b>

                        <div class="d-flex gap-2">
                            <a href="acciones.php?vender=<?= $p['id'] ?>" class="btn btn-warning flex-grow-1 btn-action <?= $agotado ? 'disabled' : '' ?>">VENDER</a>
                            <button class="btn btn-outline-info btn-action" onclick='abrirEditar(<?= json_encode($p) ?>)'><i class="fas fa-edit"></i></button>
                            <a href="acciones.php?eliminar=<?= $p['id'] ?>" class="btn btn-outline-danger btn-action" onclick="return confirm('¿Borrar?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <form action="acciones.php" method="POST" class="modal-content bg-dark border-0">
            <div class="modal-header border-0">
                <h5 class="text-white fw-bold">Gestión de Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="mId">
                <div class="mb-3">
                    <label class="text-white-50 small fw-bold">NOMBRE</label>
                    <input type="text" name="nombre" id="mNombre" class="form-control search-bar" required>
                </div>
                <div class="mb-3">
                    <label class="text-white-50 small fw-bold">UBICACIÓN (PASILLO/ESTANTE)</label>
                    <input type="text" name="ubicacion" id="mUbicacion" class="form-control search-bar">
                </div>
                <div class="mb-3">
                    <label class="text-white-50 small fw-bold">CATEGORÍA</label>
                    <select name="id_categoria" id="mCat" class="form-select search-bar">
                        <?php foreach($categorias as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-6"><label class="text-white-50 small fw-bold">PRECIO</label><input type="number" step="0.01" name="precio" id="mPrecio" class="form-control search-bar"></div>
                    <div class="col-6"><label class="text-white-50 small fw-bold">STOCK</label><input type="number" name="stock" id="mStock" class="form-control search-bar"></div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary w-100 fw-bold py-3">GUARDAR CAMBIOS</button>
            </div>
        </form>
    </div>
</div>

<script>
    function prepararAgregar() {
        document.getElementById('mId').value = "";
        document.getElementById('mNombre').value = "";
        document.getElementById('mUbicacion').value = "Bodega";
        document.getElementById('mPrecio').value = "";
        document.getElementById('mStock').value = "";
    }

    function abrirEditar(p) {
        document.getElementById('mId').value = p.id;
        document.getElementById('mNombre').value = p.nombre;
        document.getElementById('mUbicacion').value = p.ubicacion;
        document.getElementById('mCat').value = p.id_categoria;
        document.getElementById('mPrecio').value = p.precio;
        document.getElementById('mStock').value = p.stock;
        new bootstrap.Modal(document.getElementById('modalProducto')).show();
    }

    document.getElementById('busqueda').addEventListener('keyup', function() {
        let q = this.value.toLowerCase();
        document.querySelectorAll('.item-card').forEach(card => {
            card.style.display = card.getAttribute('data-search').includes(q) ? "block" : "none";
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>