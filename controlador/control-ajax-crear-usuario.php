<?php
include('esta_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
    require_once("../lib/password_compatibility_library.php");
      
/*        if (empty($_POST['firstname'])){
            $errors[] = "Nombres vacíos";
        }  */ 
        if (empty($_POST['user_name'])) {
            $errors[] = "Nombre de usuario vacío";
        } 
        
        elseif (empty($_POST["tipousuario"]) && !isset($_POST["tipousuario"])){
            $errors[] = "no ha selecionado nada";
            return false;
        } 
        elseif (empty($_POST["telextension"]) && !isset($_POST["telextension"])){
            $errors[] = "no ha selecionado nada de la extension del telefono";
            return false;
        }
        elseif (empty($_POST["pregunta"]) && !isset($_POST["pregunta"])){
            $errors[] = "no ha selecionado nada de la extension del telefono";
            return false;
        } 
        elseif (empty($_POST["respuesta"])){
            $errors[] = "respuesta vacía";
            return false;
        } 
         elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $errors[] = "Contraseña vacía";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $errors[] = "la contraseña y la repetición de la contraseña no son iguales";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $errors[] = "La contraseña debe tener como mínimo 6 caracteres";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $errors[] = "Nombre de usuario no puede ser inferior a 2 o más de 64 caracteres";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $errors[] = "Nombre de usuario no encaja en el esquema de nombre: Sólo aZ y los números están permitidos , de 2 a 64 caracteres";
        } 

        elseif (empty($_POST['user_email'])) {
            $errors[] = "El correo electrónico no puede estar vacío";
        } elseif (strlen($_POST['user_email']) > 64) {
            $errors[] = "El correo electrónico no puede ser superior a 64 caracteres";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Su dirección de correo electrónico no está en un formato de correo electrónico válida";
            }
        elseif (empty($_POST['user_number'])) {
            $errors[] = "el numero de telefono esta vacio";
            }
            elseif (strlen($_POST['user_number']) < 7) {
            $errors[] = "el numero de telefono tiene que tener 7 caracteres";
        }

         elseif (
            !empty($_POST['user_name'])
/*            && !empty($_POST['firstname'])
            && !empty($_POST['lastname'])*/
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
           && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {
            require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
            require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
            
                // escaping, additionally removing everything that could be (html/javascript-) code
  /*              $firstname = mysqli_real_escape_string($con,(strip_tags($_POST["firstname"],ENT_QUOTES)));*/
                $telextension = mysqli_real_escape_string($con,(strip_tags($_POST['telextension'],ENT_QUOTES))); 
                $telefono = mysqli_real_escape_string($con,(strip_tags($_POST["user_number"],ENT_QUOTES)));
                $selectOption = mysqli_real_escape_string($con,(strip_tags($_POST['tipousuario'],ENT_QUOTES)));
                $pregunta = mysqli_real_escape_string($con,(strip_tags($_POST['pregunta'],ENT_QUOTES)));
                $respuesta = strtolower(mysqli_real_escape_string($con,(strip_tags($_POST['respuesta'],ENT_QUOTES)))); 
                $user_name = strtolower(mysqli_real_escape_string($con,(strip_tags($_POST["user_name"],ENT_QUOTES))));
                $user_email = strtolower(mysqli_real_escape_string($con,(strip_tags($_POST["user_email"],ENT_QUOTES))));
                $user_password = $_POST['user_password_new'];
                $date_added=date("Y-m-d H:i:s");


                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
                    
                // check if user or email address already exists
                $sql = "SELECT * FROM usuario WHERE usuario = '" . $user_name . "' and correo ='".$user_email."' ";
                $query_check_user_name = mysqli_query($con,$sql);
                $query_check_user=mysqli_num_rows($query_check_user_name);
                if ($query_check_user == 1) {
                    $errors[] = "Lo sentimos , el nombre de usuario y/o correo ya está en uso.";
                } else {
                    // write new user's data into database
                    $sql = "INSERT INTO usuario (fecha_registro,usuario,tipo_usuario,pregunta_secreta,respuesta_secreta, clave_seguridad, estado,correo,telefono)
                            VALUES('".$date_added."','" . $user_name . "','" . $selectOption . "','".$pregunta."','".$respuesta."', '" . $user_password_hash . "', 'ACTIVO','" . $user_email . "','58".$telextension."" . $telefono . "');";
                    $query_new_user_insert = mysqli_query($con,$sql);

                    // if user has been added successfully
                    if ($query_new_user_insert) {
    $subject="Creación de Usuario Sistema Eddy";     
    $headers = 'MIME-Version: 1.0'."\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
    $headers .= "From: noreply@eddy.onthewifi.com"; 

$variables = array();
$variables['name'] = $user_name;
$variables['tipo'] = $selectOption;
$variables['clave'] = $user_password;
$template = file_get_contents('../emailtemplate/creacionusuario.html');

foreach($variables as $key => $value)
{
    $template = str_replace('{{ '.$key.' }}', $value, $template);
}

    mail($user_email, $subject, $template, $headers); 
$textMessage="Saludos desde el sistema eddy su usuario " . $user_name . " con clave " . $user_password . " ha sido creado con exito";
//$mobileNumber=$_POST["userMobile"];
$apiKey = urlencode('iIwAm/UfnoU-L5CM19AaZeK0TghgYJ8Kox8AoCD5vu');
$telef="58".$telextension."". $telefono;
   // Message details
   //$numbers = array($mobileNumber);
   $sender = urlencode('TXTLCL');
   $mensaje = rawurlencode($textMessage);
  // $numbers = implode(',', $numbers);
   // Prepare data for POST request
$data = array('apikey' => $apiKey, 'numbers' => $telef, "sender" => $sender, "message" => $mensaje);
   // Send the POST request with cURL
  $ch = curl_init('https://api.txtlocal.com/send/');
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   $response = curl_exec($ch);
   curl_close($ch); 
   // Process your response here
   //echo $response;

                        $messages[] = "El usuario ha sido creado con éxito.";
                    } else {
                        $errors[] = "Lo sentimos , el nombre de usuario y/o correo ya está en uso.";
                    }
                }
            
        } else {
            $errors[] = "Un error desconocido ocurrió.";
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