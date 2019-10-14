$(document).ready(function()
{
	/*$("#tabla-agregar-inventario").hide();
	$("#btn-salir-inventario").hide();*/
	$("#tabla-agregar-proveedor").hide();
	$("#btn-salir-proveedor").hide();

	load_producto(1);
	load_categoria(1);
	load_unidad(1);
	load_iva(1);
	/*load_inventario(1);
	load_inventario_producto(1);*/
	load_proveedor_producto(1);
	load_proveedor(1);

		if ($('#gravado').val() == 'INACTIVO') {
			$('#btn_iva_modal').attr('disabled',true);
		}
	$("#gravado").change(function(){
		if ($('#gravado').val() == 'INACTIVO') {
			$('#btn_iva_modal').attr('disabled',true);
		}
		else if ($('#gravado').val() == 'ACTIVO') {
			$('#btn_iva_modal').attr('disabled',false);
		}
	});

});

////////////////////////////////////////////////////////////////////// ---------------> INICIO de Todo con respecto a CATEGORIA

function validacion_categoria()
{
	var nombre_categoria= $('#nombre-categoria-modal').val();
	var descripcion_categoria= $('#descripcion-categoria-modal').val();

	var verdad=true;

	if (nombre_categoria=="")
	{
		alertify.alert("Error!","La categoría esta Vacía", function(){});
		$('#nombre-categoria-modal').focus();
		return false;
	}
	else if (nombre_categoria.length<=2 || nombre_categoria.length>45) 
	{
		alertify.alert("Información!","Categoría no valida", function(){});
		$('#nombre-categoria-modal').focus();
		return false;
	}
	else if (descripcion_categoria=="") 
	{
		alertify.alert("Error!","Descripción esta Vacío", function(){});
		$('#descripcion-categoria-modal').focus();
		return false;
	}
	else if (descripcion_categoria.length<5) 
	{
		alertify.alert("Información!","Debe dar una descripción valida", function(){});
		$('#descripcion-categoria-modal').focus();
		return false;
	}
	else
	{
		return verdad;
	}
}

$("#close_Categoria").click(function()
{
	limpiarcampos_categoria();
	habilitarcampos_categoria();
	limpiaropciones_categoria();
});

$('#nombre-categoria-modal').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#nombre-categoria-modal').val();
  	if(key === 13)
  	{
    	if(validacion_categoria())
    	{
      		$("#descripcion-categoria-modal").focus();
    	}
    } 
});

$('#nombre-categoria-modal').keypress(function(event)
{
	return sololetra(event);
});

$('#descripcion-categoria-modal').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#descripcion-categoria-modal').val();
  	if(key === 13)
  	{
    	if(validacion_categoria())
      	{
      		$("#Agregar-categoria-modal").click();
      	}
    } 
});

$('#descripcion-categoria-modal').keypress(function(event)
{
	return sololetra(event);
});

$("#Agregar-categoria-modal").click(function()
{
	var constante = 1;
	if (validacion_categoria())
	{
		var nombre_categoria= $('#nombre-categoria-modal').val();
		var descripcion_categoria= $('#descripcion-categoria-modal').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();

      	var datos =
      	{
      		"Agregar-categoria-modal":"submit",
      		"nombre-categoria-modal" : nombre_categoria,
      		"descripcion-categoria-modal" : descripcion_categoria,
      		"usuario" : usuario,
			"session" : session
      	};
      	$.ajax(
      	{
      		data: datos,
        	url:   '../controlador/controlador-categoria.php',
        	type:  'post',
        success: function(response)
        {
        	alertify.alert("Información!",response);
        	limpiarcampos_categoria();
        	load_categoria(constante);
        	 /*$(".active").removeClass('activa');
            $(".active").load('../vista/producto.php').fadeIn('slow');*/
            var bien = '<h5></h5>'
            $('#noexiste').html(bien);
            $(".modal-backdrop.fade.in").remove();
        }, 
        error: function(response)
        {
        	alertify.alert("Me equivoqué"+ response);
        }
    }); 
   }
});

function agregar_categoria(codigo_categoria)
{
	var nombre_categoria= $('#nombre-categoria-modal').val();
	var descripcion_categoria= $('#descripcion-categoria-modal').val();
	var codigo=codigo_categoria;

	var datos =
	{
		"Consultar":"submit",
		"codigo-tabla": codigo
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-categoria.php',
		type: 'post',
		success: function(response)
		{
			console.log(codigo);
			try
			{
				var mostrar= JSON.parse(response);
				if (mostrar.data.length!=0)
				{
					alertify.alert("Información","<h5>Consulta exitosa</h5>");
					for(var i in mostrar.data)
					{
						$('#codigo-categoria-modal').val(mostrar.data[i].id_categoria);
						$('#nombre-categoria-modal').val(mostrar.data[i].nombre);
						$('#descripcion-categoria-modal').val(mostrar.data[i].descripcion);
					}
					desahabilitarcampos_categoria();
					$('#Agregar-categoria-modal').attr('disabled',true);
					$('#Actualizar-categoria-modal').attr('disabled',false);
					$('#Eliminar-categoria-modal').attr('disabled',false);
					$(".modal-backdrop.fade.in").remove();
				}
				load_categoria(1);
			}
			catch(Exception)
			{
				alertify.alert("Información!","<h5>No se encuentra la categoría</h5>");
			}
		},
		error: function(response)
		{
			alertify.alert("Me equivoqué"+ response);
		}
	});
}

function activar_categoria(codigo_categoria)
{
	var usuario = $("#usuariohidden").val();
	var FiltroCategoria = $("#FiltroCategoria").val();
	var constante = 1;
	var parametros = {"accion":"activar","page":constante,"codigo_categoria":codigo_categoria,"FiltroCategoria":FiltroCategoria, "usuario":usuario};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_categoria_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".categoria_div").html(data).fadeIn('slow');
			$(".modal-backdrop.fade.in").remove();
			//$("#loader").html("");
		}
	})
}

$("#Actualizar-categoria-modal").click(function()
{
	$('#nombre-categoria-modal').attr('disabled',false);
    $('#descripcion-categoria-modal').attr('disabled',false);
	$('#Actualizar-categoria-modal').attr('disabled',true);
	$('#Eliminar-categoria-modal').attr('disabled',true);
	$('#Guardar-categoria-modal').attr('disabled',false);
});

