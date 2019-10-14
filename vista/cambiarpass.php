<?php
  session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
    exit;
        }

  /* Connect To Database*/
  require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
  require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

  $title="Cambiar Contraseña | EDDI";
?>
  <?php
    if (isset($con))
    {
  ?>
  
  <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Cambio de Contraseña</title>
        <link rel="stylesheet" type="text/css" href="../css/ripple.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
        <link rel="stylesheet" href="../css/AdminLTE.css">
        <link rel="stylesheet" href="../css/skins/_all-skins.css">
        <link rel="stylesheet" href="../css/style.css">
    <style type="text/css">
    .flex{
      display: flex;
    }
	</style>
    </head>
<body>


<div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;">

        <div class="panel-heading">
            <center>
         	<h3 style="padding:0px;margin:0px;"><i class='glyphicon glyphicon-edit'></i> CAMBIAR CONTRASEÑA</h3>
          	</center>
        </div>

 	<div class="panel-body">

	<form class="form-horizontal" accept-charset="utf-8" method="post" id="editar_password" name="editar_password">
		<div>
		  		<div class="form-group">
					<label for="user_id_mod" class="col-sm-3 control-label">Usuario</label>
					<div class="col-sm-8">
					  <input type="text" class="form-control" id="user_id_mod" name="user_id_mod" placeholder="usuario" required>
					</div>
				</div>		

        		<div class="form-group">
        			<label for="pregunta" class="col-sm-3 control-label">Pregunta de Seguridad</label>
        				<div class="col-sm-8">
        					<select class="form-control" name="pregunta" required>
          					<option value="" disabled selected>Seleciona tu opcion</option>
          					<option value="color">Color Favorito</option>
          					<option value="nombre">Nombre de tu Papa/Mamá</option>
          					<option value="mascota">Nombre de tu Primera Mascota</option>
        					</select>
        				</div>
        		</div>

        		<div class="form-group">
        			<label for="respuesta" class="col-sm-3 control-label">Respuesta de seguridad</label>
        			<div class="col-sm-8">
          				<input type="text" class="form-control" id="respuesta" name="respuesta" placeholder="Respues de seguridad" pattern="[a-zA-Z0-9]{2,64}" title="Escriba su respuesta (sólo letras y números, 2-64 caracteres)"  required>
        			</div>
        		</div>	
      <!--       AVISO CONFIRMAR SPAN -->
            <div class="form-group" id="aviso1">
              <label for="respuesta" class="col-sm-3 control-label"></label>
              <div class="col-sm-8">
                <span id="password-confirm"></span>
              </div>
            </div>
<!--               CONTRASEÑA -->
			  <div class="form-group" style="margin-top: -10px">
  				<label for="user_password_new3" class="col-sm-3 control-label">Nueva contraseña</label>
  				<div class="col-sm-8">
  				  <input type="password" class="form-control" id="user_password_new3" name="user_password_new3" placeholder="Nueva contraseña" pattern=".{6,}" title="Contraseña ( min . 6 caracteres)" required>
  				</div>
			  </div>	
      <!--       AVISO REPITE CONTRASEÑA -->
            <div class="form-group" id="aviso2">
              <label for="respuesta" class="col-sm-3 control-label"></label>
              <div class="col-sm-8">
                <span id="aviso-repite"></span>
              </div>
            </div>
<!--        REPITE CONTRASEÑA  -->
			  <div class="form-group" style="margin-top: -10px">
  				<label for="user_password_repeat3" class="col-sm-3 control-label">Repite contraseña</label>
  				<div class="col-sm-8">
  				  <input type="password" class="form-control" id="user_password_repeat3" name="user_password_repeat3" placeholder="Repite contraseña" pattern=".{6,}" required>
  				</div>
			  </div>	 

			<div id="resultados_ajax3"></div>		 
		</div>  
<!--         BOTONES -->
      <div class="" style="padding-top: 35px; padding-left: 0;">
        <button type="submit" class="btn btn-success btn-cliente" id="actualizar_datos3" style="width: 99%;" >Cambiar contraseña</button>
        <button type="button" class="btn btn-danger btn-cliente" id="btn-cancelar" name="btn-cancelar" style="width: 99%; margin-bottom: 20px;">Cerrar</button>
      </div>

		</form>	
	</div>
</div>

    </body>
</html>
    <script src="../dist/alertifyjs/alertify.js"></script>
    <script type="text/javascript" src="../js/validaciones-cambiarpass.js"></script>

<script type="text/javascript">	
	$( "#editar_password" ).submit(function( event ) {
  $('#actualizar_datos3').attr("disabled", true);
  
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "../controlador/control-ajax-cambiarpass.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax3").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados_ajax3").html(datos);
			$('#actualizar_datos3').attr("disabled", false);
			//load(1);
		  }
	});
  event.preventDefault();
})

$("#btn-cancelar").on('click',function(e)
{ 
  alertify.confirm('Cancelar','Desea cancelar el proceso?',
  function()
  {
    alertify.success('Ok');
    window.location.reload(true);
  },
  function()
  {
    alertify.error('Cancelado');
  });
});

</script>
	<?php
		}
	?>	