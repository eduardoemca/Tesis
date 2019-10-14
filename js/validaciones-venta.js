$(document).ready(function()
{
  restaurar_master();

  $("#tabla-agregar").hide();
	$("#btn-salir").hide();
 	$('#btn-agregar').attr('disabled',true);
 	$('#agregar-venta').attr('disabled',true);

  $("#Cerrar-modal-tabla").click(function()
  {
    $("#close_modal").click();
  });

  load_venta(1);
  load_ventas_registradas(1);
  load_cliente(1);
  load_iva(1);
});


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


$("#Actualizar-iva-modal").click(function()
{
  $('#nombre-iva-modal').attr('disabled',false);
    $('#descripcion-iva-modal').attr('disabled',false);
  $('#Actualizar-iva-modal').attr('disabled',true);
  $('#Eliminar-iva-modal').attr('disabled',true);
  $('#Guardar-iva-modal').attr('disabled',false);
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
  $('#Actualizar-iva-modal').attr('disabled',false);
  $('#Eliminar-iva-modal').attr('disabled',true);
}

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


//----------------------------- MODAL AGREGAR CLIENTE --------------------------------//

function validacionmodal()
{
	var cedula= $('#identificacion-cliente-modal').val();
  	var nombre= $('#nombre-cliente-modal').val();
  	var apellido= $('#apellido-cliente-modal').val();
  	var direccion= $('#direccion-cliente-modal').val();
  	var telefono= $('#telefono-cliente-modal').val();
  	var correo= $('#correo-cliente-modal').val();

  	expresion=/\w+@\w+\.+[a-z]/;
  	var verdad=true;

  	if (cedula=="")
  	{
    	alertify.error('Cedula esta vacio');
    	$('#identificacion-cliente-modal').focus();
    	return false;
  	}
  	else if (nombre=="") 
  	{
    	alertify.error('Nombre esta vacio');
    	$('#nombre-cliente-modal').focus();
    	return false;
  	}
  	else if (apellido=="") 
  	{
    	alertify.error('Apellido esta vacio');
    	$('#apellido-cliente-modal').focus();
    	return false;
  	}
	else if (direccion=="") 
	{
	    alertify.error('Direccion esta vacio');
	    $('#direccion-cliente-modal').focus();
	    return false;
	}
  	else if (correo=="") 
  	{
	    alertify.error('Correo esta vacio');
	    $('#correo-cliente-modal').focus();
	    return false;
  	}
  	else if (telefono=="")
  	{
	    alertify.error('Telefono esta vacio');
	    $('#telefono-cliente-modal').focus();
	    return false;
  	}
  	else if (cedula.length<=5 || cedula.length>8)
  	{
	    alertify.error('Identificacion no valido');
	    $('#identificacion-cliente-modal').focus();
	    return false;
  	}
  	else if (nombre.length<3 || nombre.length>15)
  	{
	    alertify.error('Nombre no valido');
	    $('#nombre-cliente-modal').focus();
	    return false;
  	}
  	else if (apellido.length<4 || apellido.length>15)
  	{
    	alertify.error('Apellido no valido');
    	$('#apellido-cliente-modal').focus();
    	return false;
  	}
  	else if (direccion.length<5)
  	{
	    alertify.error('Debe dar una direccion mas especifica');
	    $('#direccion-cliente').focus();
	    return false;
  	}
  	else if (telefono.length<11 || telefono.length>11)
  	{
    	alertify.error('El telefono no es valido');
    	$('#telefono-cliente-modal').focus();
    	return false;
  	}
  	else if (!expresion.test(correo))
  	{
    	alertify.error('Debe dar un email valido');
    	$('#correo-cliente-modal').focus();
    	return false;
  	}
  	else
  	{
    	return verdad;
  	}
}

$('#identificacion-cliente-modal').keypress(function(event)
{
  	return solonumero(event);
});

$('#telefono-cliente-modal').keypress(function(event)
{
  	return solonumero(event);
});

$('#nombre-cliente-modal').keypress(function(event)
{
  	return sololetra(event);
});

$('#apellido-cliente-modal').keypress(function(event)
{
  	return sololetra(event);
});

function limpiarcampos()
{
	$('#identificacion-cliente-modal').val("");
	$('#nombre-cliente-modal').val("");
	$('#apellido-cliente-modal').val("");
	$('#direccion-cliente-modal').val("");
	$('#telefono-cliente-modal').val("");
	$('#correo-cliente-modal').val("");
}

//----------------------------- MODAL AGREGAR CLIENTE --------------------------------//

$('#identificacion-cliente').keypress(function(event)
{
  key=event.keyCode || event.which;

  if(key === 13)
  {
    $('#Consultar').click();
    return false;
    console.log("Tecla ENTER");
  }
  return solonumero(event);
});

$("#Consultar").on('click',function()
{
  var constante = 1;
	var nacio= $('#nacio-cliente').val();
  var cedula= $('#identificacion-cliente').val();

  	if (cedula=="")
  	{
    	alertify.error('Cedula esta vacio');
    	$('#identificacion-cliente').focus();
    	return false;
  	}
  	else  if (cedula.length<7)
  	{
    	alertify.error('Cedula no valida');
    	$('#identificacion-cliente').focus();
    	return false;
  	}

  	var datos = 
  	{
  		"Consultar":"submit",
  		"cedula-cliente" : nacio+cedula
  	};

    $.ajax(
    {
      data: datos,
      url: '../controlador/controlador-cliente.php',
      type: 'post',
      success: function(respose)
      {
        try
        {
          var mostrar = JSON.parse(respose);
          if (mostrar.data.length!=0)
          {
            for(var i in mostrar.data)
            {
              $('#nombre-cliente').val(mostrar.data[i].nombre+" "+mostrar.data[i].apellido);
            }
            $('#nacio-cliente').attr('disabled',true);
            $('#identificacion-cliente').attr('disabled',true);
            $('#nombre-cliente').attr('disabled',true);
            $('#btn-agregar').attr('disabled',false);
            var bien = '<h5></h5>'
            $('#noexiste').html(bien);
            $('#Consultar').attr('disabled',true);
            load_venta_producto(constante);
          }
          $('#btn_iva_modal').attr('disabled',true);
        }
        catch(Exception)
        {
          alertify.alert("Cliente no encontrado","No se encuentra cliente");
          var error = '<button type="button" class="btn btn-danger nuevo" data-toggle="modal" data-target="#myModal">Agregar Cliente</button>'
          $('#noexiste').html(error);
        }
      },
      error: function(response)
      {
        alert("Me equivoqué"+ response);
      }
    });
});

function agregar(cedula)
{
  var constante = 1;

  var datos =
  {
    "Consultar-tabla":"submit",
    "cedula-tabla": cedula
  };

  $.ajax(
  {
    data: datos,
    url: '../controlador/controlador-cliente.php',
    type: 'POST',
    success: function(response)
    {
      try
      {
        var mostrar=JSON.parse(response);
        if (mostrar.data.length!=0)
        {
          alertify.alert("Información","Cliente encontrado");
          for(var i in mostrar.data)
          {
            var ci= mostrar.data[i].cedula;
            var res= ci.substring(0,1);
            var tan= ci.substring(1);
            $('#nacio-cliente').val(res);
            $('#identificacion-cliente').val(tan);
            $('#nombre-cliente').val(mostrar.data[i].nombre+" "+mostrar.data[i].apellido);
          }
          $("#Cerrar-modal-tabla").click();
          $(".modal-backdrop.fade.in").remove();
          $('#nacio-cliente').attr('disabled',true);
          $('#identificacion-cliente').attr('disabled',true);
          $('#nombre-cliente').attr('disabled',true);
          $('#btn-agregar').attr('disabled',false);
          var bien = '<h5></h5>'
          $('#noexiste').html(bien);
          $('#Consultar').attr('disabled',true);
          $('#btn_direc_modal').attr('disabled',true);
          load_venta_producto(constante);
        }
      }
      catch(Exception)
      {
        alertify.alert("Información","Cliente no encontrado");
      }
    }, 
    error: function(response)
    {
      alert("Me equivoqué"+ response);
    }
  });
}

function verificar_codigo(codigo_producto)
{
  var codigo= "";
  var constante = 1;
  var cantidad= Number($("#cant_produc_"+codigo_producto).val());
  var actidad_actual = Number($("#cant_actual_"+codigo_producto).val());
  var cantidad_minima = Number($("#stock_min_"+codigo_producto).val());
  var tableReg = document.getElementById('tabla_listar_productos_venta');
  var cantidad_nueva = actidad_actual-cantidad;
  if(cantidad=== 0)
  {
    alertify.error('No ha ingresado la cantidad a Vender');
    $("#cant_produc_"+codigo_producto).focus();
    return false;
  }
  else
  {
    agregar_producto_venta(constante,codigo_producto,cantidad);
  }
}

function eliminar_producto(id_tmp,page)
{
  var constante = 1;
  var idn=id_tmp.toString();
  var codigo_producto = $("#codi_compr"+id_tmp).val();
  var tableReg = document.getElementById('tabla_listar_productos_venta');
  alertify.prompt("Eliminar","Ingrese la Cantidad",""
  ,function(evt, cantidad_eliminada)
  {
    if(isNaN(cantidad_eliminada))
    {
      alertify.error("El valor no es un Numero");
      return false;
    }
    if(cantidad_eliminada === '')
    { 
      alertify.error('La cantidad no puede estar vacía');
      evt.cancel = true;
      return false;
    }
    if(cantidad_eliminada === '0')
    { 
      alertify.error('La cantidad no puede ser 0');
      evt.cancel = true;
      return false;
    }
    var parametros = {"accion":"quitar","page":page,"id_temp":idn,"cantidad_eliminada":cantidad_eliminada};
    $.ajax(
    {
      type: "POST",
      url: "../listas/listar_venta_producto_paginado.php",
      data: parametros,
      success: function(datos)
      {
        $(".factura_div").html(datos);
        load_venta_producto(constante);
        if(tabla_venta.rows.length===1)
        {
          $("#agregar-venta").attr('disabled',true);
        }
      }
    });
  },function()
  {
    alertify.error("Cancelado");
  });
}

function restaurar_master()
{
  var constante = 1;
  var parametros = {"accion":"restaurar","page":constante};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_venta_producto_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".factura_div").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  })
}

