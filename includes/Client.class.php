<?php
    require_once('Database.class.php');
	
	define('token', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IkZPUlRFTC5BVU5BIiwiY29tcGFueSI6IkJJVEVMIiwiZXhwIjoxNzE1MDI1MDY2fQ.voGPeR47VqOBsPJ29DkziTKPskc2_jBPOY4mKDoohZs');

    class Client{
        public static function create_client($id, $paterno, $materno, $nombres, $correo, $clave, $semilla){
            $database = new Database();
            $conn = $database->getConnection();
        
            $stmt = $conn->prepare('INSERT INTO usuario (id, paterno, materno, nombres, correo, clave, semilla, registro_fecha, actualizado_fecha) VALUES (:id, :paterno, :materno, :nombres, :correo, :clave, :semilla, sysdate(), now())');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':paterno', $paterno);
            $stmt->bindParam(':materno', $materno);
            $stmt->bindParam(':nombres', $nombres);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':clave', $clave);
            $stmt->bindParam(':semilla', $semilla);
    
            if ($stmt->execute()) {
                $last_id = $conn->lastInsertId();
        
                $response = array(
                    'id' => $last_id,
                    'paterno' => $paterno,
                    'materno' => $materno,
                    'nombres' => $nombres
                );
        
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(201);
                echo json_encode($response);
            } else {
                 
				header('Content-Type: application/json; charset=utf-8');
                http_response_code(500); 
                echo json_encode(array('message' => 'Error al insertar cliente'));
            }
        }

        public static function delete_client_by_id($id){
            $database = new Database();
            $conn = $database->getConnection();

            $stmt = $conn->prepare('DELETE FROM usuario WHERE id=:id');
            $stmt->bindParam(':id',$id);
			
			
			if ($stmt->execute()) {
            
                $response = array(
                    'id' => $id,
                    'success' => true
                );
        
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(201);
                echo json_encode($response);
            } else {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500); 
                echo json_encode(array('message' => 'Error al actualizar usuario', 
                                        'success' => false
                ));
            }
			
			
        }

        public static function get_all_clients(){
            $database = new Database();
            $conn = $database->getConnection();
            $stmt = $conn->prepare('SELECT * FROM usuario');
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($result) {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(200); 
                    echo json_encode($result); 
                } else {
					header('Content-Type: application/json; charset=utf-8');
                    http_response_code(404); 
                    echo json_encode(array('message' => 'No se encontraron clientes')); 
                }
            } else {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500); 
                echo json_encode(array('message' => 'Error al obtener los clientes')); 
            }
        }


        public static function get_client_by_id($id){
            $database = new Database();
            $conn = $database->getConnection();
            
            $stmt = $conn->prepare('SELECT * FROM usuario WHERE id = :id');
            $stmt->bindParam(':id', $id);
            

            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(200); 
                    echo json_encode($result); 
                } else {
                    header('Content-Type: application/json; charset=utf-8');
                    http_response_code(404); 
                    echo json_encode(array('message' => 'Usuario no encontrado')); 
                }
            } else {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500); 
                echo json_encode(array('message' => 'Error al obtener el usuario'));
            }

        }
        

        public static function update_client($paterno, $materno, $nombres, $id){
            $database = new Database();
            $conn = $database->getConnection();

            $stmt = $conn->prepare('UPDATE usuario SET paterno=:paterno,materno=:materno,nombres=:nombres,actualizado_fecha=sysdate() WHERE id=:id');
            $stmt->bindParam(':paterno',$paterno);
            $stmt->bindParam(':nombres',$nombres);
            $stmt->bindParam(':materno',$materno);
            $stmt->bindParam(':id',$id);
    
            if ($stmt->execute()) {
            
                $response = array(
                    'id' => $id,
                    'success' => true
                );
        
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(201);
                echo json_encode($response);
            } else {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500); 
                echo json_encode(array('message' => 'Error al actualizar usuario', 
                                        'success' => false
                ));
            }

        }

        public static function is_usuario($id) {
            $database = new Database();
            $conn = $database->getConnection();
            
            $stmt_check = $conn->prepare('SELECT (date(sysdate()) = date(actualizado_fecha) ) as valida FROM usuario WHERE id=:id');
            $stmt_check->bindParam(':id', $id);
            $stmt_check->execute();
			
			$usuario = $stmt_check->fetch(PDO::FETCH_ASSOC);
			$found = $stmt_check->rowCount() > 0;
			
			if ($found) {
				return array('valida' => $usuario['valida'], 'found' => $found);
			} else {
				return array('valida' => false, 'found' => false);
			}
	
        }
		
		public static function is_usuario_all($id) {
            $database = new Database();
            $conn = $database->getConnection();
            
            $stmt_check = $conn->prepare('SELECT * FROM usuario WHERE id=:id');
            $stmt_check->bindParam(':id', $id);
            $stmt_check->execute();
			$usuario = $stmt_check->fetch(PDO::FETCH_ASSOC);
			return $usuario;
	
        }
		
		public static function is_correo($correo) {
            $database = new Database();
            $conn = $database->getConnection();
            
            $stmt_check = $conn->prepare('SELECT * FROM usuario WHERE correo=:correo');
            $stmt_check->bindParam(':correo', $correo);
            $stmt_check->execute();
			$usuario = $stmt_check->fetch(PDO::FETCH_ASSOC);
			return $usuario;
	
        }


    }

?>