$("#Guardar-categoria-modal").click(function()
{
	var constante = 1;
	if (validacion_categoria())
	{
		function guardar_categoria()
		{
			var codigo_categoria= $('#codigo-categoria-modal').val();
			var nombre_categoria= $('#nombre-categoria-modal').val();
			var descripcion_categoria= $('#descripcion-categoria-modal').val();
			var usuario = $("#usuariohidden").val();
			var session = $("#sessionhidden").val();

			var datos= 
			{
				"Guardar-categoria-modal": "submit",
				"codigo-categoria-modal": codigo_categoria,
				"nombre-categoria-modal": nombre_categoria,
				"descripcion-categoria-modal": descripcion_categoria,
	      		"usuario" : usuario,
				"session" : session
			};
			$.ajax(
			{
				data: datos,
				url: '../controlador/controlador-categoria.php',
				type: 'post',
				success: function(response)
				{
					alertify.alert("Información!",response);
					load_categoria(constante);
				},
				error: function(response)
				{
					alert("Me equivoqué"+ response);
				}
			});
		}
		alertify.confirm('Información!','¿Desea guardar los cambios?',
		function()
		{
			guardar_categoria();
			habilitarcampos_categoria();
			limpiarcampos_categoria();
			limpiaropciones_categoria();
		},
		function()
		{

		});
	}
});

$("#Eliminar-categoria-modal").on('click',function()
{
	var constante = 1;
	function eliminar_categoria()
	{
		var codigo_categoria= $('#codigo-categoria-modal').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();

		var datos =
		{
			"Eliminar-categoria-modal":"submit",
			"codigo-categoria-modal" : codigo_categoria,
			"usuario" : usuario,
			"session" : session
		};

		$.ajax(
		{
			data: datos,
       		url:   '../controlador/controlador-categoria.php',
       		type:  'post',
       		success:  function (response)
       		{
       			alertify.alert("Información!",response);
       			load_categoria(constante);
           		habilitarcampos_categoria();
           		limpiarcampos_categoria();
           		limpiaropciones_categoria();
      		},
      		error: function(response)
      		{
       			alertify.alert("Me equivoqué"+ response);
      		}
       	});
	}
	alertify.confirm('Información!','¿Desea desactivar la categoria?',
	function()
	{
		eliminar_categoria();
	},
	function()
	{

	});
});

$("#btn-cancelar-categoria").on('click',function()
{
	$(".modal-backdrop.fade.in").remove();
	$("#close_Categoria").click();
});

function desahabilitarcampos_categoria()
{
	$('#nombre-categoria-modal').attr('disabled',true); /*readonly*/
    $('#descripcion-categoria-modal').attr('disabled',true);
}

function habilitarcampos_categoria()
{
	$('#nombre-categoria-modal').attr('disabled',false); /*readonly*/
    $('#descripcion-categoria-modal').attr('disabled',false);
}

function limpiarcampos_categoria()
{
	$('#nombre-categoria-modal').val("");
	$('#descripcion-categoria-modal').val("");
}

function limpiaropciones_categoria()
{
	$('#Agregar-categoria-modal').attr('disabled',false);
	$('#Guardar-categoria-modal').attr('disabled',true);
	$('#Actualizar-categoria-modal').attr('disabled',true);
	$('#Eliminar-categoria-modal').attr('disabled',true);
}

function load_categoria(page)
{
	var FiltroCategoria = $("#FiltroCategoria").val();
	var parametros = {"accion":"cargar","page":page,"FiltroCategoria":FiltroCategoria};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_categoria_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".categoria_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	})
}

////////////////////////////////////////////////////////////////////// ---------------> FINAL de Todo con respecto a CATEGORIA

////////////////////////////////////////////////////////////////////// ---------------> INCIO de Todo con respecto a UNIDAD
function validar_unidad()
{
	var nombre_unidad= $('#nombre-unidad-modal').val();
	var descripcion_unidad= $('#descripcion-unidad-modal').val();

	var verdad=true;

	if (nombre_unidad=="") 
	{
		alertify.alert("Error!","La unidad esta vacía", function(){});
		$('#nombre-unidad-modal').focus();
		return false;
	}
	else if (nombre_unidad.length<=2 || nombre_unidad.length>45) 
	{
		alertify.alert("Información!","Unidad no valida", function(){});
		$('#nombre-unidad-modal').focus();
		return false;
	}
	else if (descripcion_unidad=="") 
	{
		alertify.alert("Error!","La descripción esta vacía", function(){});
		$('#descripcion-unidad-modal').focus();
		return false;
	}
	else if (descripcion_unidad.length<5) 
	{
		alertify.alert("Información!","Debe dar una descripción valida", function(){});
		$('#descripcion-unidad-modal').focus();
		return false;
	}
	else
	{
		return verdad;
	}
}

function solo_unidad(e)
{
	key=e.keyCode || e.which;
	teclado= String.fromCharCode(key).toLowerCase();
	letras =" abcdefghijklmnñopqrstuvwxyzsáéíóú,";
	especiales ="8-37-38-46-164";
	teclado_especial= false;

	for(var i in especiales)
	{
		if (key==especiales[i]) 
		{
			teclado_especial= true;
			break;
		}
	}
	if (letras.indexOf(teclado)==-1 && !teclado_especial) 
	{
		return false;
	}
};

function sololetra_unidad(e)
{
	key=e.keyCode || e.which;
	teclado= String.fromCharCode(key).toLowerCase();
	letras =" abcdefghijklmnñopqrstuvwxyzsáéíóú()";
	especiales ="8-37-38-46-164";

	teclado_especial= false;

	for(var i in especiales)
	{
		if (key==especiales[i]) 
		{
			teclado_especial= true;
			break;
		}
	}
	if (letras.indexOf(teclado)==-1 && !teclado_especial)
	{
		return false;
	}
};

$('#nombre-unidad-modal').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#nombre-unidad-modal').val();
  	if(key === 13)
  	{
    	if(validar_unidad())
      	{
      		$("#descripcion-unidad-modal").focus();
      	}
    } 
});

$('#nombre-unidad-modal').keypress(function(event)
{
	return sololetra_unidad(event);
});

$('#descripcion-unidad-modal').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#descripcion-unidad-modal').val();
  	if(key === 13)
  	{
    	if(validar_unidad())
      	{
      		$("#Agregar-unidad-modal").click();
      	}
    } 
});

$('#descripcion-unidad-modal').keypress(function(event)
{
	return solo_unidad(event);
});

$("#close_Unidad").click(function()
{
	limpiarcampos_unidad();
	habilitarcampos_unidad();
	limpiaropciones_unidad();
});

