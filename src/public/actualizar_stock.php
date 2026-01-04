<?php
/**
 * actualizar_stock.php - Endpoint PUT para actualizar el stock de productos
 */
require_once '../../vendor/autoload.php';

use App\Config\Database;
use Dotenv\Dotenv;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Verificar que sea una solicitud PUT
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Leer los datos JSON del cuerpo de la solicitud
$input = json_decode(file_get_contents('php://input'), true);

// Validar datos
if (!isset($input['id']) || !isset($input['cantidad'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$productoId = intval($input['id']);
$cantidad = intval($input['cantidad']);
$operacion = $input['operacion'] ?? 'quitar'; // Por defecto es quitar

if ($cantidad <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'La cantidad debe ser mayor a 0']);
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

    // Obtener el stock actual del producto
    $stmt = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
    $stmt->execute([$productoId]);
    $producto = $stmt->fetch();

    if (!$producto) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
        exit;
    }

    $stockActual = $producto['stock'];

    // Validar según la operación
    if ($operacion === 'quitar') {
        // Validar que la cantidad no sea mayor al stock disponible
        if ($cantidad > $stockActual) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => "No puedes quitar $cantidad unidades. Solo hay $stockActual en stock."
            ]);
            exit;
        }
        $nuevoStock = $stockActual - $cantidad;
    } elseif ($operacion === 'agregar') {
        // Para agregar no hay límite, simplemente sumamos
        $nuevoStock = $stockActual + $cantidad;
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Operación no válida. Use "agregar" o "quitar".'
        ]);
        exit;
    }

    // Actualizar el stock en la base de datos
    $stmtUpdate = $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?");
    $stmtUpdate->execute([$nuevoStock, $productoId]);

    // Retornar respuesta exitosa
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Stock actualizado correctamente',
        'nuevoStock' => $nuevoStock
    ]);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    exit;
}
