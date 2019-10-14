<?php
  
  session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1)
  {
    header("location: login.php");
    exit;
  }
  
  require '../controlador/controlador-cliente.php';
  $control= new client();
?>
  <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Bitacora</title>
        <link rel="stylesheet" type="text/css" href="../css/ripple.css">
        <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="../css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../css/skins/_all-skins.css">
    </head>
<body>

    <header>
    </header>
  <!---------------------- MODAL BITACORA  -------------------->
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" id="modal-tabla">
      <!-- Modal content-->
      <div class="modal-content text-center">
        <div class="modal-header" id="modal-superior">
          <button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
            <center>
              <h3>
                <b>Movimientos de Usuarios</b>
              </h3>
            </center>
        </div>
      <div class="table-responsive" id="tabla_agregar">

        <div  style="width:80%;margin: 0 auto;">
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                 <input class="form-control" id="myInput1" style="width: 100%" type="text" placeholder="Buscar Movimientos...">
                <br>
              </div>
            </div>

            <br><br>
        <div class="modal-body">
          <div class="outer_divdetalle">
 
          </div>

          <div class="modal-footer">
            <button class="btn btn-danger" style="width: 100%;" id="Cerrar-modal-tabla" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  <!---------------------- FINAL MODAL MOVIMIENTO USUARIO -------------------->
<div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px; width: 100%">

        <div class="panel-heading">
            <center>
          <h3 style="padding:0px;margin:0px;"><i class='glyphicon glyphicon-edit'></i> BITACORA</h3>
            </center>
        </div>

<!--        SPINNER -->
      <div id="cargando"  style="width: 100%; margin-bottom: 50px; display: none;">
        <center >
          <div class="lds-ripple" ><div></div><div></div></div>
          <div class="alert alert-info"  style="width: 100%;">
           <strong>Cargando ... Creando Respaldo</strong>
          </div>
        </center>  
      </div>
  <div class="panel-body">

    <br>

<!--                                      TABLA INICIAL   -->
        <div class="table-responsive">
          <div class="outer_div" style="background-color: white; padding-bottom: 0; margin-bottom: -1000px,">
          </div>
        </div>
<!--         BOTONES -->
    <form class="form-horizontal" method="post" id="crearespaldo" name="crearespaldo">
  
      <div class="" style="padding-top: 0px; padding-left: 5px; width: 99%;">
        <div class="" style="padding-top: 0px; padding-left: 5px;width: 99%;">
          <div id="resultados_ajax3" style="padding-left: 5px,width: 99%;"></div>
        </div>
        
        <button type="submit" class="btn btn-success btn-cliente" id="actualizar_datos3" style="width: 99%;" >Crear Respaldo</button>
        <button type="button" class="btn btn-danger btn-cliente" id="btn-cancelar" name="btn-cancelar" style="width: 99%; margin-bottom: 20px;">Cerrar</button>
        </div>
      </form>
 
  </div>
</div>

        <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
        <script src="../dist/alertifyjs/alertify.js"></script>
        <script type="text/javascript" src="../js/validaciones-bitacora.js"></script>
        <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    </body>
</html>