$("#Agregar-unidad-modal").click(function()
{
	var constante = 1;
	if (validar_unidad())
	{
		var nombre_unidad= $('#nombre-unidad-modal').val();
		var descripcion_unidad= $('#descripcion-unidad-modal').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();

      	var datos = 
      	{
      		"Agregar-unidad-modal":"submit",
      		"nombre-unidad-modal" : nombre_unidad,
     		"descripcion-unidad-modal" : descripcion_unidad,
     		"usuario" : usuario,
			"session" : session
     	};

      	$.ajax(
      	{
      		data: datos,
        	url:   '../controlador/controlador-unidad.php',
        	type:  'post',
        	success:  function (response)
        	{
	        	alertify.alert("Información!",response);
	            limpiarcampos_unidad();
	            load_unidad(constante);
	             /*$(".active").removeClass('activa');
	            $(".active").load('../vista/producto.php').fadeIn('slow');*/
	            var bien = '<h5></h5>'
	            $('#noexiste').html(bien);
	            $(".modal-backdrop.fade.in").remove();
	        }, 
	        error: function(response)
	        {
	        	alertify.alert("Me equivoqué"+ response);
	        }
	    }); 
	}
 });

function agregar_unidad(codigo_unidad)
{
	var constante = 1;
	var nombre_unidad = $('#nombre-unidad-modal').val();
	var descripcion_unidad = $('#descripcion-unidad-modal').val();
	var codigo = codigo_unidad;
	var datos =
	{
		"Consultar":"submit",
		"codigo-tabla": codigo
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-unidad.php',
		type: 'post',
		success: function(response)
		{
			console.log(codigo);
			try
			{
				var mostrar= JSON.parse(response);
				if (mostrar.data.length!=0)
				{
					alertify.alert("Información!","<h5>Consulta exitosa</h5>");
					for(var i in mostrar.data)
					{
						$('#codigo-unidad-modal').val(mostrar.data[i].id_unidad);
						$('#nombre-unidad-modal').val(mostrar.data[i].nombre);
						$('#descripcion-unidad-modal').val(mostrar.data[i].descripcion);
					}
					desahabilitarcampos_unidad();
					$('#Agregar-unidad-modal').attr('disabled',true);
					$('#Actualizar-unidad-modal').attr('disabled',false);
					$('#Eliminar-unidad-modal').attr('disabled',false);
					$(".modal-backdrop.fade.in").remove();
				}
				load_unidad(constante);
			}
			catch(Exception)
			{
				alertify.alert("Información!","<h5>No se encuentra la unidad</h5>");
			}
		},
		error: function(response)
		{
			alertify.alert("Me equivoqué"+ response);
		}
	});
}

function activar_unidad(codigo_unidad)
{
	var usuario = $("#usuariohidden").val();
	var FiltroUnidad = $("#FiltroUnidad").val();
	var constante = 1;
	var parametros = {"accion":"activar","page":constante,"codigo_unidad":codigo_unidad,"FiltroUnidad":FiltroUnidad, "usuario":usuario};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_unidad_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".unidad_div").html(data).fadeIn('slow');
			$(".modal-backdrop.fade.in").remove();
			//$("#loader").html("");
		}
	})
}

$("#Actualizar-unidad-modal").click(function()
{
	$('#nombre-unidad-modal').attr('disabled',false);
    $('#descripcion-unidad-modal').attr('disabled',false);
	$('#Actualizar-unidad-modal').attr('disabled',true);
	$('#Eliminar-unidad-modal').attr('disabled',true);
	$('#Guardar-unidad-modal').attr('disabled',false);
});

$("#Guardar-unidad-modal").click(function()
{
	var constante = 1;
	if (validar_unidad())
	{
		function guardar_unidad()
		{
			var codigo_unidad= $('#codigo-unidad-modal').val();
			var nombre_unidad= $('#nombre-unidad-modal').val();
			var descripcion_unidad= $('#descripcion-unidad-modal').val();
			var usuario = $("#usuariohidden").val();
			var session = $("#sessionhidden").val();

			var datos= 
			{
				"Guardar-unidad-modal": "submit",
				"codigo-unidad-modal": codigo_unidad,
				"nombre-unidad-modal": nombre_unidad,
				"descripcion-unidad-modal": descripcion_unidad,
				"usuario" : usuario,
				"session" : session
			};
			$.ajax(
			{
				data: datos,
				url: '../controlador/controlador-unidad.php',
				type: 'post',
				success: function(response)
				{
					alertify.alert("Información!",response);
					load_unidad(constante);
				},
				error: function(response)
				{
					alertify.alert("Me equivoqué"+ response);
				}
			});
		}
		alertify.confirm('Información!','¿Desea guardar los cambios?',
		function()
		{
			guardar_unidad();
			habilitarcampos_unidad();
			limpiarcampos_unidad();
			limpiaropciones_unidad();
		},
		function()
		{

		});
	}
});

$("#Eliminar-unidad-modal").on('click',function()
{
	var constante = 1;
	function eliminar_unidad()
	{
		var codigo_unidad= $('#codigo-unidad-modal').val();
		var nombre_unidad= $('#nombre-unidad-modal').val();
		var descripcion_unidad= $('#descripcion-unidad-modal').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();

		var datos =
		{
			"Eliminar-unidad-modal":"submit",
			"codigo-unidad-modal" : codigo_unidad,
			"usuario" : usuario,
			"session" : session
		};

		$.ajax(
		{
			data: datos,
       		url:   '../controlador/controlador-unidad.php',
       		type:  'post',
       		success:  function (response)
       		{
       			alertify.alert("Información!",response);
       			load_unidad(constante);
           		habilitarcampos_unidad();
           		limpiarcampos_unidad();
           		limpiaropciones_unidad();
      		},
      		error: function(response)
      		{
       			alertify.alert("Me equivoqué"+ response);
      		}
       	});
	}
	alertify.confirm('Información!','¿Desea desactivar la categoria?',
	function()
	{
		eliminar_unidad();
	},
	function()
	{

	});
});

$("#btn-cancelar-unidad").on('click',function()
{
	$(".modal-backdrop.fade.in").remove();
	$("#close_Unidad").click();
 });

function desahabilitarcampos_unidad()
{
	$('#nombre-unidad-modal').attr('disabled',true);
    $('#descripcion-unidad-modal').attr('disabled',true);

}

function habilitarcampos_unidad()
{
 	$('#nombre-unidad-modal').attr('disabled',false);
    $('#descripcion-unidad-modal').attr('disabled',false);
}

function limpiarcampos_unidad()
{
	$('#nombre-unidad-modal').val("");
	$('#descripcion-unidad-modal').val("");
}

function limpiaropciones_unidad()
{
	$('#Agregar-unidad-modal').attr('disabled',false);
	$('#Guardar-unidad-modal').attr('disabled',true);
	$('#Actualizar-unidad-modal').attr('disabled',true);
	$('#Eliminar-unidad-modal').attr('disabled',true);
}

