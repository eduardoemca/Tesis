$(document).ready(function()
{
    load_cliente(1);
});

$("#Cerrar-modal-tabla").click(function()
{
	$("#close_modal").click();
});


/////////////////////////////////////////////////////////////// ---------------> INICIO de todo con respecto a CLIENTES
/*NOMBRE KEYUP*/
$('#nombre-cliente').on('keyup', function()
{
	if ($("#nombre-cliente").val()!="")
	{
		$("#aviso1").show();
	  	var nombreup= $('#nombre-cliente').val();

    	if (nombreup.length<3)
    	{
    		text='<div class="alert alert-danger" role="alert">El nombre debe ser más especifico</div>';
    	}
    	else
    	{
    		text= '';
    		$("#aviso1").fadeOut(10);
    	}
	    $("#nombre-aviso").html(text);
	    $("#nombre-aviso").show();
	    $("#nombre-aviso").delay(4000).fadeOut(200);
	    $("#aviso1").delay(4000).fadeOut(200);
 	} 
});

/*APELLIDO KEYUP*/
$('#apellido-cliente').on('keyup', function()
{
	if ($("#apellido-cliente").val()!="")
	{
		$("#aviso1").show();
	  	var apellidoup= $('#apellido-cliente').val();

	    if (apellidoup.length<3)
	    {
	    	text='<div class="alert alert-danger" role="alert">El Apellido debe ser más especifico</div>';
	    }
	    else
	    {
	    	text= '';
	    	$("#aviso1").fadeOut(10);
	    }
	    $("#apellido-aviso").html(text);
	    $("#apellido-aviso").show();
	    $("#apellido-aviso").delay(4000).fadeOut(200);
	    $("#aviso1").delay(4000).fadeOut(200);
 	} 
});

/*DIRECCION KEYUP*/
$('#direccion-cliente').on('keyup', function()
{
	if ($("#direccion-cliente").val()!="")
	{
		$("#aviso2").show();
	  	var direccionup= $('#direccion-cliente').val();

	    if (direccionup.length<5)
	    {
	    	text='<div class="alert alert-danger" role="alert">Direccion debe ser más especifica</div>';
	    } 
	    else 
	    {
	    	text= '';
	    	$("#aviso2").fadeOut(10);
	    }
	    $("#direccion-aviso").html(text);
	    $("#direccion-aviso").show();
	    $("#direccion-aviso").delay(4000).fadeOut(200);
	    $("#aviso2").delay(4000).fadeOut(200);
 	} 
});

/*CORREO KEYUP*/
$('#correo-cliente').on('keyup', function()
{
	if ($("#correo-cliente").val()!="")
	{
		$("#aviso3").show();
	   	var $email = this.value;
	    validateEmail($email);
	    $("#correo-aviso").show();
	    $("#correo-aviso").delay(4000).fadeOut(200);
	    $("#aviso3").delay(4000).fadeOut(200);
 	} 
});

function validateEmail(email)
{
	$("#aviso3").show();
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
/* VALIDACION TECLA DE ESPACIO CORREO*/
$("input#correo-cliente").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});

