/////////////////////////////////////////////////////////////// ---------------> INICIO botones Menu izquierdo
$(document).ready(function() {

 /* -------------------- MENU CENTRO -------------------------*/
    $('#btnordencompra2').on('click', function() {
        $("central").removeClass();
        $("#central").load('../vista/orden_compra.php');
        return false;
    });

    $('#btnventa2').on('click', function() {
        $("central").removeClass();
        $("#central").load('../vista/venta.php');
        return false;
    });

    $('#btncrearusuario2').on('click', function() {
        $("central").removeClass();
        $("#central").load('../vista/crearusuario.php');
        return false;
    });
   $('#btncompra2').on('click', function() {
        $("central").removeClass();
        $("#central").load('../vista/compra.php');
        return false;
    });

/*--------------------- FINAL MENU CENTRO ----------------*/
    $('#btncliente').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/cliente.php');
        return false;
    });

    $('#btnproveedor').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/proveedor.php');
        return false;
    });

    $('#btnproducto').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/producto.php');
        return false;
    });

    $('#btncompra').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/compra.php');
        return false;
    });

    $('#btnordencompra').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/orden_compra.php');
        return false;
    });

    $('#btnventa').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/venta.php');
        return false;
    });
/*REPORTES*/
    $('#btnreporteCliente').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../reporte/ReporteCliente.php');
        return false;
    });

    $('#btnreporteProveedor').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../reporte/ReporteProveedor.php');
        return false;    });

    /*$('#btnreporteInventario').on('click', function() {
        $("central").removeClass();
        $("#central").load('../reporte/ReporteInventario.php');
        return false;
    });*/

    $('#btninventario').on('click', function()
    {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/inventario.php');
        return false;
    });
/*  TOMA DE DECISION */

    $('#btnreportedecisionproductoventa').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../reportegrafico/productosventa.php');
        return false;
    });
/*      REPORTE GRAFICOS*/
    $('#btnreportegrafico').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/graficas.php');
        return false;
    });

/*MANTENIMIENTO*/

    $('#btncrearusuario').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/crearusuario.php');
        return false;
    });

    $('#btncambiarpass').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/cambiarpass.php');
        return false;
    });

    $('#btnrespaldo').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/respaldo.php');
        return false;
    });
    $('#btnbitacora').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/bitacora.php');
        return false;
    });
/*DOCUMENTACION*/

    $('#btndocumentacion').on('click', function() {
        $('.navbar-nav li').removeClass('active');
        $("#central").load('../vista/manualusuario.php');
       // $("#central").css( "height","100vh" );
        return false;
    });
/*        ELIMINAR MANTENIMIENTO SI NO ES USUARIO ADMINISTRADOR*/
    var tipousuario = $("#tipodeusuario").val();
    if (tipousuario == "Usuario"){  $('#mantenimiento').hide();  $('#btninventario').hide();  $('#btncompra').hide(); } 

    numero_ordenes(1);
    numero_venta(1);  
    numero_usuario(1);
    numero_compra(1);

  });

function numero_ordenes(page)
{
    var parametros = {"action1":"ajax1","page":page};

    $.ajax({
        url:'../controlador/controlador-menu.php',
        data: parametros,
        success:function(data)
        {
            $(".ordenescantidad").html(data).fadeIn('slow');

        }
    })
}

function numero_venta(page)
{
    var parametros = {"action2":"ajax2","page":page};

    $.ajax({
        url:'../controlador/controlador-menu.php',
        data: parametros,
        success:function(data)
        {
            $(".ventascantidad").html(data).fadeIn('slow');
            //$("#loader").html("");
        }
    })
}

function numero_usuario(page)
{
    var parametros = {"action3":"ajax3","page":page};

    $.ajax({
        url:'../controlador/controlador-menu.php',
        data: parametros,
        success:function(data)
        {
            $(".usuarioscantidad").html(data).fadeIn('slow');

        }
    })
}


function numero_compra(page)
{
    var parametros = {"action4":"ajax4","page":page};

    $.ajax({
        url:'../controlador/controlador-menu.php',
        data: parametros,
        success:function(data)
        {
            $(".comprascantidad").html(data).fadeIn('slow');

        }
    })
}