function load_unidad(page)
{
	var FiltroUnidad = $("#FiltroUnidad").val();
	var parametros = {"accion":"cargar","page":page,"FiltroUnidad":FiltroUnidad};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_unidad_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".unidad_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}

////////////////////////////////////////////////////////////////////// ---------------> FINAL de Todo con respecto a UNIDAD

////////////////////////////////////////////////////////////////////// ---------------> INCIO de Todo con respecto a IVA
function validar_iva()
{
	var porcentaje_iva= $('#nombre-iva-modal').val();
	var descripcion_iva= $('#descripcion-iva-modal').val();

	var verdad=true;

	if (porcentaje_iva=="") 
	{
		alertify.alert("Error!","El porcentaje esta vacío", function(){});
		$('#nombre-iva-modal').focus();
		return false;
	}
	else if (porcentaje_iva.length<2 || porcentaje_iva.length>4) 
	{
		alertify.alert("Información!","Porcentaje no valido", function(){});
		$('#nombre-iva-modal').focus();
		return false;
	}
	else if (descripcion_iva=="") 
	{
		alertify.alert("Error!","La descripción esta vacía", function(){});
		$('#descripcion-iva-modal').focus();
		return false;
	}
	else if (descripcion_iva.length<5) 
	{
		alertify.alert("Información!","Debe dar una descripción valida", function(){});
		$('#descripcion-iva-modal').focus();
		return false;
	}
	else
	{
		return verdad;
	}
}

function solonumero_iva(e)
{
	key=e.keyCode || e.which;
	teclado= String.fromCharCode(key).toLowerCase();
	letras ="0123456789.";
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

$("#close_Iva").click(function()
{
	limpiarcampos_iva();
	habilitarcampos_iva();
	limpiaropciones_iva();
});

$('#nombre-iva-modal').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#nombre-iva-modal').val();
  	if(key === 13)
  	{
    	if(validar_iva())
      	{
      		$("#descripcion-iva-modal").focus();
      	}
    } 
});

$('#nombre-iva-modal').keypress(function(event)
{
	return solonumero_iva(event);
});

$('#descripcion-iva-modal').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#descripcion-iva-modal').val();
  	if(key === 13)
  	{
    	if(validar_iva())
      	{
      		$("#Agregar-iva-modal").click();
      	}
    } 
});

$('#descripcion-iva-modal').keypress(function(event)
{
	return sololetra(event);
});

$("#Agregar-iva-modal").click(function()
{
	var constante = 1;
	if (validar_iva())
	{
		var porcentaje_iva= $('#nombre-iva-modal').val();
		var descripcion_iva= $('#descripcion-iva-modal').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();

      	var datos = 
      	{
      		"Agregar-iva-modal":"submit",
      		"nombre-iva-modal" : porcentaje_iva,
     		"descripcion-iva-modal" : descripcion_iva,
     		"usuario" : usuario,
			"session" : session
     	};

      	$.ajax(
      	{
      		data: datos,
        	url:   '../controlador/controlador-iva.php',
        	type:  'post',
        	success:  function (response)
        	{
	        	alertify.alert("Información!",response);
	            limpiarcampos_iva();
	            load_iva(constante);
	            $("#central").removeClass();
	            $("#central").load('../vista/producto.php').fadeIn('slow');
	            var bien = '<h5></h5>'
	            $('#noexiste').html(bien);
	            $(".modal-backdrop.fade.in").remove();
	        }, 
	        error: function(response)
	        {
	        	alertify.alert("Información","Me equivoqué"+ response);
	        }
	    }); 
	}
});

function agregar_iva(codigo_iva)
{
	var porcentaje_iva= $('#nombre-iva-modal').val();
	var descripcion_iva= $('#descripcion-iva-modal').val();
	var codigo = codigo_iva;
	var datos =
	{
		"Consultar":"submit",
		"codigo-tabla": codigo
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-iva.php',
		type: 'post',
		success: function(response)
		{
			console.log(codigo);
			try
			{
				var mostrar= JSON.parse(response);
				if (mostrar.data.length!=0)
				{
					alertify.alert("Información","<h5>Correcto al consultar</h5>");
					for(var i in mostrar.data)
					{
						$('#codigo-iva-modal').val(mostrar.data[i].id_iva);
						$('#nombre-iva-modal').val(mostrar.data[i].iva);
						$('#descripcion-iva-modal').val(mostrar.data[i].descripcion);
					}
					desahabilitarcampos_iva();
					$('#Agregar-iva-modal').attr('disabled',true);
					$('#Actualizar-iva-modal').attr('disabled',false);
					$('#Eliminar-iva-modal').attr('disabled',false);
				}
			}
			catch(Exception)
			{
				alertify.alert("Información","<h5>No se encuentra el iva</h5>");
			}
		},
		error: function(response)
		{
			alertify.alert("Me equivoqué"+ response);
		}
	});
}

function activar_iva(codigo_iva)
{
	var usuario = $("#usuariohidden").val();
	var FiltroIva = $("#FiltroIva").val();
	var constante = 1;
	var parametros = {"accion":"activar","page":constante,"codigo_iva":codigo_iva,"FiltroIva":FiltroIva, "usuario":usuario};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_iva_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".iva_div").html(data).fadeIn('slow');
			$(".modal-backdrop.fade.in").remove();
			//$("#loader").html("");
		}
	})
}

$("#Actualizar-iva-modal").click(function()
{
	$('#nombre-iva-modal').attr('disabled',false);
    $('#descripcion-iva-modal').attr('disabled',false);
	$('#Actualizar-iva-modal').attr('disabled',true);
	$('#Eliminar-iva-modal').attr('disabled',true);
	$('#Guardar-iva-modal').attr('disabled',false);
});

$("#Guardar-iva-modal").click(function()
{
	var constante = 1;
	if (validar_iva())
	{
		function guardar_iva()
		{
			var codigo_iva= $('#codigo-iva-modal').val();
			var porcentaje_iva= $('#nombre-iva-modal').val();
			var descripcion_iva= $('#descripcion-iva-modal').val();
			var usuario = $("#usuariohidden").val();
			var session = $("#sessionhidden").val();

			var datos= 
			{
				"Guardar-iva-modal": "submit",
				"codigo-iva-modal": codigo_iva,
				"nombre-iva-modal": porcentaje_iva,
				"descripcion-iva-modal": descripcion_iva,
				"usuario" : usuario,
				"session" : session
			};

			$.ajax(
			{
				data: datos,
				url: '../controlador/controlador-iva.php',
				type: 'post',
				success: function(response)
				{
					alertify.alert("Información!",response);
					load_iva(constante);
				},
				error: function(response)
				{
					alertify.alert("Me equivoqué"+ response);
				}
			});
		}
		alertify.confirm('Información!','¿Desea guardar los cambios?',
		function()
		{
			guardar_iva();
			habilitarcampos_iva();
			limpiarcampos_iva();
			limpiaropciones_iva();
		},
		function()
		{

		});
	}
});