/*TELEFONO KEYUP*/
$('#telefono-cliente').on('keyup', function()
{
	if ($("#telefono-cliente").val()!="")
	{
		$("#aviso3").show();
	  	var telefonoup= $('#telefono-cliente').val();

	    if (telefonoup.length<11)
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

function validado()
{
	var rif= $("#nacio-cliente").val();
	var cedula= $('#cedula-cliente').val();
	var nombre= $('#nombre-cliente').val();
	var apellido= $('#apellido-cliente').val();
	var direccion= $('#direccion-cliente').val();
	var telefono= $('#telefono-cliente').val();
	var correo= $('#correo-cliente').val();

	expresion=/\w+@\w+\.+[a-z]/;

	var verdad=true;

	if (rif==="RIF")
	{
		alertify.alert("Error!","Seleccione una letra Identificadora", function(){});
		$("#nacio-cliente").focus();
		return false;
	}
	else if (cedula==="")
	{
		alertify.alert("Error!","La Cedula esta Vacía", function(){});
		$('#cedula-cliente').focus();
		return false;
	}
	else if (cedula.length<=5 || cedula.length>8)
	{
		alertify.alert("Informacion!","Cédula no valida", function(){});
		$('#cedula-cliente').focus();
		return false;
	}
	else if (nombre==="")
	{
		alertify.alert("Error!","El Nombre esta Vacío", function(){});
		$('#nombre-cliente').focus();
		return false;
	}
	else if (nombre.length<=3 || nombre.length>25)
	{
		alertify.alert("Informacion!","Nombre no valido", function(){});
		$('#nombre-cliente').focus();
		return false;
	}
	else if (apellido==="")
	{
		alertify.alert("Error!","El Apellido esta Vacío", function(){});
		$('#apellido-cliente').focus();
		return false;
	}
	else if (apellido.length<=4 || apellido.length>25)
	{
		alertify.alert("Informacion!","Apellido no valido", function(){});
		$('#apellido-cliente').focus();
		return false;
	}
	else if (direccion==="")
	{
		alertify.alert("Error!","La dirección esta Vacía", function(){});
		$('#direccion-cliente').focus();
		return false;
	}
	else if (direccion.length<5)
	{
		alertify.alert("Informacion!","Debe dar una dirección coherente", function(){});
		$('#direccion-cliente').focus();
		return false;
	}
	else if (correo==="")
	{
		alertify.alert("Error!","El correo esta Vacío", function(){});
		$('#correo-cliente').focus();
		return false;
	}
	else if (!expresion.test(correo))
	{
		alertify.alert("Informacion!","Debe dar una correo valido", function(){});
		$('#correo-cliente').focus();
		return false;
	}
	else if (telefono==="")
	{
		alertify.alert("Error!","El telefono esta Vacío", function(){});
		$('#telefono-cliente').focus();
		return false;
	}
	else if (telefono.length<11 || telefono.length>11)
	{
		alertify.alert("Informacion!","Debe dar un telefono valido", function(){});
		$('#telefono-cliente').focus();
		return false;
	}
	else
	{
		return verdad;
	}
}

function sololetra(e)
{
	key=e.keyCode || e.which;
	teclado= String.fromCharCode(key).toLowerCase();

	letras =" abcdefghijklmnñopqrstuvwxyzsáéíóú";

	especiales ="8-37-38-46-164";

	teclado_especial=false;

	for(var i in especiales)
	{
		if (key== especiales[i])
		{
			teclado_especial=true; break;
		}
	}
	if (letras.indexOf(teclado)==-1 && !teclado_especial)
	{
		return false;
	}
};

function solonumero(e)
{
	key=e.keyCode || e.which;
	teclado= String.fromCharCode(key).toLowerCase();

	letras ="0123456789";

	especiales ="8-37-38-46";

	teclado_especial=false;

	for(var i in especiales)
	{
		if (key== especiales[i])
		{
			teclado_especial=true; break;
		}
	}
	if (letras.indexOf(teclado)==-1 && !teclado_especial)
	{
		return false;
	}
};



$('#cedula-cliente').keypress(function(event)
{
	return solonumero(event);
});

$('#cedula-cliente').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#cedula-cliente').val();
  	if(key === 13)
  	{
		var rif= $("#nacio-cliente").val();
		var cedula= $('#cedula-cliente').val();

		if(rif==="RIF")
		{
			alertify.alert("Error!","Seleccione una letra Identificadora", function(){});
			$("#nacio-cliente").focus();
			return false;
		}
		else if (cedula==="")
		{
			alertify.alert("Error!","La Cedula o Rif esta Vacío", function(){});
			$('#cedula-cliente').focus();
			return false;
		}
		else
		{
			$('#Consultar').click();
		}
  	} 
});

$('#nombre-cliente').keypress(function(event)
{
	return sololetra(event);
});

$('#nombre-cliente').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#nombre-cliente').val();
  	if(key === 13)
  	{
  		if(validado())
    	{
      		$("#apellido-cliente").focus();
    	}
  	} 
});

$('#apellido-cliente').keypress(function(event)
{
	return sololetra(event);
});

$('#apellido-cliente').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#apellido-cliente').val();
  	if(key === 13)
  	{
    	if(validado())
    	{
      		$("#direccion-cliente").focus();
    	}
  	} 
});

$('#direccion-cliente').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#direccion-cliente').val();
  	if(key === 13)
  	{
    	if(validado())
    	{
      		$("#correo-cliente").focus();
    	}
  	} 
});

$('#correo-cliente').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#correo-cliente').val();
  	if(key === 13)
  	{
    	if(validado())
    	{
      		$("#telefono-cliente").focus();
    	}
  	} 
});

$('#telefono-cliente').keypress(function(event)
{
	return solonumero(event);
});

$('#telefono-cliente').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#telefono-cliente').val();
  	if(key === 13)
  	{
    	if(validado())
    	{
      		$('#Agregar').click();
    	}
  	} 
});

