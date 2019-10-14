<?php  require_once("../config/db.php");   ?>
<html lang="es">
<head>
	<title>EDDY</title>
	<meta charset="UTF-8">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="../css/materialize.css"  media="screen,projection"/>
	<link rel="stylesheet" type="text/css" href="../css/olvido.css">
	<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
	<div class="contenedor" id="contenedor" style="display: table; ">
	 <div id="contenedor2"style="display: table-row;">
		<div class="logintitulo1" ">
			<img id="logo1" src="../img/logoversion2azul.png">
		</div>
      	<!--       AVISO CONFIRMAR SPAN -->
            <div class="form-group" id="aviso">
              <label for="respuesta" class="col-sm-3 control-label"></label>
              <div class="col-sm-8">
                <span id="aviso-usuario"></span>
              </div>
            </div>

  	 	<div id="resultados_ajax" width="80%" ></div>

		    <form method="post" accept-charset="utf-8" name="olvidoform" id="olvidoform" autocomplete="off" >
				<div class="User" style="padding-bottom: 0px;">

<!-- display: flex; -->
						<!-- <div style="width: 80%"> -->
							<div class="input-field cliente col s6">
								<i class="material-icons prefix"">account_circle</i>
								<input id="nombre-cliente" type="text" maxlength="20" onpaste="return false;" autocomplete="off" placeholder="Coloque el Nombre de usuario:" name="user_name" pattern="[a-zA-Z0-9]{6,64}" title="Usuario ( min . 6 caracteres)" required>
							</div>
						<!-- </div> -->
<!-- 						<div style="width: 20%; padding:29px 0px 0px 10px ;">
						<button type="button" style="background-color:#0B3954;" class="btn btn-default" id="Consultar"><i class="material-icons" style="background-color:#0B3954;">search</i>
						</button>
						</div> -->
				</div>
			    <div>
			
			    
			    <label style="padding: 0px 90px 0px 0px;font-size: 16px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;">Pregunta de seguridad</label>
			    </div>
<div class="User" style="padding: 0px 50px 0px 50px; margin: 0;">
			  		
<div class="row">	
   <div class="col s1" style="margin-right: 6px;"><img id="check" src="../img/check.png" width="30px" style="margin: 10px 0px 0px 23px;"></div>
      <div class="col s10" style="margin-left: 15px;">
			    <select style="margin-left: 35;" id="opcion" class="form-control" name="opcion" required>
			    	<option value="" disabled selected>Selecione</option>
          			<option value="color">Color Favorito</option>
          			<option value="nombre">Nombre de tu Papa/Mamá</option>
          			<option value="mascota">Nombre de tu Primera Mascota</option>
			    </select>
			     </div>
				</div>
			</div>

  				<div class="User" style="padding-bottom: 0px;padding:0px 80px 0px 80px;">
        			<div class="input-field cliente col s6">
        			<i class="material-icons prefix" style="color:#0B3954;">check_circle</i>
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

				<div class="Password">
						<div class="input-field cliente col s6">
							<i class="material-icons prefix">lock</i>
							<input id="password-cliente"  type="password" maxlength="20" onpaste="return false;" autocomplete="off" placeholder="Contraseña:" name="user_password" pattern=".{6,}" title="Contraseña ( min . 6 caracteres)"  required>
	          				<!-- 	<label for="password-cliente">Contraseña:</label> -->
						</div>
				</div>

	             <!--       AVISO REPITE CONTRASEÑA -->
	            <div class="form-group" id="aviso2">
	              <label for="respuesta" class="col-sm-3 control-label"></label>
	              <div class="col-sm-8">
	                <span id="aviso-repite"></span>
	              </div>
	            </div>

				<div class="Password">
						<div class="input-field cliente col s6">
							<i class="material-icons prefix">lock_outline</i>
							<input id="user_password_new"  type="password" maxlength="20" onpaste="return false;" autocomplete="off" placeholder="Contraseña nueva:" name="user_password_new" pattern=".{6,}" title="Contraseña ( min . 6 caracteres)"  required>
	          			<!-- 	<label for="password-cliente">Contraseña:</label> -->
						</div>
				</div>

<!-- 	BOTONES -->
		<div>
			<button type="submit" class="waves-effect waves-light btn hoverable" name="olvido" id="cambiapassword">Cambiar Contraseña</button>
		</div>
  	 		<div>
<!--<a class="waves-effect waves-light btn hoverable" name="Login" id="Login"><strong>Login</strong></a> -->
				<a id="btncerrar" class="waves-effect waves-light btn hoverable" name="btncerrar" href="#">Cerrar</a>	
			</div>
  		</form>	
	</div>
	</div>
		<script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="../js/materialize.min.js"></script>
		<script type="text/javascript" src="../js/login.js"></script>  
	</body>
