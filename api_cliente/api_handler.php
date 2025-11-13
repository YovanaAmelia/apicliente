<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/TokenController.php';

// Obtener el token y la acción
$token = $_POST['token'] ?? '';
$action = $_GET['action'] ?? '';

// Validar que el token no esté vacío
if (empty($token)) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Token no proporcionado.'
    ]);
    exit();
}

// Validar el token en la base de datos de CLIENTEAPIY
$tokenController = new TokenController();
$tokenData = $tokenController->obtenerTokenPorToken($token);

if (!$tokenData) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Token no encontrado en CLIENTEAPIY.'
    ]);
    exit();
}

// URL del API de HOTELESAPI
define('API_HOTELES_URL', 'http://localhost/hotelesAPI/api_handler.php');

// Redirigir la petición al API de HOTELESAPI
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, API_HOTELES_URL . '?' . http_build_query(['action' => $action]));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $token, 'search' => $_POST['search'] ?? '']));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Devolver la respuesta del API de HOTELESAPI
if ($httpCode === 200) {
    echo $response;
} else {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Error al conectar con el API de HOTELESAPI.'
    ]);
}
?>