$("#Eliminar-iva-modal").on('click',function()
{
	var constante = 1;
	function eliminar_iva()
	{
		var codigo_iva= $('#codigo-iva-modal').val();
		var porcentaje_iva= $('#nombre-iva-modal').val();
		var descripcion_iva= $('#descripcion-iva-modal').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();

		var datos =
		{
			"Eliminar-iva-modal":"submit",
			"codigo-iva-modal" : codigo_iva,
			"usuario" : usuario,
			"session" : session
		};

		$.ajax(
		{
			data: datos,
       		url:   '../controlador/controlador-iva.php',
       		type:  'post',
       		success:  function (response)
       		{
       			alertify.alert("Información!",response);
       			load_iva(constante);
           		habilitarcampos_iva();
           		limpiarcampos_iva();
           		limpiaropciones_iva();
      		},
      		error: function(response)
      		{
       			alert("Me equivoqué"+ response);
      		}
       	});
	}
	alertify.confirm('Información!','¿Desea desactivar la categoria?',
	function()
	{
		eliminar_iva();
	},
	function()
	{

	});
});

$("#btn-cancelar-iva").click(function()
{
	$("#close_Iva").click();
	limpiarcampos_iva();
	habilitarcampos_iva();
	limpiaropciones_iva();
 });

function desahabilitarcampos_iva()
{
	$('#nombre-iva-modal').attr('disabled',true);
    $('#descripcion-iva-modal').attr('disabled',true);

}

function habilitarcampos_iva()
{
 	$('#nombre-iva-modal').attr('disabled',false);
    $('#descripcion-iva-modal').attr('disabled',false);
}

function limpiarcampos_iva()
{
	$('#nombre-iva-modal').val("");
	$('#descripcion-iva-modal').val("");
}

function limpiaropciones_iva()
{
	$('#Agregar-iva-modal').attr('disabled',false);
	$('#Guardar-iva-modal').attr('disabled',true);
	$('#Actualizar-iva-modal').attr('disabled',true);
	$('#Eliminar-iva-modal').attr('disabled',true);
}

function load_iva(page)
{
	var FiltroIva = $("#FiltroIva").val();
	var parametros = {"accion":"cargar","page":page,"FiltroIva":FiltroIva};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_iva_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".iva_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	})
}
////////////////////////////////////////////////////////////////////// ---------------> FINAL de Todo con respecto a IVA

////////////////////////////////////////////////////////////////////// ---------------> INICIO de Todo con respecto a PRODUCTO
function validado()
{
	var nombre= $('#nombre-producto').val();
	var categoria= $('#nombre-categoria').val();
	var descripcion= $('#descripcion-producto').val();
	var unidad= $('#nombre-unidad').val();
	var stock_minimo= Number($('#cantidad-minima-producto').val());
	var stock_maximo= Number($('#cantidad-maxima-producto').val());
	//var precio= $('#precio-producto').val();
	var gravado= $('#gravado').val();

	var verdad=true;

	if (nombre=="") 
	{
		alertify.alert("Error!","El Nombre esta vacío", function(){});
		$('#nombre-producto').focus();
		return false;
	}
	else if (nombre.length<=2 || nombre.length>45) 
	{
		alertify.alert("Información!","Nombre no valido", function(){});
		$('#nombre-producto').focus();
		return false;
	}
	else if (categoria==0) 
	{
		alertify.alert("Error!","No ha seleccionado una Categoría");
		$('#nombre-categoria').focus();
		return false;
	}
	else if (descripcion=="") 
	{
		alertify.alert("Error!","Descripción esta Vacía");
		$('#descripcion-producto').focus();
		return false;
	}
	else if (descripcion.length<5)
	{
		alertify.alert("Información!","La Descripción no es valida");
		$('#descripcion-producto').focus();
		return false;
	}
	else if (unidad==0) 
	{
		alertify.alert("Error!","No ha seleccionado una Unidad");
		$('#nombre-unidad').focus();
		return false;
	}
	else if (stock_minimo=="") 
	{
		alertify.alert("Error!","Cantidad Mínima está Vacía");
		$('#cantidad-minima-producto').focus();
		return false;
	}
	else if (stock_minimo==0) 
	{
		alertify.alert("Información!","La Cantidad Mínima no puede ser 0");
		$('#cantidad-minima-producto').focus();
		return false;
	}
	else if (stock_maximo=="") 
	{
		alertify.alert("Error!","Cantidad Máxima está Vacía");
		$('#cantidad-maxima-producto').focus();
		return false;
	}
	else if (stock_maximo==0) 
	{
		alertify.alert("Información!","La Cantidad Máxima no puede ser 0");
		$('#cantidad-maxima-producto').focus();
		return false;
	}
	else if (stock_minimo >= stock_maximo) 
	{
		alertify.alert("Error","La Cantidad Mínima no puede ser mayor o igual a la Cantidad Máxima");
		$('#cantidad-minima-producto').focus();
	}
	else if (stock_maximo <= stock_minimo) 
	{
		alertify.alert("Error","La Cantidad Máxima no puede ser menor a la Cantidad Mínima");
		$('#cantidad-maxima-producto').focus();
	}
	/*else if (precio=="") 
	{
		alertify.error("Error","Precio esta Vacío");
		$('#precio-producto').focus();
		return false;
	}
	else if (precio==0) 
	{
		alertify.alert("Información!","El Precio no puede ser 0");
		$('#precio-producto').focus();
		return false;
	}*/
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

	teclado_especial= false;

	for(var i in especiales)
	{
		if (key==especiales[i]) 
		{
			teclado_especial= true;
			break;
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

$('#nombre-producto').keypress(function(event)
{
	return sololetra(event);
});

$('#descripcion-producto').keypress(function(event)
{
	return sololetra(event);
});

$('#cantidad-minima-producto').keypress(function(event)
{
	return solonumero(event);
});

$('#cantidad-maxima-producto').keypress(function(event)
{
	return solonumero(event);
});

/*$('#precio-producto').keypress(function(event)
{
	return solonumero(event);
});*/

$('#nombre-producto').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#nombre-producto').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$("#nombre-categoria").focus();
      	}
    } 
});

$('#nombre-categoria').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#nombre-categoria').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
			$("#descripcion-producto").focus();
     	}
    } 
});

/*DIRECCION KEYUP*/
$('#descripcion-producto').on('keyup', function()
{
	if ($("#descripcion-producto").val()!="")
	{
		$("#aviso2").show();
	  	var direccionup= $('#descripcion-producto').val();

	    if (direccionup.length<5)
	    {
	    	text='<div class="alert alert-danger" role="alert">La descripción debe ser más especifica</div>';
	    } 
	    else 
	    {
	    	text= '';
	    	$("#aviso1").fadeOut(10);
	    }
	    $("#descripcion-aviso").html(text);
	    $("#descripcion-aviso").show();
	    $("#descripcion-aviso").delay(4000).fadeOut(200);
	    $("#aviso1").delay(4000).fadeOut(200);
 	} 
});

