$(document).ready(function()
{
   
});

$('#user_password_new3').on('keyup', function(){
	var $password   =$('#user_password_new3').val();
	if ($password.length<6)
	{	$("#aviso1").show();
	    text='<div class="alert alert-danger" role="alert">La contraseña debe ser mayor a 6 digitos</div>';
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

$('#user_password_repeat3').on('keyup', function(){
	var $repassword =$('#user_password_repeat3').val();
	    	if ($repassword.length<6)
			{
			$("#aviso2").show();
			text='<div class="alert alert-danger" role="alert">La Repetición de la contraseña debe ser mayor a 6 digitos</div>';
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

$('#user_password_new3, #user_password_repeat3').on('keyup', function(){
	var $password   =$('#user_password_new3').val();
	var $repassword =$('#user_password_repeat3').val();

if ($password.length>=6 && $repassword.length>=6) {

passwordd($password,$repassword);	
}

			
function passwordd($password,$repassword){
	$("#aviso1").hide();
	$("#aviso1").show();
var text ='';
if($password == $repassword){
	text='<div class="alert alert-success" role="alert">Los campos de contraseña coinciden</div> ';
}else{
	text='<div class="alert alert-danger" role="alert">Los campos de contraseña no coinciden</div>';
}

		$('#password-confirm').html(text);
	    $("#password-confirm").show();
	    $("#password-confirm").delay(5000).fadeOut(200);
	    $("#aviso1").delay(5000).fadeOut(200);
};
								
})