$(document).ready(function()
{
  load_proveedor_orden(1);
  load_proveedor_producto_orden_compra(1);

  $("#tabla-agregar").hide();
  $("#btn-salir").hide();
  $('#btn-agregar').attr('disabled',true);
  $('#agregar-orden').attr('disabled',true);
  $('#btn_direc_modal').hide();

});

///////////////////////////////////// ---------------> INICIO de Todo con respecto a PROVEEDOR EN EL MODAL

$("#btn_direc_modal").on('click',function()
{
  var identificacion= $("#identificacion-proveedor").val();
  var nacio=$("#nacio-proveedor").val();
  $("#nacio-proveedor-modal").val(nacio);
  $("#identificacion-proveedor-modal").val(identificacion);
  $("#nacio-proveedor-modal").attr("disabled",true);
  $("#identificacion-proveedor-modal").attr("disabled",true);

});

$("#close_modal").click(function()
{
  limpiarcamposmp();
});

function validado()
{
  var nombre= $('#razon-proveedor-modal').val();
  var direccion= $('#direccion-proveedor-modal').val();
  var correo= $('#correo-proveedor-modal').val();
  var telefono= $('#telefono-proveedor-modal').val();

  expresion=/\w+@\w+\.+[a-z]/;

  var verdad=true;

  if(nombre=="")
  {
    alertify.error("El campo Razón Social esta vacío");
    $('#razon-proveedor-modal').focus();
    return false;
  }
  else if (nombre.length<=3 || nombre.length>35)
  {
    alertify.error("Razón Social no valida");
    $('#razon-proveedor-modal').focus();
    return false;
  }
  else if (direccion=="")
  {
    alertify.error("El campo Dirección esta vacío");
    $('#direccion-proveedor-modal').focus();
    return false;
  }
  else if (direccion.length<5)
  {
    alertify.error("Debe dar una dirección mas específica");
    $('#direccion-proveedor-modal').focus();
    return false;
  }
  else if (correo=="")
  {
    alertify.error("El campo Correo esta vacío");
    $('#correo-proveedor-modal').focus();
    return false;
  }
  else if (!expresion.test(correo))
  {
    alertify.error("Debe dar un correo valido");
    $('#correo-proveedor-modal').focus();
    return false;
  }
  else if (telefono=="")
  {
    alertify.error("El campo Telefono esta vacío");
    $('#telefono-proveedor-modal').focus();
    return false;
  }
  else if (telefono.length<11 || telefono.length>11)
  {
    alertify.error("Numero de telefono no valido");
    $('#telefono-proveedor-modal').focus();
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

  especiales ="8-37-38-46-164-13";

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

$('#razon-proveedor-modal').keypress(function()
{
  key=event.keyCode || event.which;
  var veri=$('#razon-proveedor-modal').val();
  if (key === 13)
  {
    if (validado())
    {
      $('#direccion-proveedor-modal').focus();
    }
  }
});

$('#direccion-proveedor-modal').keypress(function()
{
  key=event.keyCode || event.which;
  var veri=$('#direccion-proveedor-modal').val();
  if (key === 13)
  {
    if (validado())
    {
      $('#correo-proveedor-modal').focus();
    }
  }
});

$('#correo-proveedor-modal').keypress(function()
{
  key=event.keyCode || event.which;
  var veri=$('#correo-proveedor-modal').val();
  if (key === 13)
  {
    if (validado())
    {
      $('#telefono-proveedor-modal').focus();
    }
  }
});

$('#identificacion-proveedor-modal').keypress(function(event)
{
  return solonumero(event);
});

$('#telefono-proveedor-modal').keypress(function(event)
{
  return solonumero(event);
});

$('#Agregar-proveedor-modal').click(function()
{
  if(validado())
  {
    var nacio= $('#nacio-proveedor-modal').val();
    var identificacion= $('#identificacion-proveedor-modal').val();
    var razon= $('#razon-proveedor-modal').val();
    var direccion= $('#direccion-proveedor-modal').val();
    var correo= $('#correo-proveedor-modal').val();
    var telefono= $('#telefono-proveedor-modal').val();

    var datos=
    {
      "Agregar":"submit",
      "identificacion-proveedor": nacio+identificacion,
      "nombre-proveedor": razon,
      "direccion-proveedor": direccion,
      "correo-proveedor": correo,
      "telefono-proveedor": telefono
    };

    $.ajax(
    {
      data: datos,
      url: '../controlador/controlador-proveedor.php',
      type: 'post',
      success: function(response)
      {
        alertify.alert("Registro exitoso",response);
        limpiarcamposmp();
        $("#close_modal").click();
        var bien = '<h5></h5>'
        $('#noexiste').html(bien);
        $('#Consultar').click();
        $('#btn-agregar').attr('disabled',false);
      },
      error: function(response)
      {
        alert("Me equivoqué"+ response);
      }
    });
  }
});

$("#Cancelar-proveedor-modal").click(function()
{
  var estatus= confirm("Deseas cancelar el proceso?");
  if (estatus==true) 
  {
    $("#close_modal").click();
    limpiarcamposmp();
  }
});

function limpiarcamposmp()
{
  $('#identificacion-proveedor-modal').val("");
  $('#razon-proveedor-modal').val("");
  $('#direccion-proveedor-modal').val("");
  $('#telefono-proveedor-modal').val("");
  $('#correo-proveedor-modal').val("");
}

///////////////////////////////////// ---------------> FINAL de Todo con respecto a PROVEEDOR EN EL MODAL

///////////////////////////////////// ---------------> INICIO de Todo con respecto a ORDEN DE COMPRA

$('#identificacion-proveedor').keypress(function(event)
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

$('#Consultar').on('click',function()
{
  var constante = 1;
  var nacio= $('#nacio-proveedor').val();
  var identificacion= $('#identificacion-proveedor').val();

  if(identificacion=="")
  {
    alertify.error('Complete el campo de la Identificación');
    $('#identificacion-proveedor').focus();
    return false;
  }
  else if(identificacion.length<6)
  {
    alertify.error('Identificación no valida');
    $('#identificacion-proveedor').focus();
    return false;
  }
  var rif = nacio+identificacion;

  var datos=
  {
    "Consultar":"submit",
    "identificacion-proveedor": nacio+identificacion
  };

  $.ajax(
  {
    data: datos,
    url: '../controlador/controlador-proveedor.php',
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
            $('#nombre-proveedor').val(mostrar.data[i].razon_social);
          }
          $('#nacio-proveedor').attr('disabled',true);
          $('#identificacion-proveedor').attr('disabled',true);
          $('#btn-agregar').attr('disabled',false);
          var bien = '<h5></h5>'
          $('#noexiste').html(bien);
          $('#Consultar').attr('disabled',true);
          $('#modal_proveedor').attr('disabled',true);
        }
        load_proveedor_producto(constante,rif);
      }
      catch(Exception)
      {
        alertify.alert("Proveedor no encontrado","No se encuentra el Proveedor");
        $('#btn_direc_modal').show();
      }
    },
    error: function(response)
    {
      alert("Me equivoqué"+ response);
    }
  });
});

