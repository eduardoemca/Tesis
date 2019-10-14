<?php

if (isset($_SESSION['user_name'])) { $usuarionombre= $_SESSION['user_name']; } 
$_SESSION['session_id'] = session_id();

 if (isset($_SESSION['tipousuario'])) { $tipousuario= $_SESSION['tipousuario']; } 

if (isset($_SESSION['user_id'])) { $userid= $_SESSION['user_id']; } 


/*$timezone  = -4.5; //(GMT -5:00) EST (U.S. & Canada) 
echo gmdate("Y/m/j H:i:s", time() + 3600*($timezone+date("I"))); 
*/


/*$newDateTime = date('d-m-Y h:i A', strtotime($currentDateTime));

echo $currentDateTime;*/
class Menu
{



    public function __construct()
    {

        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
    }

    public function doLogout()
    { 
$_SESSION['session_id'] = session_id();


if (isset($_SESSION['user_id'])) { $userid= $_SESSION['user_id']; } 

$salida = "INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES('".$_SESSION['session_id']."','".$userid."','Cerrar Sesion','Login',now())";
       $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
         $this->db_connection->query($salida);



     $_SESSION = array();
        session_destroy();
  echo "<meta http-equiv='refresh' content='0;url=http://localhost:9090/eddi2.0'>";
        session_start();
           session_regenerate_id(true);

        // return a little feeedback message

        $this->messages[] = "Has sido desconectado.";

    }

  }






    $con=@mysqli_connect('localhost', 'root', '', 'eddibd');
    if(!$con){
        die("imposible conectarse: ".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        die("Conexion falló: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }

  $action1 = (isset($_REQUEST['action1'])&& $_REQUEST['action1'] !=NULL)?$_REQUEST['action1']:'';
      if($action1 == 'ajax1'){
        $reload = '../vista/menu.php';
        $sql = mysqli_query($con,"SELECT count(*) AS numero_filas FROM orden_compra");
        while($fila = mysqli_fetch_array($sql)){   echo $fila['numero_filas'];   }
      }

  $action2 = (isset($_REQUEST['action2'])&& $_REQUEST['action2'] !=NULL)?$_REQUEST['action2']:'';
      if($action2 == 'ajax2'){
        $reload = '../vista/menu.php';
        $sql = mysqli_query($con,"SELECT count(*) AS numero_filas FROM venta");
        while($fila = mysqli_fetch_array($sql)){   echo $fila['numero_filas'];   }
      }

  $action3 = (isset($_REQUEST['action3'])&& $_REQUEST['action3'] !=NULL)?$_REQUEST['action3']:'';
      if($action3 == 'ajax3'){
        $reload = '../vista/menu.php';
        $sql = mysqli_query($con,"SELECT count(*) AS numero_filas FROM usuario");
        while($fila = mysqli_fetch_array($sql)){   echo $fila['numero_filas'];   }
      }

  $action4 = (isset($_REQUEST['action4'])&& $_REQUEST['action4'] !=NULL)?$_REQUEST['action4']:'';
      if($action4 == 'ajax4'){
        $reload = '../vista/menu.php';
        $sql = mysqli_query($con,"SELECT count(*) AS numero_filas FROM compra");
        while($fila = mysqli_fetch_array($sql)){   echo $fila['numero_filas'];   }
      } 






?>