$('#descripcion-producto').keypress(function(event)
{
	key=event.keyCode || event.which;
	var veri=$('#descripcion-producto').val();
	if(key === 13)
	{
	    if(validado())
	    {
	      	$("#nombre-unidad").focus();
	    }
	} 
});

$('#nombre-unidad').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#nombre-unidad').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$("#cantidad-minima-producto").focus();
      	}
    } 
});

$('#cantidad-minima-producto').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#cantidad-minima-producto').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$("#cantidad-maxima-producto").focus();
      	}
    }
});

$('#cantidad-maxima-producto').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#cantidad-maxima-producto').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$("#precio-producto").focus();
      	}
    }
});

/*$('#precio-producto').keypress(function(event)
{
  	key=event.keyCode || event.which;
  	var veri=$('#precio-producto').val();
  	if(key === 13)
  	{
    	if(validado())
      	{
      		$('#nombre-iva').focus();
      	}
    } 
});*/


$("#Cerrar-modal-tabla").click(function()
{
	$("#close_modal").click();
 });

/*function eliminar_inventario(id_tmp,page)
{
	var idn=id_tmp.toString();
	var parametros = {"accion":"eliminar","page":page,"id":idn};
   	alertify.confirm("Eliminar","Desea eliminar el proveedor?"
    ,function()
    {
    	$.ajax(
    	{
    		type: "POST",
    		url: "../listas/listar_inventario_producto_paginado.php",
    		data: parametros,
    		success: function(datos)
    		{
    			eliminar_inventario_producto(id_tmp);
				$("#tabla_inventario").html(datos);
				if(tablainventario_2.rows.length===1)
				{
					$("#Agregar").attr('disabled',true);
				}
			}
    	});
    },
    function()
    {
    	alertify.alert("Información!","Proceso cancelado");
    });
}*/

function eliminar_proveedor(id_tmp,page)
{
	var idn=id_tmp.toString();
	var parametros = {"accion":"eliminar","page":page,"id":idn};
   	alertify.confirm("Eliminar","Desea eliminar el proveedor?"
    ,function()
    {
    	$.ajax(
    	{
    		type: "POST",
    		url: "../listas/listar_proveedor_producto_paginado.php",
    		data: parametros,
    		success: function(datos)
    		{
    			eliminar_proveedor_producto(id_tmp);
				$("#tabla_proveedor").html(datos);	
				if(tablaproveedor_2.rows.length===1)
				{
					$("#Agregar").attr('disabled',true);
				}
			}
    	});
    },
    function()
    {
    	alertify.alert("Información!","Proceso cancelado");
    });
}

$('#Agregar').on('click',function()
{
	if (validado())
	{
		var codigo= $('#codigo-producto').val();
		var nombre= $('#nombre-producto').val();
		var categoria= $('#nombre-categoria').val();
		var descripcion= $('#descripcion-producto').val();
		var unidad= $('#nombre-unidad').val();
		var stock_minimo= $('#cantidad-minima-producto').val();
		var stock_maximo= $('#cantidad-maxima-producto').val();
		//var precio= $('#precio-producto').val();
		var gravado = $("#gravado").val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();

		var datos= 
		{
			"Agregar":"submit",
			"codigo-producto" : codigo,
			"nombre-producto" : nombre,
			"nombre-categoria" : categoria,
			"descripcion-producto" : descripcion,
			"nombre-unidad" : unidad,
			"cantidad-minima-producto" : stock_minimo,
			"cantidad-maxima-producto" : stock_maximo,
			//"precio-producto" : precio,
			"producto-gravado" : gravado,
			"usuario" : usuario,
			"session" : session			
		};

		$.ajax(
		{
			data: datos,
			url: '../controlador/controlador-producto.php',
			type: 'post',
			success: function(response)
			{
				alertify.alert("Información!", response);
				//inventarioproducto();
				provedorproducto();
			},
			error: function(response)
			{
				alertify.alert("Me equivoqué"+ response);
			}
		});
	}
});

function provedorproducto(page)
{
	var codigo_producto= $('#codigo-producto').val();
	var parametros = {"accion":"registrar","page":page,"codigo_producto":codigo_producto};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_proveedor_producto_paginado.php',
		type: "POST",
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_proveedor").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}

/*function inventarioproducto(page)
{
	var codigo_producto = $('#codigo-producto').val();
	var parametros = {"accion":"registrar","page":page,"codigo_producto":codigo_producto};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_inventario_producto_paginado.php',
		type: "POST",
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_inventario").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}*/

/*function reactivar_inventario_producto(idinventario)
{
	var codigo = $('#codigo-producto').val();
	var datos=
	{
		"Reactivar_Inventario":"submit",
		"codigo_inventario": idinventario,
		"codigo_producto": codigo
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-inventario_producto.php',
		type: 'post',
		success: function(response)
		{
			
		},
		error: function(response)
		{
			alertify.alert("Me equivoqué"+ response);
		}
	});
}*/

function reactivar_proveedor_producto(idproveedor)
{
	var codigo = $('#codigo-producto').val();
	var datos=
	{
		"Reactivar_Proveedor":"submit",
		"codigo_proveedor": idproveedor,
		"codigo_producto": codigo
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-proveedor_producto.php',
		type: 'post',
		success: function(response)
		{
			
		},
		error: function(response)
		{
			alertify.alert("Me equivoqué"+ response);
		}
	});
}

/*function eliminar_inventario_producto(id_tmp)
{
	var codigo= $('#codigo-producto').val();
	var codigoinventario = $('#codigo_'+id_tmp).val();

	var datos=
	{
		"Eliminar-inventario-producto":"submit",
		"codigo-producto": codigo,
		"codigo-inventario": codigoinventario
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-producto.php',
		type: 'post',
		success: function(response)
		{
			
		},
		error: function(response)
		{
			alertify.alert("Me equivoqué"+ response);
		}
	});
}*/

function eliminar_proveedor_producto(id_tmp)
{
	var codigo= $('#codigo-producto').val();
	var codigoproveedor = $('#codigo_'+id_tmp).val();

	var datos=
	{
		"Eliminar-proveedor-producto":"submit",
		"codigo-proveedor": codigoproveedor,
		"codigo-producto": codigo
	};

	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-producto.php',
		type: 'post',
		success: function(response)
		{
			
		},
		error: function(response)
		{
			alertify.alert("Me equivoqué"+ response);
		}
	});
}