function consultar(identificacion)
{
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
          }
          $("#Consultar").click();
        }
      }
      catch(Exception)
      {
        alertify.error("<h5>No se encuentra el Proveedor</h5>");
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
  $("#btn-agregar").hide();
  $("#btn-salir").show();
  $("#tabla-agregar").slideToggle();
  alertify.success("Realice su Pedido");
});

function eliminar_producto_orden(id_tmp,page)
{
  var FiltroOrdenCompra = $("#FiltroOrdenCompra").val();
  var idn=id_tmp.toString();
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
    var parametros = {"accion":"eliminar","page":page,"id_temp":idn,"cantidad_eliminada":cantidad_eliminada,"FiltroOrdenCompra":FiltroOrdenCompra};
    $.ajax(
    {
      url: "../listas/listar_proveedor_producto_orden_paginado.php",
      data: parametros,
      success: function(datos)
      {
        $("#orden_compra").html(datos);
        if(tabla_orden.rows.length===1)
        {
          $("#agregar-orden").attr('disabled',true);
        }
      }
    });
  },
  function()
  {
    alertify.error("Cancelado");
  });
}

function verificar_producto(codigo_producto)
{
  var codigop= "";
  var constante = 1;
  var stock_minimo = Number($("#stock_minimo_"+codigo_producto).val());
  var stock_maximo = Number($("#stock_maximo_"+codigo_producto).val());
  var cantidad= Number($("#cant_produc_"+codigo_producto).val());
  var tableReg = document.getElementById('proveedor-producto');

  if(cantidad==="")
  {
    alertify.error('Cantidad esta vacio');
    $("#cant_produc_"+codigo_producto).focus();
    return false;
  }
  else if(cantidad===0)
  {
    alertify.error('Cantidad no puede ser 0');
    $("#cant_produc_"+codigo_producto).focus();
    return false;
  }
  else
  {
    agregar_producto_orden(constante,codigo_producto,cantidad);
    $("#cant_produc_"+codigo_producto).val("");
  }
}

