<?php
    require_once("../lib/password_compatibility_library.php");

        // check login form contents

        if (empty($_POST['user_name'])) {
            $errors[] = "Campo de usuario vacio.";
        }
        elseif (empty($_POST['user_password_new'])) {
            $errors[] = "Campo de repetición de contraseña vacio.";
        } 
        elseif (empty($_POST['opcion'])) {
            $errors[] = "Pregunta no seleccionada";
        } 
        elseif (empty($_POST['respuesta'])) {
            $errors[] = "Campo de respuesta vacio";
        } 
        elseif (empty($_POST['user_password_new'])) {
            $errors[] = "Campo de repetición de contraseña vacio.";
        } 
        elseif ($_POST['user_password'] !== $_POST['user_password_new']) {
            $errors[] = "la contraseña y la repetición de la contraseña no son iguales";
        }
        elseif (
            !empty($_POST['user_name'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            && !empty($_POST['user_password'])
            && !empty($_POST['user_password_new'])
            && ($_POST['user_password'] === $_POST['user_password_new'])

        ) {

            require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
            require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

            // if no connection errors (= working database connection)
                // escape the POST stuff
                $user_name = mysqli_real_escape_string($con,(strip_tags($_POST["user_name"],ENT_QUOTES)));
                $date_modified=date("Y-m-d H:i:s");
                $user_password = $_POST['user_password_new'];
                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
                $respuesta =$_POST['respuesta'];
                // database query

                $sql = "SELECT * FROM usuario WHERE usuario = '" . $user_name . "' AND respuesta_secreta = '".$respuesta."' ";
                        
                $query_check_user_name = mysqli_query($con,$sql);
                $query_check_user=mysqli_num_rows($query_check_user_name);
                if ($query_check_user == 1) {

                    $sql = "UPDATE  usuario SET clave_seguridad='" . $user_password_hash . "'  where usuario = '" . $user_name . "' ";
                    $query_new_user_insert = mysqli_query($con,$sql);


                    if ($query_new_user_insert) {
                        $messages[] = "Se ha cambiado la clave del usuario con éxito.";
                    } else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
                    }
                } else {
                    $errors[] = "Lo sentimos , el nombre de usuario o respuesta no se encuentra en la base de datos";
                }
           
        } 




 if (isset($errors)){
            
            ?>
            <center><div class="alert" style="width:60%; background-color:#0B3954; color: white;border-radius:10px; padding: 5px; margin-top:50px">
                    <strong>Error!</strong> 
                    <?php
                        foreach ($errors as $error) {
                                echo $error;
                            }
                        ?>
            </div></center>
            <?php
            }
            if (isset($messages)){
                
                ?>

                     <center><div class="alert" style="width:60%; background-color:#4EA89E; color: white;border-radius:10px; padding: 5px; margin-top:50px">
                        <strong>¡Bien hecho!</strong>
                        <?php
                            foreach ($messages as $message) {
                                    echo $message;                            
                                }
                            ?>
                    </div></center>
                    <script type="text/javascript">
                        
                    location.reload();

                    </script>
                <?php

            }

?>


