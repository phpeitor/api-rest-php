<?php
    require_once('../includes/Client.class.php');
    require_once('../vendor/autoload.php');

    use Firebase\JWT\JWT;

     $static_token = token;

    $headers = apache_request_headers();
    if (isset($headers['Authorization']) && $headers['Authorization'] === "Bearer $static_token") {

   
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {

            $input_data = json_decode(file_get_contents('php://input'), true);
    
            if ( isset($input_data['id']) ) {
				
				$resultado = Client::is_usuario_all($input_data['id']);
				 
				if ($resultado) {
						
					Client::delete_client_by_id($input_data['id']);
					
				} else {
					
					header('Content-Type: application/json; charset=utf-8');
					http_response_code(400);
					echo json_encode(array('message' => 'El cliente no existe', 'success' => false));
				}
	
            } else {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(array('message' => 'Id usuario es requerido', 'success' => false));
            }
        } else {
    
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(400);
            echo json_encode(array('message' => 'La solicitud debe ser POST y tener el tipo de contenido application/json'));
        }

    } else {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode(array('message' => 'Acceso no autorizado. Se requiere un token JWT válido'));
    
    }
       

?>