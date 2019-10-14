$(document).ready(function()
{
  load_usuario(1);
});


$("#btn-cancelar").on('click',function()
{
	var estatus= confirm("Deseas cancelar el proceso?");
	if (estatus==true)
	{
		limpiarcampos();
		/*habilitarcampos();
		limpiaropciones();*/
	}
});
/* VALIDACION TECLA DE ESPACIO USUARIO*/
$("input#user_name").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});

/*USUARIO KEYUP*/
$('#user_name').on('keyup', function()
{
  if ($("#user_name").val()!="")
  {
    $("#aviso1").show();
      var usuarioup= $('#user_name').val();

      if (usuarioup.length<6)
      {
        text='<div class="alert alert-danger" role="alert">El usuario debe tener minimo 6 caracteres</div>';
      } 
      else 
      {
        text= '';
        $("#aviso1").fadeOut(10);
      }
      $("#aviso-usuario").html(text);
      $("#aviso-usuario").show();
      $("#aviso-usuario").delay(4000).fadeOut(200);
      $("#aviso1").delay(4000).fadeOut(200);
  } 
});
/*CORREO KEYUP*/
$('#user_email').on('keyup', function()
{
  if ($("#user_email").val()!="")
  {
    $("#aviso3").show();
      var $email = this.value;
      validateEmail($email);
      $("#correo-aviso").show();
      $("#correo-aviso").delay(4000).fadeOut(200);
      $("#aviso2").delay(4000).fadeOut(200);
  } 
});

function validateEmail(email)
{
  $("#aviso2").show();
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{3,4})?$/;
    if (!emailReg.test(email))
    {
      text='<div class="alert alert-danger" role="alert">Correo invalido</div>';

    }
    else
    {
        text='<div class="alert alert-success" role="alert">Correo valido</div> ';
    }
    $('#correo-aviso').html(text);
}
/*TELEFONO KEYUP*/
$('#user_number').on('keyup', function()
{
  if ($("#user_number").val()!="")
  {
    $("#aviso3").show();
      var telefonoup= $('#user_number').val();

      if (telefonoup.length<6)
      {
        text='<div class="alert alert-danger" role="alert">Telefono invalido</div>';
      }
      else
      {
          text='<div class="alert alert-success" role="alert">Telefono valido</div> ';
      }
      $("#telefono-aviso").html(text);
      $("#telefono-aviso").show();
      $("#telefono-aviso").delay(4000).fadeOut(200);
      $("#aviso3").delay(4000).fadeOut(200);
  } 
});

/*CONTRASEÑA*/
$('#user_password_new').on('keyup', function(){
  var $password   =$('#user_password_new').val();
  if ($password.length<6)
  { $("#aviso4").show();
      text='<div class="alert alert-danger" role="alert">La contraseña debe ser mayor a 6 digitos</div>';
      $('#aviso-password').html(text);
      $("#aviso-password").show();
      $("#aviso-password").delay(5000).fadeOut(200);
      $("#aviso4").delay(5000).fadeOut(200);
  } 
    else if ($password.length>=6){
          $("#aviso4").hide();
          $("#aviso-password").hide();
        } 
});

$('#user_password_repeat').on('keyup', function(){
  var $repassword =$('#user_password_repeat').val();
        if ($repassword.length<6)
      {
      $("#aviso5").show();
      text='<div class="alert alert-danger" role="alert">La Repetición de la contraseña debe ser mayor a 6 digitos</div>';
        $('#aviso-repite').html(text);
        $("#aviso-repite").show();
        $("#aviso-repite").delay(5000).fadeOut(200);
        $("#aviso5").delay(5000).fadeOut(200);
      } 
        else if ($repassword.length>=6){
            $("#aviso5").hide();
            $('#aviso-repite').hide();
    
          }
});

/* VALIDACION TECLA DE ESPACIO CORREO*/
$("input#user_email").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
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

$('#user_password_new3, #user_password_repeat').on('keyup', function(){
  var $password   =$('#user_password_new').val();
  var $repassword =$('#user_password_repeat').val();

if (($password.length>=6) && ($repassword.length>=6)) {

passwordd($password,$repassword); 
}

      
function passwordd($password,$repassword){
  $("#aviso4").hide();
  $("#aviso4").show();
var text ='';
if($password == $repassword){
  text='<div class="alert alert-success" role="alert">Los campos de contraseña coinciden</div> ';
}else{
  text='<div class="alert alert-danger" role="alert">Los campos de contraseña no coinciden</div>';
}

    $('#aviso-password').html(text);
      $("#aviso-password").show();
      $("#aviso-password").delay(5000).fadeOut(200);
      $("#aviso4").delay(5000).fadeOut(200);
};
          
        


})



















function limpiarcampos()
{
	$('#user_name').val("");
	$('#tipousuario').val("");
	$('#user_email').val("");
	$('#usertelefono').val("");
	$('#user_number').val("");
	$('#pregunta').val("");
	$('#respuesta').val("");
	$('#user_password_new').val("");
	$('#user_password_repeat').val("");
	
}

$( "#guardar_usuario" ).submit(function( event ) {
  $('#guardar_datos').attr("disabled", true);
  
 var parametros = $(this).serialize();
   $.ajax({
      type: "POST",
      url: "../controlador/control-ajax-crear-usuario.php",
      data: parametros,
       beforeSend: function(objeto){
        $(".remover").hide();
        $("#cargando").show();

        },
      success: function(datos){
      $("#cargando").hide();
      $("#resultados_ajax").html(datos);
      $('#guardar_datos').attr("disabled", false);
      $( "#resultados_ajax" ).fadeOut(6000);
      $(".remover").show();
      }
  });
  event.preventDefault();
})

function load_usuario(page)
{
  var FiltroUsuario = $("#FiltroUsuario").val();
  var parametros = {"accion":"cargar","page":page,"FiltroUsuario":FiltroUsuario};
  //$("#loader").fadeIn('slow');
  $.ajax({
    url:'../listas/listar_usuario_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".outer_div").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  })
}