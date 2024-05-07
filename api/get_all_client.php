<?php
    require_once('../includes/Client.class.php');
    require_once('../vendor/autoload.php');

    use Firebase\JWT\JWT;

    $static_token = token;

    $headers = apache_request_headers();
    if (isset($headers['Authorization']) && $headers['Authorization'] === "Bearer $static_token") {

		if ($_SERVER['REQUEST_METHOD'] === 'GET' ) {
			
			Client::get_all_clients();
			
		}else {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(array('message' => 'La solicitud debe ser GET'));
        }
		
	}else{
		header('Content-Type: application/json; charset=utf-8');
		http_response_code(401);
		echo json_encode(array('message' => 'Acceso no autorizado. Se requiere un token JWT válido'));

	}

    

?>