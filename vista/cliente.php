<?php 

  require '../controlador/controlador-cliente.php';
  require '../controlador/autocompletar/autocompletar-cliente.php';
  $control= new client();

 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Cliente</title>
     
    <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="../css/AdminLTE.css">
    <link rel="stylesheet" href="../css/skins/_all-skins.css">
  </head>
  
  <body>

    <header>
    </header>
    <!---------------------- MODAL CLIENTES REGISTRADOS -------------------->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog" id="modal-tabla">
        <!-- Modal content-->
        <div class="modal-content text-center">
          <div class="modal-header" id="modal-superior">
            <button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
            <div class="panel-heading">
              <center>
                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
                <h3 style="padding:0px;margin:0px; ">Clientes Registrados</h3>
              </center>
            </div>
          </div>

          <div class="table-responsive" id="tabla_agregar">
            <div class="col-xs-6">
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                <input class="form-control" id="FiltroCliente" type="text" placeholder="Buscar Clientes..." onkeyup="load_cliente(1);">
                <br>
              </div>
            </div>
            <br><br>
            <div class="modal-body">
              <div class="outer_div">
            
              </div>
              <div class="modal-footer">
                <button class="btn btn-danger" style="width: 100%;" id="Cerrar-modal-tabla" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!---------------------- FINAL MODAL CLIENTES REGISTRADOS -------------------->

    <form action="" id="form-1" name="form-1" method="post">
      <div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;">
        <div class="panel-heading">
          <center>
            <!-- <img src="../img/registro1.jpg" width="1000px"> -->
            <h3 style="padding:0px;margin:0px; ">REGISTRO DE CLIENTE</h3>
          </center>
        </div>

        <div class="panel-body">
          <div class="form-group col-xs-10" style="padding-top: 20px;">
            <label>Identificación:</label>
            <div class="input-group">
              <div class="input-group-btn">
                <select name="nacio-cliente" id="nacio-cliente" class="form-control" >
                  <option value="RIF">Seleccione</option>
                  <option value="V">V</option>
                  <option value="E">E</option>
                  <option value="J">J</option>
                </select>
              </div><!-- /btn-group -->
              <input type="text" class="form-control" placeholder="Cédula o RIF" id="cedula-cliente" maxlength="8" onpaste="return false;">
              <span class="input-group-btn">
                <button type="button" class="btn btn-default" id="Consultar"><i class="glyphicon glyphicon-search"></i></button>
                <form class="navbar-form navbar-left" action="/action_page.php" id="noexiste">
                  <button type="button" class="btn btn-info nuevo" id="btn_direc_modal" data-toggle="modal" data-target="#myModal">Ver Todos</button>
                </form>
              </span>
            </div>
          </div>

          <div id="mostrar" style="width: 500px;">
            
          </div>
      <!--       AVISO NOMBRE -->
      <div class="form-group col-xs-12" id="aviso1" style="margin: 0; padding: 0;">
              <div class="col-xs-6">
                <span id="nombre-aviso" class="form-group col-xs-12" style="margin: 0px 0px -5px 0px; padding: 0;"></span>
              </div>
      
      <!--       AVISO APELLIDO -->
              <div class="col-xs-6">
                <span id="apellido-aviso" class="form-group col-xs-12" style="margin: 0px 0px -5px 0px; padding: 0;"></span>
              </div>
      </div>
          <div class="form-group">
            <div class="col-xs-6">
              <label>Nombre:</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input id="nombre-cliente" type="text" class="form-control" name="nombre-cliente" placeholder="Nombre del cliente" maxlength="25" onpaste="return false;">
              </div>
            </div>

            <div class="col-xs-6">
              <label>Apellido:</label>
              <input type="text" name="apellido-cliente" id="apellido-cliente" class="form-control" placeholder="Apellido del cliente" maxlength="25" onpaste="return false;">
            </div>
          </div>
      <!--       AVISO DIRECCION -->
      <div class="form-group col-xs-12" id="aviso1">
          <span id="direccion-aviso" class="form-group col-xs-12" style="margin:20px 0px -10px 0px; padding: 0;"></span>
      </div>    

          <div class="form-group col-xs-12">
            <label>Dirección:</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
              <input id="direccion-cliente" type="text" class="form-control" name="direccion-cliente" placeholder="Dirección del cliente" maxlength="50" onpaste="return false;">
            </div>
          </div>

      <!--       AVISO CORREO -->
      <div class="form-group col-xs-12" id="aviso3" style="margin: 0; padding: 0;">
              <div class="col-xs-6">
                <span id="correo-aviso" class="form-group col-xs-12" style="margin: 0px 0px -5px 0px; padding: 0;"></span>
              </div>
      
      <!--       AVISO TELEFONO -->
              <div class="col-xs-6">
                <span id="telefono-aviso" class="form-group col-xs-12" style="margin: 0px 0px -5px 0px; padding: 0;"></span>
              </div>
      </div>

          <div class="form-group">
            <div class="col-xs-6">
              <label>Correo:</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <input id="correo-cliente" type="email" class="form-control" name="correo-cliente" placeholder="Ejemplo@hotmail.com" onpaste="return false;">
              </div>
            </div>

            <div class="col-xs-6">
              <label>Telefono:</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
                <input id="telefono-cliente" type="text" class="form-control" name="telefono-cliente" placeholder="Telefono del cliente" required maxlength="11" onpaste="return false;">
              </div>
            </div>
          </div>
      
          <div class="col-xs-6" style="padding-top: 15px; padding-left: 9px;">
            <button type="button" class="btn btn-success btn-cliente" name="Agregar" id="Agregar">Agregar</button>
            <button type="button" class="btn btn-success btn-cliente" name="Guardar" id="Guardar" disabled>Guardar</button>
          </div>

          <div class="col-xs-6" style="padding-top: 15px; padding-bottom: 5px;">
            <button type="button" class="btn btn-success btn-cliente" name="Actualizar" id="Actualizar" disabled style="margin-left: 0">Actualizar</button>
            <button type="button" class="btn btn-success btn-cliente" name="Eliminar" id="Eliminar" disabled style="margin-left: 0">Desactivar</button>
          </div>
      
          <center>
            <button type="button" class="btn btn-danger btn-cliente" id="btn-cancelar" name="btn-cancelar" style="width: 97%; margin-bottom: 20px;">Cancelar</button>
          </center>
        </div>
      </div>
    </form>

    <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="../dist/alertifyjs/alertify.js"></script>
    <script type="text/javascript" src="../js/validaciones-cliente.js"></script>
    <script type="text/javascript" src="../js/bootstrap3-typeahead.js"></script>
  </body>
</html>