</html>
<script type="text/javascript">
$(document).ready(function(){

	$('select').formSelect();
	   $('#opcion').hide();
	});
$("select[required]").css({display: "block", height: 0, padding: 0, width: 0, position: 'absolute'});

$( "#olvidoform" ).submit(function( event ) {
$('#cambiapassword').attr("disabled", true);

 var parametros = $(this).serialize();
   $.ajax({
      type: "POST",
      url: "../controlador/control-olvido-password.php",
      data: parametros,
      success: function(datos){
      $("#resultados_ajax").html(datos);
      $('#cambiapassword').attr("disabled", false);
        $( ".fade" ).fadeOut(4000);
/*      load();*/
      }
  });
  event.preventDefault();
})

/* VALIDACION TECLA DE ESPACIO USUARIO*/
$("input#nombre-cliente").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});

/*USUARIO KEYUP*/
$('#nombre-cliente').on('keyup', function()
{
  if ($("#nombre-cliente").val()!="")
  {
    $("#aviso").show();
      var usuarioup= $('#nombre-cliente').val();

      if (usuarioup.length<6)
      {
        text='<center><div class="alert" style="width:60%; background-color:#0B3954; color: white;border-radius:10px; padding: 5px; margin-top:50px">El usuario debe tener minimo 6 caracteres</div></center>';
      } 
      else 
      {
        text= '';
        $("#aviso").fadeOut(10);
      }
      $("#aviso-usuario").html(text);
      $("#aviso-usuario").show();
      $("#aviso-usuario").delay(4000).fadeOut(200);
      $("#aviso1").delay(4000).fadeOut(200);
  } 
});

/* VALIDACION TECLA DE ESPACIO RESPUESTA DE SEGURIDAD*/
$("input#respuesta").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});

/* VALIDACION TECLA DE ESPACIO CONTRASEÑA*/
$("input#password-cliente").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});

/* VALIDACION TECLA DE ESPACIO REPITE CONTRASEÑA*/
$("input#user_password_new").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});

$('#password-cliente').on('keyup', function(){
	var $password   =$('#password-cliente').val();
	if ($password.length<6)
	{	$("#aviso1").show();
	    text='<center><div class="alert" style="width:60%; background-color:#0B3954; color: white;border-radius:10px; padding: 5px">La contraseña debe ser mayor a 6 digitos</div></center>';
	    $('#password-confirm').html(text);
	    $("#password-confirm").show();
	    $("#password-confirm").delay(5000).fadeOut(200);
	    $("#aviso1").delay(5000).fadeOut(200);
	} 
		else if ($password.length>=6){
	    		$("#aviso1").hide();
	    		$("#password-confirm").hide();
	    	} 
});

$('#user_password_new').on('keyup', function(){
	var $repassword =$('#user_password_new').val();
	    	if ($repassword.length<6)
			{
			$("#aviso2").show();
			text='<center><div class="alert" style="width:60%; background-color:#0B3954; color: white; border-radius:10px; padding: 5px"">La Repetición debe ser mayor a 6 digitos</div></center>';
		    $('#aviso-repite').html(text);
		    $("#aviso-repite").show();
		    $("#aviso-repite").delay(5000).fadeOut(200);
		    $("#aviso2").delay(5000).fadeOut(200);
			} 
				else if ($repassword.length>=6){
		    		$("#aviso2").hide();
		    		$('#aviso-repite').hide();
		
		    	}
});

$('#password-cliente, #user_password_new').on('keyup', function(){
	var $password   =$('#password-cliente').val();
	var $repassword =$('#user_password_new').val();

if ($password.length>=6 && $repassword.length>=6)
{
	passwordd($password,$repassword);	
}

			
function passwordd($password,$repassword){
	$("#aviso1").hide();
	$("#aviso1").show();
var text ='';
if($password == $repassword){

	text='<center><div class="alert" style="width:60%; background-color:#4EA89E; color: white; border-radius:10px ;padding: 5px">Los campos de contraseña coinciden</div></center>';
}else{
	text='<center><div class="alert" style="width:60%; background-color:#0B3954; color: white; border-radius:10px; padding: 5px">Los campos de contraseña no coinciden</div></center>';
}

		$('#password-confirm').html(text);
	    $("#password-confirm").show();
	    $("#password-confirm").delay(5000).fadeOut(200);
	    $("#aviso1").delay(5000).fadeOut(200);
};
								
})
</script>