<?php 
// api_cliente/api_handler.php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/TokenController.php';

$tokenController = new TokenController();
$token = $tokenController->obtenerTokenActivo();

if (empty($token)) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'No hay un token activo configurado en la base de datos.'
    ]);
    exit();
}

// Validar token en HOTELESAPI
$validacion = $tokenController->validarTokenEnHOTELESAPI($token);

if (empty($validacion) || !$validacion['status']) {
    echo json_encode([
        'status' => false,
        'type' => $validacion['type'] ?? 'error',
        'msg' => $validacion['msg'] ?? 'Error al validar el token en HOTELESAPI.'
    ]);
    exit();
}

// Búsqueda desde el buscador
$search = $_POST['search'] ?? '';

// URL DEL SERVIDOR CPANEL
$url = 'https://localhost/AdminHotel/api_handler.php';

// Enviar petición a HOTELESAPI
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
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Respuesta del API
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
