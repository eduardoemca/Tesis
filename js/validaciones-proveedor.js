$(document).ready(function()
{
    $("#tabla-agregar-proveedor").hide();
	$("#btn-salir-proveedor").hide();

    load_proveedor(1);
    load_producto(1);
    load_producto_proveedor(1);
});

$("#Cerrar-modal-tabla").click(function()
{
	$("#close_modal").click();
});


/////////////////////////////////////////////////////////////// ---------------> INICIO de todo con respecto a PROVEEDOR
/*RAZON KEYUP*/
$('#nombre-proveedor').on('keyup', function()
{
	if ($("#nombre-proveedor").val()!="")
	{
		$("#aviso2").show();
	  	var razonup= $('#nombre-proveedor').val();

	    if (razonup.length<5)
	    {
	    	text='<div class="alert alert-danger" role="alert">La razón social debe ser más especifica</div>';
	    } 
	    else 
	    {
	    	text= '';
	    	$("#aviso2").fadeOut(10);
	    }
	    $("#nombre-aviso").html(text);
	    $("#nombre-aviso").show();
	    $("#nombre-aviso").delay(4000).fadeOut(200);
	    $("#aviso1").delay(4000).fadeOut(200);
 	} 
});


/*DIRECCION KEYUP*/
$('#direccion-proveedor').on('keyup', function()
{
	if ($("#direccion-proveedor").val()!="")
	{
		$("#aviso2").show();
	  	var direccionup= $('#direccion-proveedor').val();

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
$('#correo-proveedor').on('keyup', function()
{
	if ($("#correo-proveedor").val()!="")
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
$("input#correo-proveedor").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});

/*TELEFONO KEYUP*/
$('#telefono-proveedor').on('keyup', function()
{
	if ($("#telefono-proveedor").val()!="")
	{
		$("#aviso3").show();
	  	var telefonoup= $('#telefono-proveedor').val();

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
	var rif= $("#nacio-proveedor").val();
	var identificacion= $('#identificacion-proveedor').val();
	var nombre= $('#nombre-proveedor').val();
	var direccion= $('#direccion-proveedor').val();
	var correo= $('#correo-proveedor').val();
	var telefono= $('#telefono-proveedor').val();

	expresion=/\w+@\w+\.+[a-z]/;

	var verdad=true;

	if (rif==="RIF")
	{
		alertify.alert("Error!","Seleccione una letra Identificadora", function(){});
		$("#nacio-proveedor").focus();
		return false;
	}
	else if (identificacion==="") 
	{
		alertify.alert("Error!","La Identificación esta Vacía", function(){});
		$('#identificacion-cliente').focus();
		return false;
	}
	else if (identificacion.length<=5 || identificacion.length>8)
	{
		alertify.alert("Informacion!","Identificación no valida", function(){});
		$('#identificacion-cliente').focus();
		return false;
	}
	else if (nombre==="") 
	{
		alertify.alert("Error!","La Razón Social esta Vacía", function(){});
		$('#nombre-proveedor').focus();
		return false;
	}
	else if (nombre.length<=3 || nombre.length>35)
	{
		alertify.alert("Informacion!","Razón Social no valida", function(){});
		$('#nombre-proveedor').focus();
		return false;
	}
	else if (direccion==="") 
	{
		alertify.alert("Error!","La dirección esta Vacía", function(){});
		$('#direccion-proveedor').focus();
		return false;
	}
	else if (direccion.length<5) 
	{
		alertify.alert("Informacion!","Debe dar una dirección coherente", function(){});
		$('#direccion-proveedor').focus();
		return false;
	}
	else if (correo==="") 
	{
		alertify.alert("Error!","El correo esta Vacío", function(){});
		$('#correo-proveedor').focus();
		return false;
	}
	else if (!expresion.test(correo)) 
	{
		alertify.alert("Informacion!","Debe dar una correo valido", function(){});
		$('#correo-proveedor').focus();
		return false;
	}
	else if (telefono==="") 
	{
		alertify.alert("Error!","El telefono esta Vacío", function(){});
		$('#telefono-proveedor').focus();
		return false;
	}
	else if (telefono.length<11 || telefono.length>11) 
	{
		alertify.alert("Informacion!","Debe dar un telefono valido", function(){});
		$('#telefono-proveedor').focus();
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

$('#identificacion-proveedor').keypress(function(event)
{
	return solonumero(event);
});

$('#identificacion-proveedor').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#identificacion-proveedor').val();
  	if(key === 13)
  	{
		var rif= $("#nacio-proveedor").val();
		var cedula= $('#identificacion-proveedor').val();

		if(rif==="RIF")
		{
			alertify.alert("Error!","Seleccione una letra Identificadora", function(){});
			$("#nacio-cliente").focus();
			return false;
		}
		else if (cedula==="")
		{
			alertify.alert("Error!","La Cedula o Rif esta Vacío", function(){});
			$('#identificacion-proveedor').focus();
			return false;
		}
		else
		{
			$('#Consultar').click();
		}
  	} 
});

$('#nombre-proveedor').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#nombre-proveedor').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$("#direccion-proveedor").focus();
      	}
    } 
});

$('#direccion-proveedor').keypress(function(event)
{
 	key=event.keyCode || event.which;
  	var veri=$('#direccion-proveedor').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$("#correo-proveedor").focus();
      	}
    } 
});

