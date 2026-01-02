<?php
require_once 'config/db.php';

echo "<h2>Estado de la Conexión</h2>";
echo "<p style='color:green;'>✅ Conexión establecida correctamente.</p>";

echo "<h2>Tablas detectadas:</h2>";
try {
    $query = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tablas = $query->fetchAll();

    if (count($tablas) > 0) {
        echo "<ul>";
        foreach ($tablas as $t) {
            echo "<li><strong>" . $t['table_name'] . "</strong></li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color:red;'>⚠️ No se encontraron tablas. ¿Ejecutaste el script en DataGrip?</p>";
    }
} catch (Exception $e) {
    echo "❌ Error al consultar tablas: " . $e->getMessage();
}
?>