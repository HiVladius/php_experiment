<?php
// --- LÓGICA (Backend) ---
require_once '../../vendor/autoload.php';

use App\Models\StatusManager;
use App\Config\Database;

try {
    $dbConfig = new Database(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );

    $pdo = $dbConfig->getConnection();
    $stmt = $pdo->query("SELECT nombre, precio, stock FROM productos, ORDER BY creado_en DESC");
    $productos = $stmt->fetchAll();
} catch (\Exception $e) {
    $productos = [];
    $error_db = $e->getMessage();
}

$userName = "Vladimir";
$titulo = "Panel de control de " . $userName;

// Inicializar el manejador de estados
$statusManager = new StatusManager('online');

// Cargar configuración de productos
$estadosDisponibles = $statusManager->getAvailableStates();
$productos = require 'config/products.php';

// Variable para mostrar mensajes de estado
$statusQuery = $_GET['status'] ?? null;

// Procesar cambio de estado si se envía desde el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuevo_estado'])) {
    $statusManager->setStatus($_POST['nuevo_estado']);
}

// Obtener el estado actual
$status = $statusManager->getCurrentStatus();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Tienda con PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body class="bg-gray-100 p-8">

    <!-- Header con selector de estado -->
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md mb-8">
        <?php require 'components/header.php'; ?>
    </div>

    <!-- Inventario de productos -->
    <?php require 'components/inventory.php'; ?>

    <!-- Formulario para agregar productos -->
    <?php require 'components/product-form.php'; ?>

    <!-- Alertas de estado -->
    <?php require 'components/status-alert.php'; ?>

</body>
</html>