$("#agregar-orden").on('click',function(e)
{
  if(tabla_orden.rows.length<2)
  {
    alertify.error("La tabla esta vacía");
    return false;
  }
  
  var codigo = $("#codigo-orden").val();

  var datos=
  {
    "Agregar":"submit",
    "codigo-orden" : codigo
  }

  alertify.confirm("Confirmación","Desea realizar la orden?",
  function()
  {
    $.ajax(
    {
      data: datos,
      url:   '../controlador/controlador-orden-compra.php',
      type:  'post',
      success: function(response)
      {
        calculardetalle();
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
  var codigo=$("#codigo-orden").val();
  var nacio=$("#nacio-proveedor").val();
  var rif=$("#identificacion-proveedor").val();
  var identificacion=nacio+rif;
  var parametros = {"accion":"registrar","page":page,"codigo_orden":codigo,"identificacion":identificacion};

  $.ajax(
  {
    url:'../listas/listar_proveedor_producto_orden_paginado.php',
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
      $("#orden_compra").html(data).fadeIn('slow');
      //window.location.reload(true);
    }
  });
}

function mostrardetalle()
{
  var codigo=$("#codigo-orden").val();
  var nacio=$("#nacio-proveedor").val();
  var identificacion=$("#identificacion-proveedor").val();
  var icompleta=nacio+identificacion;

  var datos = 
  {
    "codigo_orden" : codigo,
    "identificacion_proveedor": nacio+identificacion
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
      a.href ="../reporte/pdforden.php?codigo_orden="+codigo+"&identificacion_proveedor="+icompleta+"";
      a.click();
      window.location.reload(true);
    }, 
    error: function(response)
    {
      alert("error en mostrar factura"+ response);
    }
  });
}

$("#btn-salir").on('click',function()
{
  $("#btn-salir").hide();
  $("#btn-agregar").show();
  $("#tabla-agregar").slideToggle();
});

$("#btn-cancelar").on('click',function(e)
{
  alertify.confirm('Cancelar','Desea cancelar el proceso?',
  function()
  {
    alertify.alert("Informacion!",'Saldra de la vista Registro Orden de Compra', function()
    {
      window.location.reload(true);
    });
  },
  function()
  {

  });
});

///////////////////////////////////// ---------------> FINAL de Todo con respecto a ORDEN DE COMPRA

function limpiarcampos()
{
  $('#identificacion-proveedor-modal').val("");
  $('#razon-proveedor-modal').val("");
  $('#direccion-proveedor-modal').val("");
  $('#telefono-proveedor-modal').val("");
  $('#correo-proveedor-modal').val("");
}

function load_proveedor_producto(page,rif)
{
  var nacio=$("#nacio-proveedor").val();
  var identificacion=$("#identificacion-proveedor").val();
  var rif = nacio+identificacion;
  var FiltroProducto = $("#FiltroProducto").val();
  var parametros = {"accion":"listar","page":page,"identificacion":rif, "FiltroProducto":FiltroProducto};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_proveedor_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".orden_div").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  })
}

function load_proveedor_orden(page)
{
  var FiltroProveedor = $("#FiltroProveedor").val();
  var parametros = {"accion":"cargar","page":page,"FiltroProveedor":FiltroProveedor};
  //$("#loader").fadeIn('slow');
  $.ajax({
    url:'../listas/listar_proveedor_orden_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $(".proveedor-producto_div").html(data).fadeIn('slow');
      //$("#loader").html("");
    }
  })
}

function load_proveedor_producto_orden_compra(page)
{
  var parametros = {"accion":"cargar","page":page};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_proveedor_producto_orden_paginado.php',
    data: parametros,
    type: 'POST',
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $("#orden_compra").html(data).fadeIn('slow');
      if (tabla_orden.rows.length>1)
      {
        $("#agregar-orden").attr('disabled',false);
      }
      //$("#loader").html("");
    }
  })
}

function agregar_producto_orden(page,id,cantidad)
{
  var FiltroOrdenCompra = $("#FiltroOrdenCompra").val();
  var parametros = {"accion":"insertar","page":page,"codigo_producto":id,"cantidad":cantidad,"FiltroOrdenCompra":FiltroOrdenCompra};
  //$("#loader").fadeIn('slow');
  $.ajax(
  {
    url:'../listas/listar_proveedor_producto_orden_paginado.php',
    data: parametros,
    beforeSend: function(objeto)
    {
      //$("#loader").html("<img src='loader.gif'>");
    },
    success:function(data)
    {
      $("#orden_compra").html(data).fadeIn('slow');
      if (tabla_orden.rows.length>1)
      {
        $("#agregar-orden").attr('disabled',false);
      }
      //$("#loader").html("");
    }
  })
}