<?php
// include the configs / constants for the database connection
/*require_once("conexion.php");*/
require_once("../config/db.php");

// load the login class
require_once("../controlador/control-login.php");

$login = new Login();
//Para conocer si esta logeado
if ($login->isUserLoggedIn() == true) {
   header("location: menu.php");

} else {
?>
<head>
	<title>EDDY</title>
	<meta charset="UTF-8">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="../css/materialize.css"  media="screen,projection"/>
		<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
		<link rel="stylesheet" type="text/css" href="../css/login.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

	<div class="contenedor" id="contenedor">
		<div class="overlay">
		 <ul class="nav navbar-nav">
					<div class="logintitulo">
							<img id="logo" src="../img/logovazul.svg">
					</div>

		        <form method="post" accept-charset="utf-8" action="login.php" name="loginform" autocomplete="off" role="form" class="form-signin">

			<?php
				// show potential errors / feedback (from login object)
				if (isset($login)) {
					if ($login->errors) {
						?>
						<center>
						<div class="mensajito">
						<div class="" role="alert" style="border-radius: 15px; margin: -30 0 -10 0;width: 300px; padding: 8 10 8 10px;">
						    <strong>Error!</strong> 
						
						<?php 
						foreach ($login->errors as $error) {
							echo $error;
						}
						?>
						</div>
					</div>
					</center>
						<?php
					}
					if ($login->messages) {
						?>
						<center>
						<div class="mensajito">
						<div class="" role="alert" style="margin: 0px;width: 300px; padding: 10px;">
						    <strong>Aviso!</strong>
						<?php
						foreach ($login->messages as $message) {
							echo $message;
						}
						?>
						</div> 
					</div>
					</center>
						<?php 
					}
				}
				?>	
					<div class="User">
							<div class="input-field cliente col s6">

								<i class="material-icons prefix">account_circle</i>
								<input id="nombre-cliente" type="text" maxlength="20" onpaste="return false;" autocomplete="off" placeholder="Nombre de usuario:" pattern="[a-z0-9]{2,64}" title="Usuario ( min . 2 caracteres en Minusculas sin espacios)" name="user_name" required>
	          					<!-- <label for="nombre-cliente">Nombre de usuario:</label> -->
							</div>
					</div>
					<div class="Password">
							<div class="input-field cliente col s6">
								<i class="material-icons prefix">lock</i>
								<input id="password-cliente"  type="password" maxlength="20" onpaste="return false;" autocomplete="off" placeholder="Contraseña:" name="user_password" pattern=".{4,}" title="Contraseña ( min . 4 caracteres)" required>
	          				<!-- 	<label for="password-cliente">Contraseña:</label> -->
							</div>
					</div>

					<div class="Recuperar" ><a id="btnolvido" href="#">¿Olvido Su contraseña?</a></div>

				

		<div class="input-field col s12 center-align" id="botonlogin">
<!-- 			<a class="waves-effect waves-light btn hoverable" name="Login" id="Login"><strong>Login</strong></a> -->

	     <strong><button type="submit" class="waves-effect waves-light btn hoverable" name="login" id="Login">Iniciar Sesión</button></strong>
          	
		</div>
	
  	</form><!-- /form -->

  	</ul>
  	</div>
  </div>

	<script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="../js/login.js"></script>  
	<script type="text/javascript" src="../js/materialize.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	$('select').formSelect();
	});
	/* VALIDACION TECLA DE ESPACIO ADMIN*/
	$("input#nombre-cliente").on({
	  keydown: function(e) {
	    if (e.which === 32)
	      return false;
	  },
	  change: function() {
	    this.value = this.value.replace(/\s/g, "");
	  }
	});
	/* VALIDACION TECLA DE ESPACIO CONTRASEÑA*/
	$("input#Password").on({
	  keydown: function(e) {
	    if (e.which === 32)
	      return false;
	  },
	  change: function() {
	    this.value = this.value.replace(/\s/g, "");
	  }
	});
	</script>
	</body>
</html>
<?php
}
