<?php
  
  session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1)
  {
    header("location: login.php");
    exit;
  }
  
/*  require '../controlador/controlador-cliente.php';
  $control= new client();*/
?>
  <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Bitacora</title>
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
  <div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;box-shadow: 0px 0px 0px 0px;">
        <div class="panel-heading">
          <center>
           <h3 style="padding:0px;margin:0px; ">Gráficos</h3>
          </center>
        </div>
    <div style="margin: 25px 0px 20px 0px">
      <p id="subtitulo" style="text-align: center;">Gráficas por fecha</p>
    </div> 
    <div class="fechas_div" style="display: flex; justify-content: center;">
    
    <div class="input_date">
    <label for="fecha-desde">Desde :</label>  
    <input type="date" id="fecha_desde" required max="<?php $hoy=date("Y-m-d"); echo $hoy;?>">
    </div>
    
    <div class="input_date">
    <label for="fecha-hasta">Hasta :</label>
    <input type="date" id="fecha_hasta" required max="<?php $hoy=date("Y-m-d "); echo $hoy;?>">
    </div>
    </div>

  <div class="botonera">
    <div class="container">
<!--         <a href="../reportegrafico/productosventa.php" target="_blank">
        <button  style="margin: 5px;" class="btn btn-info" id="btn_imprimir_torta_g"><i class="glyphicon glyphicon-print"></i> Productos mas vendidos</button>
          </a> -->
      <center>
      <button  style="margin: 5px;" class="btn btn-info" id="productos_vendidosfecha"><i class="glyphicon glyphicon-print"></i> Productos mas Vendidos por rango de fecha</button>
        <button  style="margin: 5px;" class="btn btn-info" id="ventas_cantidadfecha"><i class="glyphicon glyphicon-print"></i> Ventas por Cantidad por rango de fecha</button>
        </center>
<!--       <a href="barraventatotal.php" target="_blank">
        <button  style="margin: 5px;" class="btn btn-info" id="btn_imprimir_barra_g"><i class="glyphicon glyphicon-print"></i> Ventas Totales por Cantidad</button>
      </a> -->
<!--       <a href="barraventagasto.php" target="_blank">
        <button  style="margin: 5px;" class="btn btn-info" id="btn_imprimir_barra_g"><i class="glyphicon glyphicon-print"></i> Gastos Totales Generales</button>
      </a> -->
<!--         <button  style="margin: 5px;" class="btn btn-info" id="btn_imprimir_barra_GTPF"><i class="glyphicon glyphicon-print"></i> Gastos Totales por Fecha</button> -->
<!--       <button  style="margin: 5px;" class="btn btn-info" id="btn_imprimir_barra_VCPF"><i class="glyphicon glyphicon-print"></i> Ventas a Clientes por Fecha</button> -->
<!--       <a href="barraventagastoporcliente.php" target="_blank">
        <button  style="margin: 5px;" class="btn btn-info" id="venta_clientes"><i class="glyphicon glyphicon-print"></i> Ventas a Clientes Generales</button>  
      </a> -->
    </div>
  </div> 
</div>
      <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
      <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
      <script src="../dist/alertifyjs/alertify.js"></script>
      <script type="text/javascript" src="../js/jsgraficos/vista_ventasporfechas.js"></script>
</body>
</html>


