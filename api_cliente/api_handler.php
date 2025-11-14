<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/TokenController.php';

// Obtener token activo del clienteAPI
$tokenController = new TokenController();
$token = $tokenController->obtenerTokenActivo();

if (!$token) {
    echo json_encode([
        'status' => false,
        'type'   => 'error',
        'msg'    => 'No hay un token activo en la base de datos.'
    ]);
    exit();
}

// Validar token directo en HOTELESAPI
$validacion = $tokenController->validarTokenEnHOTELESAPI($token);

if (!$validacion || !$validacion['status']) {
    echo json_encode([
        'status' => false,
        'type'   => $validacion['type'] ?? 'error',
        'msg'    => $validacion['msg'] ?? 'Error al validar token.'
    ]);
    exit();
}

// Obtener búsqueda
$search = $_POST['search'] ?? '';

// Enviar solicitud a HOTELESAPI
$url = "http://localhost/hotelesAPI/api_handler.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'action' => 'buscarHoteles',
    'token'  => $token,
    'search' => $search
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http === 200) {
    echo $response;
} else {
    echo json_encode([
        'status' => false,
        'type'   => 'error',
        'msg'    => 'Error al conectar con HOTELESAPI.'
    ]);
}
?>