$("#agregar-venta").on('click',function(e)
{
  if(tabla_venta.rows.length<2)
  {
    alertify.error("La tabla esta vacia");
    return false;
  }

  var codigo_venta = $("#codigo-venta").val();
  var nacio = $("#nacio-cliente").val();
  var identificacion = $("#identificacion-cliente").val();

  var datos=
  {
    "Agregar":"submit",
    "codigo-venta" : codigo_venta,
    "identificacion-cliente" : nacio+identificacion
  }

  alertify.confirm("Confirmación","Desea realizar la venta?",
  function()
  {
    $.ajax(
    {
      data: datos,
      url:   '../controlador/controlador-venta.php',
      type:  'post',
      success: function(response)
      {
        alertify.alert('Correcto',response,function()
        {
          calculardetalle();
          //mostrardetalle();
        });
      },
      error: function(response)
      {
        alert("Me equivoqué 2"+ response);
      }
    });
  },
  function()
  {
    alertify.error("Cancelado");
  });
});

function calculardetalle(page)
{
  var codigo_venta = $("#codigo-venta").val();
  var parametros = {"accion":"registrar","page":page,"codigo_venta":codigo_venta};

  $.ajax(
  {
    url:'../listas/listar_venta_producto_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      alertify.alert('Registrando Detalle',function()
      {
        mostrardetalle();
      });
      $(".factura_div").html(data).fadeIn('slow');
    }
  })
}


