/////////////////////////////////////////////////////////////// ---------------> INICIO botones CAJAS
$(document).ready(function() {

 /* -------------------- MENU CENTRO -------------------------*/
/* POR PRODUCTO AMCHART*/
    $('#graficoproductos').on('click', function() {
        $("#caja2").removeClass();
        $("#caja2").load('../reportegrafico/productosventa.php');
        return false;
    });
/*    POR FECHA*/
    $('#graficoreporte').on('click', function() {
        $("#caja2").removeClass();
        $("#caja").removeClass();
        $("#caja2").load('../reportegrafico/vista/vista_porfecha.php');
        return false;
    });
/*    REPORTE VENTA*/
/*    $('#graficoreporte').on('click', function() {
        $("#caja2").removeClass();
        $("#caja").removeClass();
        $("#caja2").load('../reportegrafico/vista/vista_porfecha.php');
        return false;
    });*/
/*    POR CLIENTE*/
    $('#graficocliente').on('click', function() {
        $("#caja2").removeClass();
        $("#caja").removeClass();
        $("#caja2").load('../reportegrafico/vista/vista_clienteporfecha.php');
        return false;
    });   
  });

