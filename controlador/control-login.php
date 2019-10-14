<?php

/**
 * Class login
 * handles the user's login and logout process
 */

class Login
{
    /**
     * @var object The database connection
     */
    private $db_connection = null;
    /**
     * @var array Collection of error messages
     */
    public $errors = array();
    /**
     * @var array Collection of success / neutral messages
     */
    public $messages = array();
    
    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {
        // create/read session, absolutely necessary
         //session_set_cookie_params('5');
        session_start();
/*         $_SESSION['LAST_ACTIVITY'] = time();
$session_id = session_id();
  if (isset($_SESSION['LAST_ACTIVITY'])){
echo $session_id;
  if ($_SESSION['LAST_ACTIVITY'] + 1 * 60 < time()) {
     
    $session_id = session_id();
    $salida = "UPDATE bitacora SET fecha_salida=now() WHERE session_id='".$session_id."' ";
    $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $this->db_connection->query($salida);
     // session timed out
     session_unset();     // unset $_SESSION variable for the run-time 
     session_destroy();   // destroy session data in storage
  } else {

    // session ok
 }
}*/
    

        // check the possible login actions:
        // if user tried to log out (happen when user clicks logout button)
/*        if (isset($_GET["logout"])) {
            $this->doLogout();
        }*/
        // login via post data (if user just submitted a login form)
        if (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }

    /**
     * log in with post data
     */
    private function dologinWithPostData()
    {
        // check login form contents
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Campo de usuario vacio.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Campo de contraseña vacio.";
        } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            // create a database connection, using the constants from config/db.php (which we loaded in index.php)
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escape the POST stuff
                $user_name1 = $this->db_connection->real_escape_string($_POST['user_name']);
                $user_name = strtolower($user_name1);
                // database query, getting all the info of the selected user (allows login via email address in the
                // username field)
                $sql = "SELECT user_id, usuario,tipo_usuario, clave_seguridad
                        FROM usuario
                        WHERE usuario = '" . $user_name . " ';";
                        $session_id= session_id(); 
                              
                $result_of_login_check = $this->db_connection->query($sql);

                // if this user exists
                if ($result_of_login_check->num_rows == 1) {

                    // get result row (as an object)
                    $result_row = $result_of_login_check->fetch_object();

                    // using PHP 5.5's password_verify() function to check if the provided password fits
                    // the hash of that user's password
                    if (password_verify($_POST['user_password'], $result_row->clave_seguridad)) {

                        // write user data into PHP SESSION (a file on your server)
                        $_SESSION['user_id'] = $result_row->user_id;
            $_SESSION['user_name'] = $result_row->usuario;
                        $_SESSION['session_id'] = session_id();
                       /* $_SESSION['user_email'] = $result_row->user_email;*/
                        $_SESSION['user_login_status'] = 1;
                        $_SESSION['tipousuario'] =  $result_row->tipo_usuario;
			$date_added=date("Y-m-d H:i:s");
                        $insertar = "INSERT INTO bitacora(fecha_registro,session_id) VALUES (now(),'".$session_id."') ";
          $sql=" INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES('".$session_id."','".$_SESSION['user_id']."','Inicio de sesion','Login',now())";
                        $this->db_connection->query($insertar);
                        $this->db_connection->query($sql);

                    } else {
                        $this->errors[] = "contraseña no coinciden.";
                    }
                } else {
                    $this->errors[] = "Usuario y/o contraseña no coinciden.";
                }
            } else {
                $this->errors[] = "Problema de conexión de base de datos.";
            }
        }
    }

    /**
     * perform the logout
     */
/*    public function doLogout()
    {  $session_id = session_id();
               //echo $session_id;
        // delete the session of the user
       $salida = "INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES(".$session_id.",'1','Cerrar Sesion','Login',now())";
       $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->db_connection->query($salida);
        $_SESSION = array();
        setcookie( session_name() ,"",0,"/");
        session_destroy();
        session_start();
           session_regenerate_id(true);

        // return a little feeedback message

        $this->messages[] = "Has sido desconectado.";

    }
*/
    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
        // default return
        return false;
    }
}
