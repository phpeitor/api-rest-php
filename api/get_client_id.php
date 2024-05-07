<?php
    require_once('../includes/Client.class.php');
    require_once('../vendor/autoload.php');

    use Firebase\JWT\JWT;

    $static_token = token;
	
    $headers = apache_request_headers();
    if (isset($headers['Authorization']) && $headers['Authorization'] === "Bearer $static_token") {
		
		if ($_SERVER['REQUEST_METHOD'] === 'GET' ) {

			$request_uri = $_SERVER['REQUEST_URI'];
			$request_uri = trim(str_replace('/api-rest/api/', '', $request_uri), '/');
			$segments = explode('/', $request_uri);

			if (!empty($segments) && is_numeric($segments[count($segments) - 1])) {
				$client_id = $segments[count($segments) - 1];
				Client::get_client_by_id($client_id);
			} else {
				header('Content-Type: application/json; charset=utf-8');
				http_response_code(400);
				echo json_encode(array('message' => 'URL inválida. Se requiere un UsuarioId'));
			}
			
		}else {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(array('message' => 'La solicitud debe ser GET'));
        }
    } else {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode(array('message' => 'Acceso no autorizado. Se requiere un token JWT válido'));
    }

      
?>
