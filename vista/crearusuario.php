<?php
  session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
    exit;
        }
  /* Connect To Database*/
  require_once ("../config/db.php");
  require_once ("../config/conexion.php");

  $title="Usuarios";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <link rel="stylesheet" type="text/css" href="../css/ripple.css">
        <link rel="stylesheet" href="../css/AdminLTE.css">
        <link rel="stylesheet" href="../css/skins/_all-skins.css">
        <link rel="stylesheet" href="../css/style.css">
    <style type="text/css">
    .flex{
      display: flex;
    }
.telefonito{
  width: 10%;
    height: 34px;
    font-size: 14px;
    line-height: 1.42857143;
  /*  padding-right: 5px;*/
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    margin-right: 0;
}    
.telefono{
    margin-left: 2%;
    width: 88%;
    height: 34px;
    padding-left: 15px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
}
    </style>
  </head>
  <body>

    <!---------------------- MODAL USUARIOS REGISTRADOS -------------------->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog" id="modal-tabla">
        <!-- Modal content-->
        <div class="modal-content text-center">
          <div class="modal-header" id="modal-superior">
            <button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
            <div class="panel-heading">
              <center>
                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
                <h3 style="padding:0px;margin:0px; ">Usuarios Registrados</h3>
              </center>
            </div>
          </div>

          <div class="table-responsive" id="tabla_agregar">
            <div class="col-xs-6">
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                <input class="form-control" id="FiltroUsuario" type="text" placeholder="Buscar Usuarios..." onkeyup="load_usuario(1);">
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
    <!---------------------- FINAL MODAL USUARIOS REGISTRADOS -------------------->

<div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;">

        <div class="panel-heading">
            <center>
            <h3 style="padding:0px;margin:0px;"><i class='glyphicon glyphicon-edit'></i> CREAR USUARIO</h3>
            </center>
        </div>

  <div class="panel-body">

      <form class="form-horizontal" accept-charset="utf-8" method="post" id="guardar_usuario" name="guardar_usuario">
        <div>
      <!--       AVISO USUARIO -->
      <div class="form-group" id="aviso1">
        <label for="respuesta" class="col-sm-3 control-label"></label>
          <div class="col-sm-8">
            <span id="aviso-usuario"></span>
          </div>
      </div>

          <div class="form-group">

            <label for="user_name" class="col-sm-3 control-label"><i class="fa fa-user-plus"></i> Usuario</label>
              <div class="col-sm-8">
                <div class="input-group col-sm-12">
                  <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Usuario" pattern="[a-z0-9]{2,64}" title="Nombre de usuario ( sólo letras y números (sin espacios en minusculas), 2-64 caracteres)"required>
                  <span class="input-group-btn">
                    <form class="navbar-form navbar-left" action="/action_page.php" id="noexiste">
                      <button type="button" class="btn btn-info nuevo" id="btn_direc_modal" data-toggle="modal" data-target="#myModal">Ver Todos</button>
                    </form>
                  </span>
                </div>
              </div>
          </div>

       <div class="form-group">
          <label for="user_name" class="col-sm-3 control-label">Tipo de Usuario</label>
            <div class="col-sm-8">
              <select class="form-control" name="tipousuario" id="tipousuario" required>
              <option value="" disabled selected>Seleciona tu opcion</option>
              <option value="Administrador">Administrador</option>
              <option value="Usuario">Usuario</option>
             </select>
            </div>
        </div>
      <!--       AVISO CORREO -->
      <div class="form-group" id="aviso2">
        <label for="respuesta" class="col-sm-3 control-label"></label>
          <div class="col-sm-8">
            <span id="correo-aviso"></span>
          </div>
      </div>
        <div class="form-group"  style="margin-top: -10px">
          <label for="user_email" class="col-sm-3 control-label">Email</label>
            <div class="col-sm-8">
              <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Correo electrónico" required >
            </div>
        </div>
      <!--       AVISO TELEFONO -->
      <div class="form-group" id="aviso3">
        <label for="respuesta" class="col-sm-3 control-label"></label>
          <div class="col-sm-8">
            <span id="telefono-aviso"></span>
          </div>
      </div>
        <div class="form-group" style="margin-top: -10px">
          <label for="user_number" class="col-sm-3 control-label">Numero de telefono Movil</label>
          <div class="col-sm-8">
            <div class="flex">
              <select class="telefonito" style="width: 23%;" name="telextension" id="usertelefono" required>
                <option value="" disabled selected>Selecione</option>
                <option value="414">0414</option>
                <option value="424">0424</option>
                <option value="416">0416</option>
                <option value="426">0426</option>
                <option value="412">0412</option>
              </select>
          <input type="number" class="telefono" id="user_number" name="user_number" placeholder="Numero de telefono" pattern="[0-9]{7}" maxlength="7" title=" Numero de telefono 7 digitos (Ejemplo 1234567)"  required>
            </div>
          </div>
        </div>
