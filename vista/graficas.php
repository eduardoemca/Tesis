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
       
        <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="../css/AdminLTE.css">
        <link rel="stylesheet" href="../css/skins/_all-skins.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">    
    </head>
<body>

    <header>
    </header>

<div class="panel panel-primary" id="step-one" style="padding:-15px;margin:-15px; width: 100%">

        <div class="panel-heading">
            <center>
          <h3 style="padding:0px;margin:0px;"><i class='glyphicon glyphicon-signal'></i> REPORTES GRAFICOS</h3>
            </center>
        </div>
      <br><br>
      <!-- Info boxes -->

     <div id="caja2" style="margin: -30px 0px 10px 0px">
     </div>


  <div id="caja" style="margin-bottom: 50px"></div>  

      <div class="row" style="margin-left: 150px; padding-left: 100px;">
       
          <div class="col-lg-3 col-xs-6">
            <a id="graficoproductos" href="#">
            <span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-stats"></i></span>
            </a>
            <div class="info-box-content">
              <span class="info-box-text">Productos</span>
              <!-- <span class="info-box-number">90<small>%</small></span> -->
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
      
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
             <a id="graficoreporte" href="#">
            <span class="info-box-icon bg-red"><i class="fa fa-area-chart"></i></span>
              </a>
            <div class="info-box-content">
              <span class="info-box-text">Ventas</span>
             <!--  <span class="info-box-number">41,410</span> -->
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

<!--         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Reporte venta</span>
            </div>
          </div>
        </div> -->

        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <a id="graficocliente" href="#">
            <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
            </a>
            <div class="info-box-content">
              <span class="info-box-text">Clientes</span>
              <!-- <span class="info-box-number">2,000</span> -->
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <br><br>
       <!-- fa-truck proveedor -->
 
</div>

       
        <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="../js/jsgraficos/graficas.js"></script>
        <script src="../dist/alertifyjs/alertify.js"></script>

    </body>
</html>