$('#correo-proveedor').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#correo-proveedor').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$("#telefono-proveedor").focus();
      	}
    } 
});

$('#telefono-proveedor').keypress(function(event)
{
	return solonumero(event);
});

$('#telefono-proveedor').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#telefono-proveedor').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$("#Agregar").click();
      	}
    } 
});

$("#btn-agregar-proveedor").click(function()
{
	$("#btn-agregar-proveedor").hide();
	$("#btn-salir-proveedor").show();
	$("#tabla-agregar-proveedor").slideToggle();
	alertify.success("Agregue quien le provee este Producto");
});

$("#btn-salir-proveedor").on('click',function()
{
	$("#btn-salir-proveedor").hide();
	$("#btn-agregar-proveedor").show();
	$("#tabla-agregar-proveedor").slideToggle();
});

function verificarcodigo_producto(idproducto)
{
	var constante = 1;
	var tableReg = document.getElementById('tablaproducto_2');
	var codigocom= idproducto;
	var codigop= "";
  	try
  	{
  		for(var i = 1; i < tableReg.rows.length; i++)
  		{
  			var codigop= (tableReg.rows[i].cells[0].innerHTML);
  			if (codigop==codigocom) 
  			{
    			alertify.alert("Error","Ya existe este producto en la tabla, por favor verifique");
    			return false;
  			}
		}
		agregar_producto_proveedor(constante,idproducto);
  		return false;
  	}
  	catch(Exception)
  	{
    	alert(Exception);
  	}
}

function agregar_producto_proveedor(page,idproducto)
{
	var FiltroProductoProveedor = $("#FiltroProductoProveedor").val();
	var parametros = {"accion":"agregar","page":page,"id_producto":idproducto,"FiltroProductoProveedor":FiltroProductoProveedor};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_producto_proveedor_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_producto").html(data).fadeIn('slow');
			if(tablaproducto_2.rows.length>1)
			{
				$("#Agregar").attr('disabled',false);
				reactivar_proveedor_producto(idproducto);
			}
			//$("#loader").html("");
		}
	});
}

function reactivar_proveedor_producto(idproducto)
{
	var nacio= $('#nacio-proveedor').val();
	var cedula= $('#identificacion-proveedor').val();

	var datos=
	{
		"Reactivar_Proveedor":"submit",
		"codigo_proveedor": nacio+cedula,
		"codigo_producto": idproducto
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-proveedor_producto.php',
		type: 'post',
		success: function(response)
		{
			//alert(response);
		},
		error: function(response)
		{
			alert("Me equivoqué"+ response);
		}
	});
}

function eliminar_producto(id_tmp,page)
{
	var idn=id_tmp.toString();
	var parametros = {"accion":"eliminar","page":page,"id":idn};
   	alertify.confirm("Eliminar","Desea eliminar el proveedor?"
    ,function()
    {
    	$.ajax(
    	{
    		type: "POST",
    		url: "../listas/listar_producto_proveedor_paginado.php",
    		data: parametros,
    		success: function(datos)
    		{
    			eliminar_producto_proveedor(id_tmp);
				$("#tabla_producto").html(datos);	
				if(tablaproducto_2.rows.length===1)
				{
					$("#Agregar").attr('disabled',true);
				}
			}
    	});
    },
    function()
    {
    	alertify.error("Cancelado");
    });
}

function eliminar_producto_proveedor(id_tmp)
{
	var nacio= $('#nacio-proveedor').val();
	var cedula= $('#identificacion-proveedor').val();
	var codigoproducto = $('#codigo_'+id_tmp).val();

	var datos=
	{
		"Eliminar-proveedor-producto":"submit",
		"codigo-proveedor": nacio+cedula,
		"codigo-producto": codigoproducto
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-producto.php',
		type: 'post',
		success: function(response)
		{
			//alert(response);
		},
		error: function(response)
		{
			alert("Me equivoqué"+ response);
		}
	});
}

