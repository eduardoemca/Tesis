$(document).ready(function()
{
	$("#btn-imprimir-clientes").on('click',function()
    {
       alertify.confirm("Confirmación","Desea generar el reporte?",function()
       {
       	$("#reporte_inventario").click();
       },
       function()
       {
       	
       });
    });

	$("#tabla-agregar").hide();
	$("#btn-agregar").hide();
	$("#btn-salir").show();
	
  	load_inventario(1);
  	//load_inventarios(1);
});

$("#Cerrar-modal-tabla").click(function()
{
	$("#close_modal").click();
});

function validado()
{
	var codigo= $('#codigo-inventario').val();
	var descripcion= $('#descripcion-inventario').val();

	var verdad=true;

	if (codigo=="")
	{
		alertify.error("El campo Código esta Vacío");
		$('#codigo-inventario').focus();
		return false;
	}
	else if (codigo.length<=5 || codigo.length>8)
	{
		alertify.error("Código no valido");
		$('#codigo-inventario').focus();
		return false;
	}
	else if (descripcion=="")
	{
		alertify.error("El campo Descripción esta Vacío");
		$('#descripcion-inventario').focus();
		return false;
	}
	else if (descripcion.length<=3 || descripcion.length>15)
	{
		alertify.error("Descripción no valida");
		$('#descripcion-inventario').focus();
		return false;
	}
	else
	{
		return verdad;
	}
}

function solonumero(e)
{
  var key=e.keyCode || e.which;
	teclado= String.fromCharCode(key).toLowerCase();

	letras ="0123456789";

	especiales ="8-37-38-46-13";

	teclado_especial=false;

  for(var i in especiales)
  {
   	if (key== especiales[i])
   	{
   		teclado_especial=true; break;
   	}
  }
  if(letras.indexOf(teclado)==-1 && !teclado_especial)
  {
   	return false;
  }
};

$('#descripcion-inventario').keypress(function(event)
{
	key=event.keyCode || event.which;
  	var veri=$('#descripcion-inventario').val();
  	if(key === 13)
  	{
  		if(validado())
    	{
      		
    	}
  	} 
});

function load_inventario(page)
{
	var FiltroInventario = $("#FiltroInventario").val();
	var parametros = {"accion":"cargar","page":page,"FiltroInventario":FiltroInventario};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_inventario_paginado.php',
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
	})
}

$('#Agregar').on('click',function()
{
	if (validado()) 
	{
		var codigo= $('#codigo-inventario').val();
		var descripcion= $('#descripcion-inventario').val();

		var datos =
		{
			"Agregar":"submit",
			"codigo-inventario" : codigo,
			"descripcion-inventario" : descripcion
		};

		$.ajax(
		{
			data: datos,
       		url:   '../controlador/controlador-inventario.php',
        	type:  'post',
        	success:  function (response)
        	{
            	alertify.success(response);
            	limpiarcampos();
            	relogea();
        	}, 
			error: function(response)
			{
				alert("Me equivoqué"+ response);
			}
 		});	
	}
});

function agregar(codigo_tabla)
{
	var datos =
	{
		"Consultar-tabla":"submit",
		"codigo-tabla": codigo_tabla
	};

	$.ajax(
	{
		data: datos,
        url:   '../controlador/controlador-inventario.php',
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
          				$('#codigo-inventario').val(mostrar.data[i].id_inventario);
						$('#descripcion-inventario').val(mostrar.data[i].descripcion);
          			}
        			desahabilitarcampos();
        			$('#Agregar').attr('disabled',true);
					$('#Actualizar').attr('disabled',false);
					$('#Eliminar').attr('disabled',false);
					$("#close_modal").click();
        		}
        	}
        	catch(Exception)
        	{
        		alertify.error("<h5>No se encuentra el Inventario</h5>");
        	}
        },
        error: function(response)
        {
		    alert("Me equivoqué"+ response);
		}
	})
}

$("#Actualizar").on('click',function()
{
	$('#Actualizar').attr('disabled',true);
	$('#Eliminar').attr('disabled',true);
	$('#Guardar').attr('disabled',false);
   	$('#descripcion-inventario').attr('disabled',false);
});

$("#Guardar").on('click',function()
{
	if (validado())
	{
		function guardar()
		{
			var codigo= $('#codigo-inventario').val();
			var descripcion= $('#descripcion-inventario').val();

			var datos =
			{
				"Guardar":"submit",
				"codigo-inventario" : codigo,
				"descripcion-inventario" : descripcion
			};
			
			$.ajax(
			{
		 		data: datos,
         		url:   '../controlador/controlador-inventario.php',
          		type:  'post',
          		success:  function (response)
          		{
               		alert(response);
               		relogea();
         		}, 
         		error: function(response)
         		{
	       			alert("Me equivoqué"+ response);
	 			}
			});
		}
		var estatus= confirm("Quieres guardar cambios?");
		if (estatus==true)
		{
			guardar();
			habilitarcampos();
			limpiarcampos();
			limpiaropciones();
		}
	}
});

$("#Eliminar").on('click',function()
{
	function eliminar()
	{
		var codigo = $('#codigo-inventario').val();

		var datos =
		{
			"Eliminar":"submit",
			"codigo-inventario" : codigo
		};

		$.ajax(
		{
			data: datos,
        	url:   '../controlador/controlador-inventario.php',
       		type:  'post',
        	success: function (response)
        	{
            	alert(response);
            	habilitarcampos();
            	limpiarcampos();
            	limpiaropciones();
            	relogea();
        	},
        	error: function(response)
        	{
	       		alert("Me equivoqué"+ response);
	   		}
		});
	}
	var estatus= confirm("Quieres eliminar?");
	if (estatus==true)
	{
		eliminar();
	}
});