function agregado(codigo_producto)
{
	var constante = 1;
	var codigo = codigo_producto;
	var datos=
	{
		"Consultar":"submit",
		"codigo-tabla": codigo_producto
	};
	
	$.ajax(
	{
		data: datos,
		url: '../controlador/controlador-producto.php',
		type: 'post',
		success: function(response)
		{
			try
			{
				var mostrar=JSON.parse(response);
          		if (mostrar.data.length!=0)
          		{
          			alertify.alert("Información!","<h5>Correcto al consultar</h5>");
          			for(var i in mostrar.data)
          			{
          				$('#codigo-producto').val(mostrar.data[i].codigo_producto);
          				$('#nombre-producto').val(mostrar.data[i].nombre);
          				$('#descripcion-producto').val(mostrar.data[i].descripcion);
						$('#nombre-categoria').val(mostrar.data[i].tipo_categoria)
						$('#cantidad-minima-producto').val(mostrar.data[i].cantidad_minima);
						$('#cantidad-maxima-producto').val(mostrar.data[i].cantidad_maxima);
						$('#nombre-unidad').val(mostrar.data[i].tipo_unidad);
						//$('#precio-producto').val(mostrar.data[i].precio);
						$('#gravado').val(mostrar.data[i].gravado);
          			}
          			desahabilitarcampos();
          			//listar_tabla_inventario_producto(constante,codigo);
          			listar_tabla_proveedor_producto(constante,codigo);
          			$('#Agregar').attr('disabled',true);
					$('#Actualizar').attr('disabled',false);
					$('#Eliminar').attr('disabled',false);
					//$("#close_modal").click();
				}
			}
			catch(Exception)
			{
				alertify.alert("Información!","<h5>No se encuentra el producto</h5>");
			}
		},
		error: function(response)
		{
			alertify.alert("Me equivoqué"+ response);
		}
	});
}