$('#Agregar').on('click',function()
{
	if (validado()) 
	{
		var nacio= $('#nacio-cliente').val();
		var cedula= $('#cedula-cliente').val();
		var nombre= $('#nombre-cliente').val();
		var apellido= $('#apellido-cliente').val();
		var direccion= $('#direccion-cliente').val();
		var telefono= $('#telefono-cliente').val();
		var correo= $('#correo-cliente').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();
		var datos =
		{
			"Agregar":"submit",
			"cedula-cliente" : nacio+cedula,
			"nombre-cliente" : nombre,
			"apellido-cliente" : apellido,
			"direccion-cliente" : direccion,
			"correo-cliente" : correo,
			"telefono-cliente" : telefono,
			"usuario" : usuario,
			"session" : session
		};
		$.ajax(
		{
			data: datos,
       		url:   '../controlador/controlador-cliente.php',
        	type:  'post',
        	success:  function (response)
        	{
            	alertify.alert("Información!", response);
            	limpiarcampos();
        	}, 
			error: function(response)
			{
				alertify.alert("Me equivoqué"+ response);
			}
 		});	
	}
});

$("#Consultar").on('click',function()
{
	var nacio= $('#nacio-cliente').val();
	var cedula= $('#cedula-cliente').val();
	var nombre= $('#nombre-cliente').val();
	var apellido= $('#apellido-cliente').val();
	var direccion= $('#direccion-cliente').val();
	var correo= $('#correo-cliente').val();
	var telefono= $('#telefono-cliente').val();
	
	if (cedula=="")
	{
		alertify.error('Campo Cédula esta vacío');
		$('#cedula-cliente').focus();
		return false;
	}

	var daticos =
	{
		"Consultar":"submit",
		"cedula-cliente" : nacio+cedula
	};

	$.ajax(
	{
		data: daticos,
        url:   '../controlador/controlador-cliente.php',
        type:  'post',
        success: function(response)
        {
       		try
      		{
      			var mostrar=JSON.parse(response);
          		if (mostrar.data.length!=0)
          		{
          			alertify.success("<h5>Correcto al consultar</h5>");
          			for(var i in mostrar.data)
          			{
          				$('#nombre-cliente').val(mostrar.data[i].nombre);
						$('#apellido-cliente').val(mostrar.data[i].apellido);
						$('#direccion-cliente').val(mostrar.data[i].direccion);
						$('#telefono-cliente').val(mostrar.data[i].telefono);
						$('#correo-cliente').val(mostrar.data[i].correo);
          			}
        			desahabilitarcampos();
        			$('#Agregar').attr('disabled',true);
					$('#Actualizar').attr('disabled',false);
					$('#Eliminar').attr('disabled',false);
        		}
        	}
        	catch(Exception)
        	{
          		alertify.error("<h5>No se encuentra el cliente</h5>");
        	}
       	}, 
		error: function(response)
		{
		    alert("Me equivoqué"+ response);
		}
	})
});

function agregar(cedula)
{
	var datos =
	{
		"Consultar-tabla":"submit",
		"cedula-tabla": cedula
	};

	$.ajax(
	{
		data: datos,
        url:   '../controlador/controlador-cliente.php',
        type:  'post',
        success:  function (response)
        {
        	try
        	{
        		var mostrar=JSON.parse(response);
          		if (mostrar.data.length!=0)
          		{
          			alertify.success("<h5>Correcto al consultar</h5>");
          			for(var i in mostrar.data)
          			{
          				var ci= mostrar.data[i].cedula;
          				var res= ci.substring(0,1);
          				var tan= ci.substring(1);
          				$('#nacio-cliente').val(res);
						$('#cedula-cliente').val(tan);
          				$('#nombre-cliente').val(mostrar.data[i].nombre);
						$('#apellido-cliente').val(mostrar.data[i].apellido);
						$('#direccion-cliente').val(mostrar.data[i].direccion);
						$('#telefono-cliente').val(mostrar.data[i].telefono);
						$('#correo-cliente').val(mostrar.data[i].correo);
          			}
        			desahabilitarcampos();
        			$('#Agregar').attr('disabled',true);
					$('#Actualizar').attr('disabled',false);
					$('#Eliminar').attr('disabled',false);
					//$("#close_modal").click();
        		}
        	}
        	catch(Exception)
        	{
        		alertify.error("<h5>No se encuentra el cliente</h5>");
        	}
        }, 
        error: function(response)
        {
		    alert("Me equivoqué"+ response);
		}
	})
}

