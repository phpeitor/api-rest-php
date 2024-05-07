<?php
    require_once('../includes/Client.class.php');
    require_once('../vendor/autoload.php');

    use Firebase\JWT\JWT;

     $static_token = token;

    $headers = apache_request_headers();
    if (isset($headers['Authorization']) && $headers['Authorization'] === "Bearer $static_token") {

   
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') === 0) {

            $input_data = json_decode(file_get_contents('php://input'), true);
			
			$parametros_esperados = ['id', 'materno', 'paterno', 'nombres', 'correo', 'clave', 'semilla'];

            if (count(array_diff(array_keys($input_data), $parametros_esperados)) === 0) {
				
				 if (!filter_var($input_data['correo'], FILTER_VALIDATE_EMAIL)) {
					header('Content-Type: application/json; charset=utf-8');
					http_response_code(400);
					echo json_encode(array('message' => 'El formato del correo electrónico no es válido', 'success' => false));
					exit; 
				}

				$resultado = Client::is_usuario_all($input_data['id']);
				$correo_result = Client::is_correo($input_data['correo']);

				if ($resultado) {

					header('Content-Type: application/json; charset=utf-8');
					http_response_code(400);
					echo json_encode(array('message' => 'Id usuario ya existe', 'success' => false));
				}  else if ($correo_result) {
					
					header('Content-Type: application/json; charset=utf-8');
					http_response_code(400);
					echo json_encode(array('message' => 'Correo electrónico ya existe', 'success' => false));
				} else {

					Client::create_client($input_data['id'], $input_data['paterno'], $input_data['materno'], $input_data['nombres'], $input_data['correo'], $input_data['clave'], $input_data['semilla']);
				}
			} else {
				header('Content-Type: application/json; charset=utf-8');
				http_response_code(400);
				echo json_encode(array('message' => 'Parámetros no válidos. Solo se permiten los siguientes campos: id, materno, paterno, nombres, correo, clave, semilla', 'success' => false));
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