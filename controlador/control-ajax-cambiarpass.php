<?php
include('esta_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado


    require_once("../lib/password_compatibility_library.php");
	
		if (empty($_POST['user_id_mod'])){
			$errors[] = "Nombre de administrador vacio";
		}  elseif (empty($_POST['user_password_new3']) || empty($_POST['user_password_repeat3'])) {
            $errors[] = "Contraseña vacía";
        } elseif ($_POST['user_password_new3'] !== $_POST['user_password_repeat3']) {
            $errors[] = "la contraseña y la repetición de la contraseña no son lo mismo";
        }  elseif (
			 !empty($_POST['user_id_mod'])
			&& !empty($_POST['user_password_new3'])
            && !empty($_POST['user_password_repeat3'])
            && ($_POST['user_password_new3'] === $_POST['user_password_repeat3'])
        ) {
            require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
			
				$user_id=($_POST['user_id_mod']);
				$user_password = $_POST['user_password_new3'];
				$pregunta = $_POST['pregunta'];
				$respuesta = $_POST['respuesta'];
				
                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
				$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

                  


$user = mysqli_query($con,"SELECT usuario FROM usuario WHERE usuario = '".$user_id."' ");

$row = mysqli_fetch_array($user);
$name = $row['usuario'];
			

			if($user_id= $name){
$result = mysqli_query($con,"SELECT * FROM usuario WHERE pregunta_secreta = '".$pregunta."' and  respuesta_secreta='".$respuesta."' ");

			$row = mysqli_num_rows($result);
			 if ($row === 1) {
				# code...
			


					// write new user's data into database
                    $sql = "UPDATE usuario SET clave_seguridad='".$user_password_hash."',fecha_modificacion=now() WHERE usuario='".$user_id."'";
                    $query = mysqli_query($con,$sql);

                    // if user has been added successfully
                    if ($query) {
                        $messages[] = "contraseña ha sido modificada con éxito.";
                    } else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
                    }
                
        } else{
        	$errors[] = "Pregunta y respuesta no coincides";
        }    
        } else {
            $errors[] = "Este usuario no existe";
        }

		} 
		if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}

?>