<?php
/**
 * procesar.php - Procesa la inserción de nuevos productos
 */
require_once '../../vendor/autoload.php';

use App\Config\Database;
use Dotenv\Dotenv;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Validar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Obtener y validar los datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$precio = floatval($_POST['precio'] ?? 0);
$stock = intval($_POST['stock'] ?? 0);

// Validación básica
$errores = [];

if (empty($nombre)) {
    $errores[] = 'El nombre del producto es requerido';
}

if ($precio <= 0) {
    $errores[] = 'El precio debe ser mayor a 0';
}

if ($stock < 0) {
    $errores[] = 'El stock no puede ser negativo';
}

// Si hay errores, redirigir con mensaje de error
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    header('Location: index.php?status=error');
    exit;
}

try {
    // Conectar a la base de datos
    $dbConfig = new Database(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );

    $pdo = $dbConfig->getConnection();

    // Preparar e insertar el producto
    $stmt = $pdo->prepare("INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $precio, $stock]);

    // Redirigir con mensaje de éxito
    header('Location: index.php?status=success');
    exit;

} catch (\Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: index.php?status=error');
    exit;
}