function producto_provedor(page)
{
	var nacio= $('#nacio-proveedor').val();
	var cedula= $('#identificacion-proveedor').val();
	var identificacion = nacio+cedula;
	var parametros = {"accion":"registrar","page":page,"identificacion":identificacion};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_producto_proveedor_paginado.php',
		type: "POST",
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_producto").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}

$('#Agregar').on('click',function()
{
	if (validado())
	{
		var nacio= $('#nacio-proveedor').val();
		var cedula= $('#identificacion-proveedor').val();
		var nombre= $('#nombre-proveedor').val();
		var direccion= $('#direccion-proveedor').val();
		var telefono= $('#telefono-proveedor').val();
		var correo= $('#correo-proveedor').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();
		var daticos = 
		{
			"Agregar":"submit",
			"identificacion-proveedor" : nacio+cedula,
			"nombre-proveedor" : nombre,
			"direccion-proveedor" : direccion,
			"correo-proveedor" : correo,
			"telefono-proveedor" : telefono,
			"usuario" : usuario,
			"session" : session
		};
		$.ajax(
		{
			data: daticos,
            url:   '../controlador/controlador-proveedor.php',
            type:  'post',
            success:  function (response)
            {
            	producto_provedor();
            	alertify.alert("Informacion!","Registro de proveedor Exitoso", function()
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
});

$("#Consultar").on('click',function()
{
	var nacio= $('#nacio-proveedor').val();
	var identificacion= $('#identificacion-proveedor').val();
	var razon_social= $('#nombre-proveedor').val();
	var direccion= $('#direccion-proveedor').val();
	var correo= $('#correo-proveedor').val();
	var telefono= $('#telefono-proveedor').val();
	
	if (identificacion=="")
	{
		alertify.error('Campo Identificación esta vacío');
		$('#identificacion-cliente').focus();
		return false;
	}

	var daticos = 
	{
		"Consultar":"submit",
		"identificacion-proveedor" : nacio+identificacion
	};

	$.ajax(
	{
		data: daticos,
        url:   '../controlador/controlador-proveedor.php',
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
          				$('#nombre-proveedor').val(mostrar.data[i].razon_social);
						$('#direccion-proveedor').val(mostrar.data[i].direccion);
						$('#telefono-proveedor').val(mostrar.data[i].telefono);
						$('#correo-proveedor').val(mostrar.data[i].correo);
					}
					desahabilitarcampos();
        			$('#Agregar').attr('disabled',true);
					$('#Actualizar').attr('disabled',false);
					$('#Guardar').attr('disabled',true);
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

function agregar(identificacion)
{
	var constante = 1;
	var datos = 
	{
		"Consultar-tabla":"submit",
		"identificacion-tabla": identificacion
	};

	$.ajax(
	{
		data: datos,
        url:   '../controlador/controlador-proveedor.php',
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
          				var ci= mostrar.data[i].identificacion;
          				var res= ci.substring(0,1);
          				var tan= ci.substring(1);
          				$('#nacio-proveedor').val(res);
          				$('#identificacion-proveedor').val(tan);
          				$('#nombre-proveedor').val(mostrar.data[i].razon_social);
						$('#direccion-proveedor').val(mostrar.data[i].direccion);
						$('#telefono-proveedor').val(mostrar.data[i].telefono);
						$('#correo-proveedor').val(mostrar.data[i].correo);
          			}
        			desahabilitarcampos();
        			listar_tabla_producto_proveedor(constante,identificacion);
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

function activar(identificacion)
{   
	var usuario = $("#usuariohidden").val();
	var FiltroProveedor = $("#FiltroProveedor").val();
	var constante = 1;
	var parametros = {"accion":"activar","page":constante,"identificacion":identificacion,"FiltroProveedor":FiltroProveedor, "usuario":usuario};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_proveedor_paginado.php',
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

function listar_tabla_producto_proveedor(page,identificacion)
{
	var parametros = {"accion":"listar","page":page,"id_proveedor":identificacion};
  	$.ajax(
  	{
   		data: parametros,
    	url:'../listas/listar_producto_proveedor_paginado.php',
    	type: 'post',
    	success: function(data)
    	{
      		$("#tabla_producto").html(data).fadeIn('slow');
    	},
    	error: function(data)
    	{
      	alert("Me equivoqué"+ response);
    	}
 	});
}

$("#Actualizar").on('click',function()
{
	$('#Actualizar').attr('disabled',true);
	$('#Eliminar').attr('disabled',true);
	$('#Guardar').attr('disabled',false);
	$('#nombre-proveedor').attr('disabled',false);
    $('#direccion-proveedor').attr('disabled',false);
    $('#telefono-proveedor').attr('disabled',false);
    $('#correo-proveedor').attr('disabled',false);
});

$("#Guardar").on('click',function()
{
	if (validado())
	{
		function guardar()
		{
			var cedula= $('#identificacion-proveedor').val();
			var nacio= $('#nacio-proveedor').val();
			var nombre= $('#nombre-proveedor').val();
			var direccion= $('#direccion-proveedor').val();
			var telefono= $('#telefono-proveedor').val();
			var correo= $('#correo-proveedor').val();
			var usuario = $("#usuariohidden").val();
			var session = $("#sessionhidden").val();
			var daticos = 
			{
				"Guardar":"submit",
				"identificacion-proveedor" : nacio+cedula,
				"nombre-proveedor" : nombre,
				"direccion-proveedor" : direccion,
				"correo-proveedor" : correo,
				"telefono-proveedor" : telefono,
				"usuario" : usuario,
				"session" : session
			};
			$.ajax(
			{
		 		data: daticos,
          		url:   '../controlador/controlador-proveedor.php',
          		type:  'post',
          		success:  function (response)
          		{
	            	alertify.alert('Actualizacion Exitosa','Proveedor actualizado', function(){ 
	            	window.location.reload(true); });
         		}, 
         		error: function(response)
         		{
	       			alert("Me equivoqué"+ response);
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
			alertify.error('Cancelado');
		});
	}
});

$("#Eliminar").on('click',function()
{
	function eliminar()
	{
		var identificacion= $('#identificacion-proveedor').val();
		var nacio= $('#nacio-proveedor').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();
		var daticos = 
		{
			"Eliminar":"submit",
			"identificacion-proveedor" : nacio+identificacion,
			"usuario" : usuario,
			"session" : session
		};

		$.ajax(
		{
			data: daticos,
	        url:   '../controlador/controlador-proveedor.php',
	        type:  'post',
	        success:  function (response)
	        {
				alertify.alert('Inactivacion Exitosa','Proveedor Inactivado', function(){ 
	            window.location.reload(true); });
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
			alertify.error('Cancelado');
		});
});

$("#btn-cancelar").on('click',function(e)
{
	alertify.confirm('Cancelar','Desea cancelar el proceso?',
	function()
	{
		alertify.alert("Informacion!",'Saldra de la vista Registro Proveedor', function()
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
	$('#nacio-proveedor').attr('disabled',true); 
    $('#identificacion-proveedor').attr('disabled',true);
    $('#nombre-proveedor').attr('disabled',true);
    $('#apellido-proveedor').attr('disabled',true);
    $('#direccion-proveedor').attr('disabled',true);
    $('#telefono-proveedor').attr('disabled',true);
    $('#correo-proveedor').attr('disabled',true); 
}

function habilitarcampos()
{
	$('#nacio-proveedor').attr('disabled',false); 
    $('#identificacion-proveedor').attr('disabled',false);
    $('#nombre-proveedor').attr('disabled',false);
    $('#direccion-proveedor').attr('disabled',false);
    $('#telefono-proveedor').attr('disabled',false);
    $('#correo-proveedor').attr('disabled',false); 
}

function limpiarcampos()
{
	$('#identificacion-proveedor').val("");
	$('#nombre-proveedor').val("");
	$('#direccion-proveedor').val("");
	$('#telefono-proveedor').val("");
	$('#correo-proveedor').val("");
}

function limpiaropciones()
{
	$('#Agregar').attr('disabled',false);
	$('#Guardar').attr('disabled',true);
	$('#Actualizar').attr('disabled',true);
	$('#Eliminar').attr('disabled',true);
}
/////////////////////////////////////////////////////////////// ---------------> FIN de todo con respecto a PROVEEDOR

function load_proveedor(page)
{
	var FiltroProveedor = $("#FiltroProveedor").val();
	var parametros = {"accion":"cargar","page":page,"FiltroProveedor":FiltroProveedor};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_proveedor_paginado.php',
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

function load_producto(page)
{
	var FiltroProducto = $("#FiltroProducto").val();
	var parametros = {"accion":"cargar","page":page,"FiltroProducto":FiltroProducto};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_productos_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".producto_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	})
}

function load_producto_proveedor(page,idproducto)
{
	var parametros = {"accion":"cargar","page":page,"id_proveedor":idproducto};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_producto_proveedor_paginado.php',
		type: "POST",
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_producto").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}