function mostrardetalle()
{
  var codigo=$("#codigo-venta").val();
  var nacio=$("#nacio-cliente").val();
  var identificacion=$("#identificacion-cliente").val();
  var icompleta=nacio+identificacion;

  var datos = 
  {
    "codigo_venta" : codigo,
    "identificacion_cliente": nacio+identificacion
  };

  $.ajax(
  {
    data: datos,
    url:'../reporte/pdfventa.php',
    type:'post',
    success: function(response)
    {
      var a = document.createElement("a");
      a.target = "_blank";
      a.href ="../reporte/pdfventa.php?codigo_venta="+codigo+"&identificacion_cliente="+icompleta+"";
      a.click();
      window.location.reload(true);
    }, 
    error: function(response)
    {
      alert("error en mostrar factura"+ response);
    }
  });
}

$("#btn-agregar").click(function()
{
	$("#btn-agregar").hide();
	$("#btn-salir").show();
	$("#tabla-agregar").slideToggle();
	alertify.success("Haga su Pedido Ingrese la Cantidad");
});

$("#btn-salir").on('click',function()
{
	$("#btn-salir").hide();
	$("#btn-agregar").show();
	$("#tabla-agregar").slideToggle();
});

$(".caja_cant").keypress(function(event)
{
	return solonumero(event);
});

