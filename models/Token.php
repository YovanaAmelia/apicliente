<?php
// models/Token.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';

class Token {
    private $conexion;

    public function __construct() {
        $this->conexion = conectarDB();
    }

    public function getConexion() {
        return $this->conexion;
    }

    // Obtener todos los tokens
    public function obtenerTokens() {
        $resultado = $this->conexion->query("SELECT id_client_api, token FROM tokens_api");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener un token por ID
    public function obtenerTokenPorId($id_client_api) {
        $stmt = $this->conexion->prepare("SELECT id_client_api, token FROM tokens_api WHERE id_client_api = ?");
        $stmt->bind_param("i", $id_client_api);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    // Actualizar un token
    public function actualizarToken($id_client_api, $nuevo_token) {
        $stmt = $this->conexion->prepare("UPDATE tokens_api SET token = ? WHERE id_client_api = ?");
        $stmt->bind_param("si", $nuevo_token, $id_client_api);
        return $stmt->execute();
    }

    // Obtener el token activo de la base de datos
    public function obtenerTokenActivo() {
        $query = "SELECT token FROM tokens_api WHERE estado = 1 LIMIT 1";
        $resultado = $this->conexion->query($query);
        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return $fila['token'];
        }
        return null;
    }

    // Validar el token en la base de datos de HOTELESAPI
    public function validarTokenEnHOTELESAPI($token) {
        $url = 'http://localhost/hotelesAPI/api_handler.php?action=validarToken';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $token]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return [
                'status' => false,
                'type' => 'error',
                'msg' => 'Error al conectar con HOTELESAPI.'
            ];
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status' => false,
                'type' => 'error',
                'msg' => 'Respuesta inválida de HOTELESAPI.'
            ];
        }

        return $data;
    }
}
?>  