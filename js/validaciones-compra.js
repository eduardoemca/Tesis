$(document).ready(function()
{
  load_ordenes_registradas(1);
  load_orden_compra(1);
  $("#tabla-agregar").hide();
  $("#btn-salir").hide();
  $('#agregar-compra').attr('disabled',true);
  $('#DivFiltroOrdenCompraRegistrada').hide();
  $('#actualizar-compra').hide();
  $('#actualizar-compra').attr('disabled',true);
  load_compra(1);
  load_iva(1);
});

$("#Cerrar-modal-tabla").click(function()
{
	$("#close_modal").click();
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

function validado()
{
  var orden = $("#codigo-orden").val();
  var codigo_factura = $("#codigo-compra").val();

  var verdad=true;

  if(orden==="")
  {
    alertify.error("El Nro° de Orden esta vacío");
    $('#codigo-orden').focus();
    return false;
  }
  else if(orden.length <5 || orden.length >6)
  {
    alertify.error("El Nro° de Orden no valido");
    $('#codigo-orden').focus();
    return false;
  }
  else if(codigo_factura==="")
  {
    alertify.error("El Nro° de Factura esta vacío");
    $('#codigo-compra').focus();
    return false;
  }
  else if(codigo_factura.length <3 || codigo_factura.length >10)
  {
    alertify.error("El Nro° de Factura no valido");
    $('#codigo-compra').focus();
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

function solodecimal(e)
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
    if(letras.indexOf(teclado)==-1 && !teclado_especial)
    {
      return false;
    }


};


$('#codigo-orden').keypress(function()
{
	key=event.keyCode || event.which;
  if (key === 13)
  {
    $('#Consultar').click();
    return false;
  }
});

$('#codigo-compra').keypress(function()
{
  key=event.keyCode || event.which;
  if (key === 13)
  {
    $('#Consultar').click();
    return false;
  }
});

$('#Consultar').on('click',function()
{
  var constante =1;
	var orden = $("#codigo-orden").val();
	var codigo_factura = $("#codigo-compra").val();

	if(orden==="")
	{
		alertify.error("El Nro° de Orden esta vacío");
	  $('#codigo-orden').focus();
	  return false;
	}
	else if(orden.length <5 || orden.length >6)
	{
		alertify.error("El Nro° de Orden no valido");
	  $('#codigo-orden').focus();
	  return false;
	}
	else if(codigo_factura==="")
	{
		alertify.error("El Nro° de Factura esta vacío");
	  $('#codigo-compra').focus();
	  return false;
	}
	else if(codigo_factura.length <3 || codigo_factura.length >10)
	{
		alertify.error("El Nro° de Factura no valido");
	  $('#codigo-compra').focus();
	  return false;
	}

	var datos=
 	{
   	"Consultar":"submit",
   	"codigo-orden": orden
 	};

 	$.ajax(
  {
    data: datos,
    url: '../controlador/controlador-compra.php',
    type: 'post',
    success: function(response)
    {
     	try
     	{
        var mostrar= JSON.parse(response);
        if (mostrar.data.length!=0)
        {
         	for(var i in mostrar.data)
         	{
         		$('#identificacion-proveedor').val(mostrar.data[i].codigo_proveedor);
         		$('#nombre-proveedor').val(mostrar.data[i].razon_social);
       		}
          var identificacion = $("#identificacion-proveedor").val();
       		desahabilitarcampos();
       		$('#btn-agregar').attr('disabled',false);
       		$('#Consultar').attr('disabled',true);
       	}
        load_orden_compra(constante,orden,identificacion);
        $('#btn_iva_modal').attr('disabled',true);
     	}
     	catch(Exception)
     	{
       	alertify.alert("Orden no encontrada","No se encuentra esta Orden2");
     	}
    },
 	 	error: function(response)
   	{
   		alert("Me equivoqué"+ response);
   	}
 	});
});

function consultar_orden(codigo_orden,estado)
{
  var constante = 1;
  var datos=
  {
    "Consultar-Generada":"submit",
    "codigo-orden": codigo_orden,
    "estado-orden": estado
  };

  $.ajax(
  {
    data: datos,
    url: '../controlador/controlador-compra.php',
    type: 'post',
    success: function(response)
    {
      try
      {
        var mostrar= JSON.parse(response);
        if (mostrar.data.length!=0)
        {
          for(var i in mostrar.data)
          {
            $('#codigo-orden').val(mostrar.data[i].codigo_orden_compra);
            $('#identificacion-proveedor').val(mostrar.data[i].codigo_proveedor);
            $('#nombre-proveedor').val(mostrar.data[i].razon_social);
          }
          var identificacion = $("#identificacion-proveedor").val();
          $("#close_modal").click();
          $(".modal-backdrop.fade.in").remove();
          $('#codigo-orden').attr('disabled',true);
          $('#Consultar').attr('disabled',true);
          $('#btn_direc_modal ').attr('disabled',true);
          alertify.alert('Informacion!','Por favor Ingrese el Nro° de Factura');
          $('#codigo-compra').focus();
        }
        //load_orden_compra(constante,codigo_orden,identificacion);
        //load_compra_cargado(constante);
      }
      catch(Exception)
      {
        alertify.alert("Orden no encontrada","No se encuentra esta Orden");
      }
    },
    error: function(response)
    {
      alert("Me equivoqué"+ response);
    }
  });
}

function cerrar_orden(codigo_orden,estado)
{
  function cerrada()
  {
    var usuario = $("#usuariohidden").val();
    var session = $("#sessionhidden").val();
    var datos =
    {
      "Cerrar-orden":"submit",
      "codigo-orden" : codigo_orden,
      "estado-orden" : estado,
      "usuario" : usuario,
      "session" : session
    };

    $.ajax(
    {
      data: datos,
      url: '../controlador/controlador-compra.php',
      type: 'post',
      success:  function (response)
      {
        alertify.alert("Información!", response);
        load_ordenes_registradas();
      }, 
      error: function(response)
      {
        alertify.alert("Me equivoqué"+ response);
      }
    });
  }
  alertify.confirm('Cerrar','¿Desea cerrar la orden generada?',
  function()
  {
    cerrada();
  },
  function()
  {

  });
}

function cerrar_orden_registrada(codigo,estado,codigo_proveedor)
{
  alertify.confirm('Cerrar','¿Desea cerrar la orden pendiente?',
  function()
  {
    
    var usuario = $("#usuariohidden").val();
    var session = $("#sessionhidden").val();
    var datos =
    {
      "Cerrar-orden-compra-registrada":"submit",
      "codigo-orden" : codigo,
      "estado-orden" : estado,
      "codigo-proveedor" : codigo_proveedor,
      "usuario" : usuario,
      "session" : session
    };

    $.ajax(
    {
      data: datos,
      url: '../controlador/controlador-compra.php',
      type: 'post',
      success:  function (response)
      {
        alertify.alert("Información!", response);
        load_ordenes_registradas();
      }, 
      error: function(response)
      {
        alertify.alert("Me equivoqué"+ response);
      }
    });
  },
  function()
  {

  });
}

function imprimir_orden_cerrada(codigo,codigo_proveedor)
{
  function imprimir()
  {
    var usuario = $("#usuariohidden").val();
    var session = $("#sessionhidden").val();
    var datos =
    {
      "codigo-orden" : codigo,
      "codigo_proveedor": codigo_proveedor
    };

    $.ajax(
    {
      data: datos,
      url:'../reporte/pdforden.php',
      type:'post',
      success: function(response)
      {
        var a = document.createElement("a");
        a.target = "_blank";
        a.href ="../reporte/pdforden.php?codigo_orden="+codigo+"&identificacion_proveedor="+codigo_proveedor+"";
        a.click();
      }, 
      error: function(response)
      {
        alert("Error en mostrar factura"+ response);
      }
    });
  }
  alertify.confirm('Cerrar','¿Desea imprimir la orden?',
  function()
  {
    imprimir();
  },
  function()
  {

  });
}

function consultar_orden_registrada(codigo_orden,estado)
{
  var constante = 1;
  var datos=
  {
    "Consultar-Pendiente":"submit",
    "codigo-orden": codigo_orden,
    "estado-orden": estado
  };

  $.ajax(
  {
    data: datos,
    url: '../controlador/controlador-compra.php',
    type: 'post',
    success: function(response)
    {
      try
      {
        
        console.log(response);
        var mostrar= JSON.parse(response);
        if (mostrar.data.length!=0)
        {
          for(var i in mostrar.data)
          {
            $('#codigo-orden').val(mostrar.data[i].codigo_orden_compra);
            $('#identificacion-proveedor').val(mostrar.data[i].codigo_proveedor);
            $('#nombre-proveedor').val(mostrar.data[i].razon_social);
            $('#codigo-compra').val(mostrar.data[i].codigo_compra);
          }
          var identificacion = $("#identificacion-proveedor").val();
          $("#close_modal").click();
          $(".modal-backdrop.fade.in").remove();
          desahabilitarcampos();
          $('#Consultar').attr('disabled',true);
          $('#btn_direc_modal ').attr('disabled',true);
          $('#DivFiltroOrdenCompra').hide();
          $('#agregar-compra').hide();
          $('#DivFiltroOrdenCompraRegistrada').show();
          $('#actualizar-compra').show();
        }
        load_orden_compra_registrada(constante,codigo_orden,identificacion);
        load_compra_cargado(constante);
      }
      catch(Exception)
      {
        alertify.alert("Orden no encontrada","No se encuentra esta Orden1");
      }
    },
    error: function(response)
    {
      alert("Me equivoqué"+ response);
    }
  });
}

$("#btn-agregar").click(function()
{
  var orden = $("#codigo-orden").val();
  var codigo_factura = $("#codigo-compra").val();

  if(orden==="")
  {
    alertify.error("El Nro° de Orden esta vacío");
    $('#codigo-orden').focus();
    return false;
  }
  else if(orden.length <5 || orden.length >6)
  {
    alertify.error("El Nro° de Orden no valido");
    $('#codigo-orden').focus();
    return false;
  }
  else if(codigo_factura==="")
  {
    alertify.error("El Nro° de Factura esta vacío");
    $('#codigo-compra').focus();
    return false;
  }
  else if(codigo_factura.length <3 || codigo_factura.length >10)
  {
    alertify.error("El Nro° de Factura no valido");
    $('#codigo-compra').focus();
    return false;
  }
  else
  {
    $("#btn-agregar").hide();
    $("#btn-salir").show();
    $("#tabla-agregar").slideToggle();
    alertify.success("Ingrese la Cantidad y Precio del Producto Recibido");
  }
});

function verificar_codigo(idproducto)
{ 
  var codigop= "";
  var constante = 1;
  var cantidad_solicitada = Number($("#cant_solic"+idproducto).val());
  var cantidad = Number($("#cant_produc_"+idproducto).val());
  var precio = Number($("#precio_produc_"+idproducto).val());
  var tableReg = document.getElementById('tabla_orden_compra');

  if(cantidad==="")
  {
    alertify.error('Cantidad esta vacio');
    $("#cant_produc_"+idproducto).focus();
    return false;
  }
  else if(cantidad===0)
  {
    alertify.error('Cantidad no puede ser 0');
    $("#cant_produc_"+idproducto).focus();
    return false;
  }
  if(precio==="")
  {
    alertify.error('Precio esta vacio');
    $("#precio_produc_"+idproducto).focus();
    return false;
  }
  else if(precio===0)
  {
    alertify.error('Precio no puede ser 0');
    $("#precio_produc_"+idproducto).focus();
    return false;
  }
  else
  {
    for(var i = 1; i < tableReg.rows.length; i++)
    {
      var codigop = (tableReg.rows[i].cells[0].innerHTML);
      if(codigop === idproducto)
      {
        if(cantidad > cantidad_solicitada)
        {
          alertify.alert("Alerta","Cantidad mayor a la Solicitada");
          return false;
        }
        else
        {
          agregar_producto(constante,idproducto,cantidad,precio);
         $("#precio_produc_"+idproducto).val("");

        }
      }
    }
  }
}

function eliminar_producto(id_tmp,codigo_producto)
{
  var constante = 1;
  var idn=id_tmp.toString();
  var orden = $("#codigo-orden").val();
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
      var parametros = {"accion":"eliminar","page":constante,"id_temp":idn,"cantidad_eliminada":cantidad_eliminada,"codigo_producto":codigo_producto,"codigo_orden":orden};
     $.ajax(
      {
        type: "POST",
        url: "../listas/listar_compra_paginado.php",
        data: parametros,
        success: function(datos)
        {
          $(".compra_div").html(datos);
          if(tabla_compra.rows.length===1)
          {
            $("#agregar-compra").attr('disabled',true);
            $("#actualizar-compra").attr('disabled',true);
          }
        }
      });
    },function()
    {
      alertify.error("Cancelado");
    });
}

$("#agregar-compra").on('click',function(e)
{
  if(tabla_compra.rows.length<2)
  {
    alertify.error("La tabla esta vacía");
    return false;
  }

  var codigo_factura = $("#codigo-compra").val();
  var orden = $("#codigo-orden").val();

  if(orden==="")
  {
    alertify.error("El Nro° de Orden esta vacío");
    $('#codigo-orden').focus();
    return false;
  }
  else if(orden.length <5 || orden.length >6)
  {
    alertify.error("El Nro° de Orden no valido");
    $('#codigo-orden').focus();
    return false;
  }
  else if(codigo_factura==="")
  {
    alertify.error("El Nro° de Factura esta vacío");
    $('#codigo-compra').focus();
    return false;
  }
  else if(codigo_factura.length <3 || codigo_factura.length >10)
  {
    alertify.error("El Nro° de Factura no valido");
    $('#codigo-compra').focus();
    return false;
  }else if(tabla_compra.rows.length<2)
  {
    alertify.error("La tabla esta vacia");
    return false;
  }

  var identificacion = $("#identificacion-proveedor").val();

  var datos=
  {
    "Agregar":"submit",
    "codigo-compra" : codigo_factura,
    "codigo-orden" : orden,
    "identificacion-proveedor" : identificacion
  }

  alertify.confirm("Confirmación","Desea realizar la orden?",
    function()
    {
      $.ajax(
      {
          data: datos,
          url:   '../controlador/controlador-compra.php',
          type:  'post',
          success: function(response)
          {
            alertify.alert('Correcto',response,function()
            {
              calculardetalle();
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

$("#actualizar-compra").on('click',function(e)
{
  if(tabla_compra.rows.length<2)
  {
    alertify.error("La tabla esta vacía");
    return false;
  }

  var constante = 1;
  var orden = $("#codigo-orden").val();
  var identificacion = $("#identificacion-proveedor").val();
  var codigo_factura = $("#codigo-compra").val();

  if(orden==="")
  {
    alertify.error("El Nro° de Orden esta vacío");
    $('#codigo-orden').focus();
    return false;
  }
  else if(orden.length <5 || orden.length >6)
  {
    alertify.error("El Nro° de Orden no valido");
    $('#codigo-orden').focus();
    return false;
  }
  else if(codigo_factura==="")
  {
    alertify.error("El Nro° de Factura esta vacío");
    $('#codigo-compra').focus();
    return false;
  }
  else if(codigo_factura.length <3 || codigo_factura.length >10)
  {
    alertify.error("El Nro° de Factura no valido");
    $('#codigo-compra').focus();
    return false;
  }else if(tabla_compra.rows.length<2)
  {
    alertify.error("La tabla esta vacia");
    return false;
  }

  var parametros = {"accion":"actualizar","page":constante,"codigo_factura":codigo_factura,"codigo_orden":orden,"identificacion":identificacion};

  alertify.confirm("Confirmación","Desea realizar la compra?",
    function()
    {
      $.ajax(
      {
        url:'../listas/listar_compra_paginado.php',
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
          $(".compra_div").html(data).fadeIn('slow');
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
  var codigo_factura = $("#codigo-compra").val();
  var orden = $("#codigo-orden").val();
  var parametros = {"accion":"registrar","page":page,"codigo_factura":codigo_factura,"codigo_orden":orden};

  $.ajax(
  {
    url:'../listas/listar_compra_paginado.php',
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
      $(".compra_div").html(data).fadeIn('slow');
    }
  });
}

function mostrardetalle()
{
  var codigocompra = $("#codigo-compra").val();
  var identificacion = $("#identificacion-proveedor").val();
  var orden = $("#codigo-orden").val();

  var datos = 
  {
    "codigo_compra" : codigocompra,
    "identificacion_proveedor": identificacion,
    "codigo_orden": orden
   
  };

  $.ajax(
  {
    data: datos,
    url:'../reporte/pdfcompra.php',
    type:'post',
    success: function(response)
    {
      var a = document.createElement("a");
      a.target = "_blank";
      a.href ="../reporte/pdfcompra.php?codigo_compra="+codigocompra+"&identificacion_proveedor="+identificacion+"&codigo_orden="+orden+""; //cosas de compra
      a.click();
      window.location.reload(true);
    }, 
    error: function(response)
    {
      alert("Error en mostrar factura"+ response);
    }
  });
}

$("#btn-salir").on('click',function()
{
  $("#btn-salir").hide();
  $("#btn-agregar").show();
  $("#tabla-agregar").slideToggle();
});

function desahabilitarcampos()
{
  $('#codigo-orden').attr('disabled',true);
  $('#codigo-compra').attr('disabled',true);
}

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

$("#estado").change(function()
{
  load_ordenes_registradas();
});

function load_ordenes_registradas(page,estado)
{
  var FiltroOrdenesRegistradas = $("#FiltroOrdenesRegistradas").val();
  var estado = $("#estado").val();
  var parametros = {"accion":"cargar","page":page,"estado":estado,"FiltroOrdenesRegistradas":FiltroOrdenesRegistradas};
  $.ajax(
  {
    url:'../listas/listar_ordenes_registradas.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".ordenesregistradas_div").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  });
}

function load_orden_compra(page,orden,identificacion)
{
  var orden = $("#codigo-orden").val();
  var identificacion = $("#identificacion-proveedor").val();
  var FiltroOrdenCompra = $("#FiltroOrdenCompra").val();
  var parametros = {"accion":"orden","page":page,"orden_compra":orden,"identificacion":identificacion,"FiltroOrdenCompra":FiltroOrdenCompra};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_orden_compra.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".orden_compra_div").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  });
}

function load_orden_compra_registrada(page,orden,identificacion)
{
  var compra = $("#codigo-compra").val();
  var orden = $("#codigo-orden").val();
  var identificacion = $("#identificacion-proveedor").val();
  var FiltroOrdenCompraRegistrada = $("#FiltroOrdenCompraRegistrada").val();
  var parametros = {"accion":"compra","page":page,"compra":compra,"orden_compra":orden,"identificacion":identificacion,"FiltroOrdenCompraRegistrada":FiltroOrdenCompraRegistrada};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_orden_compra.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".orden_compra_div").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  });
}

function load_compra(page)
{
  var parametros = {"accion":"cargar","page":page};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_compra_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".compra_div").html(data).fadeIn('slow');
      if (tabla_compra.rows.length>1)
      {
        $("#agregar-compra").attr('disabled',false);
      }
      //$("#loader").html("");
    }
  });
}

function load_compra_cargado(page)
{
  var FiltroCompra = $("#FiltroCompra").val();
  var parametros = {"accion":"cargado","page":page,"FiltroCompra":FiltroCompra};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_compra_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".compra_div").html(data).fadeIn('slow');
      if (tabla_compra.rows.length>1)
      {
        $("#agregar-compra").attr('disabled',false);
      }
      //$("#loader").html("");
    }
  });
}

function agregar_producto(page,idproducto,cantidad,precio)
{
  var codigo_orden = $("#codigo-orden").val();
  var FiltroCompra = $("#FiltroCompra").val();
  var parametros = {"accion":"insertar","page":page,"codigo_producto":idproducto,"cantidad":cantidad,"precio":precio,"FiltroCompra":FiltroCompra,"codigo_orden":codigo_orden};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_compra_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".compra_div").html(data).fadeIn('slow');
      if (tabla_compra.rows.length>1)
      {
        $("#agregar-compra").attr('disabled',false);
        $("#actualizar-compra").attr('disabled',false);
      }
      //$("#loader").html("");
    }
  });
}

function restaurar_master()
{
  var codigo_orden = $("#codigo-orden").val();
  var constante = 1;
  var parametros = {"accion":"restaurar","page":constante,"codigo_orden":codigo_orden};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_compra_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".compra_div").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  });
}