$("#btn-cancelar").on('click',function(e)
{
	alertify.confirm('Cancelar','Desea cancelar el proceso?',
	function()
	{
    restaurar_master();
		window.location.reload(true);
	},
	function()
	{
		alertify.error('Cancelado');
	});
});

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
  	if(letras.indexOf(teclado)==-1 && !teclado_especial)
  	{
    	return false;
  	}
};

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
  	if(letras.indexOf(teclado)==-1 && !teclado_especial)
  	{
    	return false;
  	}
};

function load_venta_producto(page)
{
  var FiltroProductosListados = $("#FiltroProductosListados").val();
  var parametros = {"accion":"listar","page":page,"FiltroProductosListados":FiltroProductosListados};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_venta_producto_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".venta_listar").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  })
}

function agregar_producto_venta(page,codigo_producto,cantidad_vender)
{
  var constante = 1;
  var FiltroProductosVender = $("#FiltroProductosVender").val();
  var parametros = {"accion":"agregar","page":page,"codigo_producto":codigo_producto,"cantidad_vender":cantidad_vender,"FiltroProductosVender":FiltroProductosVender};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_venta_producto_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".factura_div").html(data).fadeIn('slow');
      load_venta_producto(constante);
      //$("#loader").html("");
      if(tabla_venta.rows.length>1)
      {
        $("#agregar-venta").attr('disabled',false);
      }
    }
  });
}

function load_venta(page)
{
  var constante = 1;
  var parametros = {"accion":"cargar","page":page};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_venta_producto_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".factura_div").html(data).fadeIn('slow');
      //$("#loader").html("");
      if(tabla_venta.rows.length>1)
      {
        $("#agregar-venta").attr('disabled',false);
      }
    }
  })
}

function load_ventas_registradas(page)
{
  var constante = 1;
  var FiltroFacturas = $("#FiltroFacturas").val();
  var parametros = {"accion":"facturas","page":page,"FiltroFacturas":FiltroFacturas};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_venta_producto_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".facturas_registrados").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  });
}

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
      $(".clientes_registrados").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  })
}

function eliminar_venta(codigo_venta,identificacion_cliente)
{
  var constante = 1;
  var FiltroFacturas = $("#FiltroFacturas").val();
  var parametros = {"accion":"anular","page":constante,"codigo_venta":codigo_venta,"identificacion_cliente":identificacion_cliente,"FiltroFacturas":FiltroFacturas};
  $.ajax(
  {
    url:'../listas/listar_venta_producto_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".facturas_registrados").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  });
}