function activar(cedula)
{
	var usuario = $("#usuariohidden").val();
	var FiltroCliente = $("#FiltroCliente").val();
	var constante = 1;
	var parametros = {"accion":"activar","page":constante,"cedula_cliente":cedula,"FiltroCliente":FiltroCliente, "usuario":usuario};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_cliente_paginado.php',
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

$("#Actualizar").on('click',function()
{
	$('#Actualizar').attr('disabled',true);
	$('#Eliminar').attr('disabled',true);
	$('#Guardar').attr('disabled',false);
	$('#nombre-cliente').attr('disabled',false);
    $('#apellido-cliente').attr('disabled',false);
    $('#direccion-cliente').attr('disabled',false);
    $('#telefono-cliente').attr('disabled',false);
    $('#correo-cliente').attr('disabled',false);
});

$("#Guardar").on('click',function()
{
	if (validado())
	{
		function guardar()
		{
			var cedula= $('#cedula-cliente').val();
			var nacio= $('#nacio-cliente').val();
			var nombre= $('#nombre-cliente').val();
			var apellido= $('#apellido-cliente').val();
			var direccion= $('#direccion-cliente').val();
			var telefono= $('#telefono-cliente').val();
			var correo= $('#correo-cliente').val();
			var usuario = $("#usuariohidden").val();
			var session = $("#sessionhidden").val();
			var daticos =
			{
				"Guardar":"submit",
				"cedula-cliente" : nacio+cedula,
				"nombre-cliente" : nombre,
				"apellido-cliente" : apellido,
				"direccion-cliente" : direccion,
				"telefono-cliente" : telefono,
				"correo-cliente" : correo,
				"usuario" : usuario,
				"session" : session
			};
			
			$.ajax(
			{
		 		data: daticos,
         		url:   '../controlador/controlador-cliente.php',
          		type:  'post',
          		success:  function (response)
          		{
	            	alertify.confirm('Actualizacion Exitosa','Cliente actualizado', function()
	            	{ 
	            	window.location.reload(true);
	            	});
         		}
			});
		}
		alertify.confirm('Guardar','¿Desea guardar los cambios?',
		function()
		{
			guardar();
		},
		function()
		{

		});
	}
});

$("#Eliminar").on('click',function()
{
	function eliminar()
	{
		var cedula= $('#cedula-cliente').val();
		var nacio= $('#nacio-cliente').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();
		var daticos =
		{
			"Eliminar":"submit",
			"cedula-cliente" : nacio+cedula,
			"usuario" : usuario,
			"session" : session
		};

		$.ajax(
		{
			data: daticos,
        	url:   '../controlador/controlador-cliente.php',
       		type:  'post',
        	success: function (response)
        	{
            	alertify.alert('Informacion!','Cliente desactivado Exitosamente', function()
            	{ 
	            	window.location.reload(true);
            	});
        	},
        	error: function(response)
        	{
	       		alert("Me equivoqué"+ response);
	   		}
		});
	}
	alertify.confirm('Eliminar','Desea Eliminar?',
	function()
	{		
		eliminar();
	},
	function()
	{

	});
});

$("#btn-cancelar").on('click',function(e)
{
	alertify.confirm('Cancelar','Desea cancelar el proceso?',
	function()
	{
		alertify.alert("Informacion!",'Saldra de la vista Registro Cliente', function()
		{
			window.location.reload(true);
		});
	},
	function()
	{

	});
});

function desahabilitarcampos()
{
	$('#nacio-cliente').attr('disabled',true); 
    $('#cedula-cliente').attr('disabled',true); /*readonly*/
    $('#nombre-cliente').attr('disabled',true);
    $('#apellido-cliente').attr('disabled',true);
    $('#direccion-cliente').attr('disabled',true);
    $('#telefono-cliente').attr('disabled',true);
    $('#correo-cliente').attr('disabled',true); 
}

function habilitarcampos()
{
	$('#nacio-cliente').attr('disabled',false); 
    $('#cedula-cliente').attr('disabled',false); /*readonly*/
    $('#nombre-cliente').attr('disabled',false);
    $('#apellido-cliente').attr('disabled',false);
    $('#direccion-cliente').attr('disabled',false);
    $('#telefono-cliente').attr('disabled',false);
    $('#correo-cliente').attr('disabled',false); 
}

function limpiarcampos()
{
	$('#cedula-cliente').val("");
	$('#nombre-cliente').val("");
	$('#apellido-cliente').val("");
	$('#direccion-cliente').val("");
	$('#telefono-cliente').val("");
	$('#correo-cliente').val("");
}

function limpiaropciones()
{
	$('#Agregar').attr('disabled',false);
	$('#Guardar').attr('disabled',true);
	$('#Actualizar').attr('disabled',true);
	$('#Eliminar').attr('disabled',true);
}

/////////////////////////////////////////////////////////////// ---------------> FIN de todo con respecto a CLIENTES

function load_cliente(page)
{
	var FiltroCliente = $("#FiltroCliente").val();
	var parametros = {"accion":"cargar","page":page,"FiltroCliente":FiltroCliente};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_cliente_paginado.php',
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