<?php

  session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
    exit;
        }

  /* Connect To Database*/
  require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
  require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

  $title="Respaldo | EDDI";
?>
  <?php
    if (isset($con))
    {
  ?>
  
  <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Crear Respaldo</title>
        <link rel="stylesheet" type="text/css" href="../dist/bootstrap-3.3.7-dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="../css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../css/skins/_all-skins.css">
  <style type="text/css">
.atom-spinner, .atom-spinner * {
      box-sizing: border-box;
    }

    .atom-spinner {
      height: 400px;
      width: 400px;
      overflow: hidden;
    }

    .atom-spinner .spinner-inner {
      position: relative;
      display: block;
      height: 100%;
      width: 100%;
    }

    .atom-spinner .spinner-circle {
      display: block;
      position: absolute;
      color: #ff1d5e;
      font-size: calc(60px * 0.24);
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .atom-spinner .spinner-line {
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      animation-duration: 1s;
      border-left-width: calc(60px / 25);
      border-top-width: calc(60px / 25);
      border-left-color: #ff1d5e;
      border-left-style: solid;
      border-top-style: solid;
      border-top-color: transparent;
    }

    .atom-spinner .spinner-line:nth-child(1) {
      animation: atom-spinner-animation-1 1s linear infinite;
      transform: rotateZ(120deg) rotateX(66deg) rotateZ(0deg);
    }

    .atom-spinner .spinner-line:nth-child(2) {
      animation: atom-spinner-animation-2 1s linear infinite;
      transform: rotateZ(240deg) rotateX(66deg) rotateZ(0deg);
    }

    .atom-spinner .spinner-line:nth-child(3) {
      animation: atom-spinner-animation-3 1s linear infinite;
      transform: rotateZ(360deg) rotateX(66deg) rotateZ(0deg);
    }

    @keyframes atom-spinner-animation-1 {
      100% {
        transform: rotateZ(120deg) rotateX(66deg) rotateZ(360deg);
      }
    }

    @keyframes atom-spinner-animation-2 {
      100% {
        transform: rotateZ(240deg) rotateX(66deg) rotateZ(360deg);
      }
    }

    @keyframes atom-spinner-animation-3 {
      100% {
        transform: rotateZ(360deg) rotateX(66deg) rotateZ(360deg);
      }
    }
  </style>
    </head>
<body>

      <form class="form-horizontal" method="post" id="editar_password" name="editar_password">
      <div id="resultados_ajax3"></div>
       
<!--         <div class="form-group">
        <label for="user_password_new3" class="col-sm-4 control-label">Usuario</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="user_id_mod" name="user_id_mod" placeholder="usuario" required>
        </div>
        </div> -->       
<div class="atom-spinner" style="display: none;">
  <div class="spinner-inner">
    <div class="spinner-line"></div>
    <div class="spinner-line"></div>
    <div class="spinner-line"></div>
    <!--Chrome renders little circles malformed :(-->
    <div class="spinner-circle">
      &#9679;
    </div>
  </div>
</div>

      <div>
      <button type="button" class="btn btn-default" id="cerrar">Cerrar</button>
      <button type="submit" class="btn btn-primary" id="actualizar_datos3">Crear Respaldo</button>
      </div>
      </form>
    </div>



    </body>
</html>
<script type="text/javascript">
  
  $( "#editar_password" ).submit(function( event ) {
  $('#actualizar_datos3').attr("disabled", true);
  
 var parametros = $(this).serialize();
   $.ajax({
      type: "POST",
      url: "../controlador/control-ajax-respaldo.php",
      data: parametros,
       beforeSend: function(objeto){
        $(".atom-spinner").show();

        },
      success: function(datos){
      $(".atom-spinner").hide();
      $("#resultados_ajax3").html(datos);
      $('#actualizar_datos3').attr("disabled", false);
      //load(1);
      }
  });
  event.preventDefault();
})

</script>
  <?php
    }
  ?>  