<!--         PREGUNTA DE SEGURIDAD -->
        <div class="form-group">
          <label for="pregunta" class="col-sm-3 control-label">Pregunta de Seguridad</label>
            <div class="col-sm-8">
              <select class="form-control" name="pregunta" id="pregunta" required>
              <option value="" disabled selected>Seleciona tu opcion</option>
              <option value="color">Color Favorito</option>
              <option value="nombre">Nombre de tu Papa/Mamá</option>
              <option value="mascota">Nombre de tu Primera Mascota</option>
              </select>
            </div>
        </div> 
<!--         RESPUESTA DE SEGURIDAD -->
        <div class="form-group">
          <label for="respuesta" class="col-sm-3 control-label">Respuesta de seguridad</label>
            <div class="col-sm-8">
            <input type="text" class="form-control" id="respuesta" name="respuesta" placeholder="Respues de seguridad" pattern="[a-z0-9]{2,64}" title="Escriba su respuesta (Sin espacios, sólo letras minusculas y números, 2-64 caracteres)"  required>
            </div>
        </div>

      <!--       AVISO CONTRASEÑA -->
            <div class="form-group" id="aviso4">
              <label for="respuesta" class="col-sm-3 control-label"></label>
              <div class="col-sm-8">
                <span id="aviso-password"></span>
              </div>
            </div>

<!--         CLAVE -->
        <div class="form-group" style="margin-top: -10px">
          <label for="user_password_new" class="col-sm-3 control-label">Contraseña</label>
            <div class="col-sm-8">
            <input type="password" class="form-control" id="user_password_new" name="user_password_new" placeholder="Contraseña" pattern=".{6,}" title="Contraseña ( min . 6 caracteres)" required>
            </div>
        </div>
      <!--       AVISO REPITE CONTRASEÑA -->
            <div class="form-group" id="aviso5">
              <label for="respuesta" class="col-sm-3 control-label"></label>
              <div class="col-sm-8">
                <span id="aviso-repite"></span>
              </div>
            </div>
<!--      REPITE CLAVE -->
        <div class="form-group" style="margin-top: -10px">
          <label for="user_password_repeat" class="col-sm-3 control-label">Repite contraseña</label>
            <div class="col-sm-8">
            <input type="password" class="form-control" id="user_password_repeat" name="user_password_repeat" placeholder="Repite contraseña" pattern=".{6,}" title="Contraseña ( min . 6 caracteres)" required>
            </div>
        </div>
       </div>
<!--        SPINNER -->
        <center id="cargando"  width="100%" style="margin-bottom: -55px; display: none;">
          <div class="lds-ripple" ><div></div><div></div></div>
          <div class="alert alert-info"  style="width: 500px;">
           <strong>Cargando ... Creando su usuario, enviando email y mensaje de texto de confirmación </strong>
          </div>
        </center>   

      <div id="resultados_ajax" style="padding-top: 35px; padding-left: 10px; margin-bottom: -30px"></div>

<!--         BOTONES -->
      <div class="" style="padding-top: 35px; padding-left: 0;">
        <button type="submit" class="btn btn-success btn-cliente" name="guardar_datos" id="guardar_datos"style="width: 99%;" >Guardar datos</button>
        <button type="button" class="btn btn-danger btn-cliente" id="btn-cancelar" style="width: 99%; margin-bottom: 20px;"><i class="glyphicon glyphicon-remove"></i>Cancelar</button>
      </div>
      </form>


 </div> 
</div>
          <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
          <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>   
          <script src="../dist/alertifyjs/alertify.js"></script>
          <script type="text/javascript" src="../js/validaciones-crearusuario.js"></script>

<script type="text/javascript">


</script>


  </body>
</html>