function activar(codigo_producto)
{
	var usuario = $("#usuariohidden").val();
	var FiltroProducto = $("#FiltroProducto").val();
	var constante = 1;
	var parametros = {"accion":"activar","page":constante,"codigo_producto":codigo_producto,"FiltroProducto":FiltroProducto, "usuario":usuario};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_producto_paginado.php',
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

$("#Actualizar").on('click',function()
{
	$('#Actualizar').attr('disabled',true);
	$('#Eliminar').attr('disabled',true);
	$('#Guardar').attr('disabled',false);
	$('#nombre-producto').attr('disabled',false);
    $('#nombre-categoria').attr('disabled',false);
    $('#descripcion-producto').attr('disabled',false);
    $('#nombre-unidad').attr('disabled',false);
  	$('#cantidad-minima-producto').attr('disabled',false);
  	$('#cantidad-maxima-producto').attr('disabled',false);
    //$('#precio-producto').attr('disabled',false);
    $('#gravado').attr('disabled',false);
    $('#btn-agregar-inventario').attr('disabled',false);
    $('#btn-agregar-proveedor').attr('disabled',false);
});

$("#Guardar").on('click',function()
{
	if (validado())
	{
		function guardar()
		{
			var codigo= $('#codigo-producto').val();
			var nombre= $('#nombre-producto').val();
			var categoria= $('#nombre-categoria').val();
			var descripcion= $('#descripcion-producto').val();
			var unidad= $('#nombre-unidad').val();
			var stock_minimo= $('#cantidad-minima-producto').val();
			var stock_maximo= $('#cantidad-maxima-producto').val();
			//var precio= $('#precio-producto').val();
			var gravado= $('#gravado').val();
			var usuario = $("#usuariohidden").val();
			var session = $("#sessionhidden").val();

			var datos = 
			{
				"Guardar":"submit",
				"codigo-producto" : codigo,
				"nombre-producto" : nombre,
				"nombre-categoria" : categoria,
				"descripcion-producto" : descripcion,
				"nombre-unidad" : unidad,
				"cantidad-minima-producto" : stock_minimo,
				"cantidad-maxima-producto" : stock_maximo,
				//"precio-producto" : precio,
				"producto-gravado" : gravado,
				"usuario" : usuario,
				"session" : session
			};

			$.ajax(
			{
		 		data: datos,
          		url:   '../controlador/controlador-producto.php',
          		type:  'post',
          		success: function(response) 
          		{
	            	alertify.alert('Actualizacion Exitosa','Producto actualizado', function()
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
		var codigo= $('#codigo-producto').val();
		var usuario = $("#usuariohidden").val();
		var session = $("#sessionhidden").val();

		var datos = 
		{
			"Eliminar":"submit",
			"codigo-producto" : codigo,
			"usuario" : usuario,
			"session" : session
		};

		$.ajax(
		{
		  	data: datos,
          	url:   '../controlador/controlador-producto.php',
          	type:  'post',
          	success: function (response)
          	{
	            alertify.alert('Inactivacion Exitosa','Producto Inactivado', function()
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
	alertify.confirm('Eliminar','¿Desea desactivar el producto?',
	function()
	{
		eliminar();
	},
	function()
	{

	});
});

/*function verificarcodigo_inventario(idinventario)
{
	var constante = 1;
	var tableReg = document.getElementById('tablainventario_2');
	var codigocom= idinventario;
	var codigop= "";
  	try
  	{
  		for(var i = 1; i < tableReg.rows.length; i++)
  		{
  			var codigop= (tablainventario_2.rows[i].cells[0].innerHTML);
  			if (codigop==codigocom) 
  			{
    			alertify.alert("Error","Ya existe este Inventario en la tabla, por favor verifique");
    			return false;
  			}
		} 
		agregar_inventario_producto(constante,idinventario);
  		return false;
  	}
  	catch(Exception)
  	{
    	alert(Exception);
  	}
}*/

function verificarcodigo_proveedor(idproveedor)
{
	var constante = 1;
	var tableReg = document.getElementById('tablaproveedor_2');
	var codigocom= idproveedor;
	var codigop= "";
  	try
  	{
  		for(var i = 1; i < tableReg.rows.length; i++)
  		{
  			var codigop= (tablaproveedor_2.rows[i].cells[0].innerHTML);
  			if (codigop==codigocom) 
  			{
    			alertify.alert("Error","Ya existe este Proveedor en la tabla, por favor verifique");
    			return false;
  			}
		}
		agregar_proveedor_producto(constante,idproveedor);
  		return false;
  	}
  	catch(Exception)
  	{
    	alert(Exception);
  	}
}

/*$("#btn-agregar-inventario").click(function()
{
	$("#btn-agregar-inventario").hide();
	$("#btn-salir-inventario").show();
	$("#tabla-agregar-inventario").slideToggle();
	alertify.alert("Información!","Agregue el inventario donde se almacenará el producto");
});

$("#btn-salir-inventario").on('click',function()
{
	$("#btn-salir-inventario").hide();
	$("#btn-agregar-inventario").show();
	$("#tabla-agregar-inventario").slideToggle();
});*/

$("#btn-agregar-proveedor").click(function()
{
	$("#btn-agregar-proveedor").hide();
	$("#btn-salir-proveedor").show();
	$("#tabla-agregar-proveedor").slideToggle();
	alertify.alert("Información!","Agregue quien le provee este Producto");
});

$("#btn-salir-proveedor").on('click',function()
{
	$("#btn-salir-proveedor").hide();
	$("#btn-agregar-proveedor").show();
	$("#tabla-agregar-proveedor").slideToggle();
});

$("#btn-cancelar").on('click',function()
{
	alertify.confirm('Cancelar','Desea cancelar el proceso?',
	function()
	{
	    window.location.reload(true);
	},
	function()
	{
		
	});
});

function desahabilitarcampos()
{
	$('#btn_prod_modal').attr('disabled',true); /*readonly*/
	$('#btn_cate_modal').attr('disabled',true); 
	$('#btn_unid_modal').attr('disabled',true); 
	$('#btn_iva_modal').attr('disabled',true); 
    $('#nombre-producto').attr('disabled',true); 
    $('#nombre-categoria').attr('disabled',true);
    $('#descripcion-producto').attr('disabled',true);
    $('#nombre-unidad').attr('disabled',true);
    $('#cantidad-minima-producto').attr('disabled',true);
    $('#cantidad-maxima-producto').attr('disabled',true);
    //$('#precio-producto').attr('disabled',true);
    $('#gravado').attr('disabled',true);
    $('#btn-agregar-inventario').attr('disabled',true);
    $('#btn-agregar-proveedor').attr('disabled',true);
}

function habilitarcampos()
{
	$('#nombre-producto').attr('disabled',false); /*readonly*/
	$('#nombre-categoria').attr('disabled',false);
    $('#descripcion-producto').attr('disabled',false);
    $('#nombre-unidad').attr('disabled',false);
    $('#cantidad-minima-producto').attr('disabled',false);
    //$('#precio-producto').attr('disabled',false); 
    $('#gravado').attr('disabled',false);
    $('#btn-agregar-inventario').attr('disabled',false);
    $('#btn-agregar-proveedor').attr('disabled',false);
}

function limpiarcampos()
{
	$('#nombre-producto').val("");
	$('#nombre-categoria').val(0);
	$('#descripcion-producto').val("");
	$('#unidad-producto').val("");
	$('#nombre-unidad').val(0);
	$('#cantidad-minima-producto').val("");
	//$('#precio-producto').val("");
	$('#gravado').val("INACTIVO");
}

function limpiaropciones()
{
	$('#Agregar').attr('disabled',false);
	$('#Guardar').attr('disabled',true);
	$('#Actualizar').attr('disabled',true);
	$('#Eliminar').attr('disabled',true);
}
////////////////////////////////////////////////////////////////////// ---------------> FINAL de Todo con respecto a PRODUCTO

function load_producto(page)
{
	var FiltroProducto = $("#FiltroProducto").val();
	var parametros = {"accion":"cargar","page":page,"FiltroProducto":FiltroProducto};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_producto_paginado.php',
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
	});
}

/*function load_inventario(page)
{
	var FiltroInventario = $("#FiltroInventario").val();
	var parametros = {"accion":"cargar","page":page,"FiltroInventario":FiltroInventario};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_inventarios_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".inventario_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}

function load_inventario_producto(page,idinventario)
{
	var parametros = {"accion":"cargar","page":page,"id_inventario":idinventario};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_inventario_producto_paginado.php',
		type: "POST",
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_inventario").html(data).fadeIn('slow');
			if(tablainventario_2.rows.length>1)
			{
				$("#Agregar").attr('disabled',false);
			}
			//$("#loader").html("");
		}
	});
}

function agregar_inventario_producto(page,idinventario)
{
	var FiltroInventarioProducto = $("#FiltroInventarioProducto").val();
	var parametros = {"accion":"agregar","page":page,"id_inventario":idinventario,"FiltroInventarioProducto":FiltroInventarioProducto};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_inventario_producto_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_inventario").html(data).fadeIn('slow');
			if(tablainventario_2.rows.length>1)
			{
				$("#Agregar").attr('disabled',false);
				reactivar_inventario_producto(idinventario);
			}
			//$("#loader").html("");
		}
	});
}*/

function load_proveedor_producto(page,idproveedor)
{
	var parametros = {"accion":"cargar","page":page,"id_proveedor":idproveedor};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_proveedor_producto_paginado.php',
		type: "POST",
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_proveedor").html(data).fadeIn('slow');
			if(tablaproveedor_2.rows.length>1)
			{
				$("#Agregar").attr('disabled',false);
			}
			//$("#loader").html("");
		}
	});
}

function agregar_proveedor_producto(page,idproveedor)
{
	var FiltroProveedorProducto = $("#FiltroProveedorProducto").val();
	var parametros = {"accion":"agregar","page":page,"id_proveedor":idproveedor,"FiltroProveedorProducto":FiltroProveedorProducto};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_proveedor_producto_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$("#tabla_proveedor").html(data).fadeIn('slow');
			if(tablaproveedor_2.rows.length>1)
			{
				$("#Agregar").attr('disabled',false);
				reactivar_proveedor_producto(idproveedor);
			}
			//$("#loader").html("");
		}
	});
}

/*function listar_tabla_inventario_producto(page,codigo)
{
	var parametros = {"accion":"listar","page":page,"id_producto":codigo};
  	$.ajax(
  	{
   		data: parametros,
    	url:'../listas/listar_inventario_producto_paginado.php',
    	type: 'post',
    	success: function(data)
    	{
      		$("#tabla_inventario").html(data).fadeIn('slow');
    	},
    	error: function(data)
    	{
      	alert("Me equivoqué"+ response);
    	}
 	});
}*/

function listar_tabla_proveedor_producto(page,codigo)
{
	var parametros = {"accion":"listar","page":page,"id_producto":codigo};
  	$.ajax(
  	{
   		data: parametros,
    	url:'../listas/listar_proveedor_producto_paginado.php',
    	type: 'post',
    	success: function(data)
    	{
      		$("#tabla_proveedor").html(data).fadeIn('slow');
    	},
    	error: function(data)
    	{
      	alert("Me equivoqué"+ response);
    	}
 	});
}

function load_proveedor(page)
{
	var FiltroProveedor = $("#FiltroProveedor").val();
	var parametros = {"accion":"cargar","page":page,"FiltroProveedor":FiltroProveedor};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_proveedores_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".proveedor_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}