$("#btn-agregar").click(function()
{
	$("#btn-agregar").hide();
	$("#btn-salir").show();
	$("#tabla-agregar").slideToggle();
});

$("#btn-salir").on('click',function()
{
	$("#btn-salir").hide();
	$("#btn-agregar").show();
	$("#tabla-agregar").slideToggle();
});

/*function load_inventarios(page)
{
	var inventario = $('#nombre-inventario').val();
	var parametros = {"accion":"cargar","page":page,"inventario":inventario};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_inventario_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".inventarios_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	})
}

function validar_codigo(codigo_producto)
{
	var codigop= "";
  	var constante = 1;
  	var almacen = $("#nombre-inventario").val();
  	var stock_minimo = Number($("#stock_min_"+codigo_producto).val());
  	var cantidad_actual = Number($("#cant_actual_"+codigo_producto).val());
  	var cantidad = Number($("#cant_produc_"+codigo_producto).val());
  	var tableReg = document.getElementById('tablaalmacen_2');
  	var comparacion = cantidad_actual - cantidad;

  	if(almacen === '0')
	{
		alertify.alert("Alerta","Seleccione un Almacen");
		$('#nombre-inventario').focus();
		return false;
	}
	else if(cantidad === 0)
	{
		alertify.error('Cantidad no puede ser 0');
	    $("#cant_produc_"+codigo_producto).focus();
	    return false;
	}
	else
  	{
    	for(var i = 1; i < tableReg.rows.length; i++)
    	{
	    	var codigop = (tableReg.rows[i].cells[0].innerHTML);
	      	if(codigop === codigo_producto)
	      	{
        		if(cantidad > cantidad_actual)
        		{
          			alertify.alert("Alerta","Cantidad mayor al Stock actual");
          			return false;
        		}
        		else if(stock_minimo >= comparacion)
        		{
        			alertify.alert("Alerta","Llgo al limite del stock mínimo");
          			return false;
        		}
        		else
        		{
          			load_registrar_inventario(constante,codigo_producto,cantidad);
          			$("#cant_actual_"+codigo_producto).val(comparacion);
        		}
      		}
    	}
  	}
}

function load_registrar_inventario(page,codigo_producto,cantidad)
{
	var inventario = $('#nombre-inventario').val();
	var master = $('#codigo-master').val();
	var parametros = {"accion":"agregar","page":page,"inventario":inventario,"codigo_producto":codigo_producto,"cantidad_enviada":cantidad,"master":master};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_inventario_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".inventarios_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	})
}

function eliminar_producto(codigo_producto, page)
{
	var codigop= "";
	var inventario = $('#nombre-inventario').val();
	var master = $('#codigo-master').val();
	var cantidad_actual = Number($("#actual_"+codigo_producto).val());
	var stock_minimo = Number($("#stock_min_"+codigo_producto).val());
	var tableReg = document.getElementById('tabla_temporal');

	alertify.prompt("Eliminar","Ingrese la Cantidad",""
    ,function(evt, cantidad_eliminada)
    {
    	var comparacion = cantidad_actual - cantidad_eliminada;
    	if(isNaN(cantidad_eliminada))
      	{
        	alertify.error("El valor no es un Numero");
        	return false;
      	}
      	else if(cantidad_eliminada === '')
      	{
        	alertify.error('La cantidad no puede estar vacía');
        	evt.cancel = true;
        	return false;
      	}
      	else if(cantidad_eliminada === '0')
      	{ 
        	alertify.error('La cantidad no puede ser 0');
        	evt.cancel = true;
        	return false;
      	}
      	for(var i = 1; i < tableReg.rows.length; i++)
    	{
	    	var codigop = (tableReg.rows[i].cells[0].innerHTML);
	      	if(codigop === codigo_producto)
	      	{
        		if(cantidad_eliminada > cantidad_actual)
        		{
          			alertify.alert("Alerta","Cantidad mayor al Stock actual");
          			return false;
        		}
        		else if(stock_minimo >= comparacion)
        		{
        			alertify.alert("Alerta","Llgo al limite del stock mínimo");
          			return false;
        		}
        		else
        		{
          			var parametros = {"accion":"quitar","page":page,"inventario":inventario,"master":master,"codigo_producto":codigo_producto,"cantidad_eliminada":cantidad_eliminada};
					$.ajax(
					{
						url:'../listas/listar_inventario_paginado.php',
						data: parametros,
						beforeSend: function(objeto)
						{
							//$("#loader").html("<img src='loader.gif'>");
						},
						success:function(data)
						{
							$(".inventarios_div").html(data).fadeIn('slow');
							load(1);
						}
					});
        		}
      		}
    	}
    },function()
    {
     	alertify.error("Cancelado");
    });
}

$("#nombre-inventario").change(function()
{
	load_inventarios();
	$('#nombre-inventario').attr('disabled',true);
});

/*function registrar_almacen()
{
	$('#nombre-inventario').attr('disabled',false);
}*/

function habilitarcampos()
{
	$('#codigo-inventario').attr('disabled',false); 
    $('#descripcion-inventario').attr('disabled',false); /*readonly*/
}

function desahabilitarcampos()
{
	$('#codigo-inventario').attr('disabled',true); 
    $('#descripcion-inventario').attr('disabled',true); /*readonly*/
}

function limpiarcampos()
{
	var codigo= $('#codigo-inventario').val("");
	var descripcion= $('#descripcion-inventario').val("");
}

function limpiaropciones()
{
	$('#Agregar').attr('disabled',false);
	$('#Guardar').attr('disabled',true);
	$('#Actualizar').attr('disabled',true);
	$('#Eliminar').attr('disabled',true);
}