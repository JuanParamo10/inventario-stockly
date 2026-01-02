<?php
// 1. Cargas iniciales
session_start();
require_once 'config/db.php';

// 2. Carga de Composer (Ruta absoluta recomendada)
$autoloadPath = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
} else {
    die("Error: No se encontró el archivo 'vendor/autoload.php'. Ejecuta 'composer install'.");
}

// 3. Importación de Clases (Esto elimina los errores de "Options" y "Dompdf")
use Dompdf\Dompdf;
use Dompdf\Options;

// 4. Inicialización
try {
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Arial');

    $dompdf = new Dompdf($options);

    // --- AQUÍ VA TU LÓGICA DE CONSULTA SQL ---
    $query = "SELECT p.*, c.nombre as categoria 
              FROM productos p 
              JOIN categorias c ON p.id_categoria = c.id 
              WHERE p.stock <= p.stock_minimo 
              ORDER BY p.stock ASC";
    $stmt = $pdo->query($query);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // --- AQUÍ VA TU HTML ---
    $html = "<h1>Reporte de Stock Bajo</h1>"; // (Usa el HTML que definimos antes)
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // 5. Salida al navegador
    $dompdf->stream("reporte.pdf", ["Attachment" => false]);

} catch (Exception $e) {
    echo "Error al generar el PDF: " . $e->getMessage();
}