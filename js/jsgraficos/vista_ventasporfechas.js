/*PRODUCTOS MAS VENDIDOS POR FECHA*/
$("#productos_vendidosfecha").on('click',function()
{
var date = new Date();
var hora = ("0" + date.getHours()).slice(-2)+":"+("0" + date.getMinutes()).slice(-2)+":"+("0" + date.getSeconds()).slice(-2);
var fecha_desde=$("#fecha_desde").val();
var fecha_hasta=$("#fecha_hasta").val();

	var daticos = {"fecha_desde":fecha_desde, "fecha_hasta" : fecha_hasta};

if (fecha_desde==fecha_hasta || fecha_hasta==fecha_desde) {
alertify.alert("Error","Las fechas no pueden ser iguales",function(){     });		
}
	else if (fecha_hasta<fecha_desde) {
		alertify.alert("Error","La fecha "+fecha_desde+" tiene que ser mayor a "+fecha_hasta+"",function(){   });
		}

		else if (fecha_desde=="" || fecha_hasta=="") {
		  alertify.alert("Error","Selecciones la fecha",function(){         });
			}

			else{
				  var daticos = {"fecha_desde":fecha_desde,"fecha_hasta" : fecha_hasta, "hora":hora};

			        $.ajax({
			          data: daticos,
			            url:   '../controlador/reportegrafico/control_ajax_buscarporfecha.php',
			            type:  'post',
			            success:  function (response){
			            	/*	COMPARAR STRING*/
			            		
			                 	var respuesta = response.toString();
			                  	var nohay = 1;

			                  if (nohay == respuesta) {
			                  alertify.alert("No hay ventas","No existen ventas dentro de este periodo de fechas",function(){    });                  	
			                  }

							else {
								 alertify.alert("Correcto","Generando reporte",function()
								 {

					$("#caja2").removeClass();
			        $("#caja2").load("../reportegrafico/ventaporfecha.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"");

							     	});	
								}             	
			                  					}, 

				          error: function(response) {
				                alert("Me equivoqué"+ response);
				          }
			          });
				};
});

/*BARRA VENTA CANTIDAD VENDIDAS POR FECHA*/
$("#ventas_cantidadfecha").on('click',function()
{
var date = new Date();
var hora = ("0" + date.getHours()).slice(-2)+":"+("0" + date.getMinutes()).slice(-2)+":"+("0" + date.getSeconds()).slice(-2);
var fecha_desde=$("#fecha_desde").val();
var fecha_hasta=$("#fecha_hasta").val();

	 var daticos = {"fecha_desde":fecha_desde,"fecha_hasta" : fecha_hasta};

 if (fecha_desde==fecha_hasta || fecha_hasta==fecha_desde) {
alertify.alert("Error","Las fechas no pueden ser iguales",function(){     });
}
	else if (fecha_hasta<fecha_desde) {
		alertify.alert("Error","La fecha "+fecha_desde+" tiene que ser mayor a "+fecha_hasta+"",function(){      });
		}

		else if (fecha_desde=="" || fecha_hasta=="") {
		  alertify.alert("Error","Selecciones la fecha",function(){    });
		}

		else{
			var daticos = {"fecha_desde":fecha_desde,"fecha_hasta" : fecha_hasta, "hora":hora};

			        $.ajax({
			          data: daticos,
			            url:   '../controlador/reportegrafico/control_ajax_cantidadventasporfecha.php',
			            type:  'post',
			            success:  function (response){
			            	/*	COMPARAR STRING*/
			                 	var respuesta2 = response.toString();
			                  	var nohay = 1;

			                  if (nohay == respuesta2) {
			                  alertify.alert("No hay ventas","No existen ventas dentro de este periodo de fechas",function(){    });                  	
			                  }

							else {
								 alertify.alert("Correcto","Generando reporte",function()
								 {

					$("#caja2").removeClass();
			        $("#caja2").load("../reportegrafico/barraventaporfecha.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"");

							     	});	
								}             	
			                  					}, 
			
				          error: function(response) {
				                alert("Me equivoqué"+ response);
				          }
			          });
			    };
/*		 alertify.alert("Correcto","Generando reporte",function()
	     {
	     var a = document.createElement("a");
	     a.target = "_blank";
	     a.href ="../reportegrafico/barraventaporfecha.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"";
	     a.click();
	     });*/	
		
		

});

$("#btn_imprimir_barra_GTPF").on('click',function()
{
	var fecha_desde=$("#fecha_desde").val();
	var fecha_hasta=$("#fecha_hasta").val();

	 var daticos = {"fecha_desde":fecha_desde,
     "fecha_hasta" : fecha_hasta};

     	if (fecha_desde==fecha_hasta || fecha_hasta==fecha_desde) {

		alertify.alert("Error","Las fechas no pueden ser iguales",function()
		{
			
		});
			
	}
	else if (fecha_hasta<fecha_desde) {
	alertify.alert("Error","La fecha "+fecha_desde+" tiene que ser mayor a "+fecha_hasta+"",function()
		{
			
		});
	}
	else if (fecha_desde=="" || fecha_hasta=="") {
	  alertify.alert("Error","Selecciones la fecha",function()
	  	{
			
	  	});
	}
	else{
	 alertify.alert("Correcto","Generando reporte",function()
     {
     var a = document.createElement("a");
     a.target = "_blank";
     a.href ="../reportes/barraventagastoporfecha.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"";
     a.click();
     });	
	
	}

});

$("#btn_imprimir_barra_VCPF").on('click',function()
{
	var fecha_desde=$("#fecha_desde").val();
	var fecha_hasta=$("#fecha_hasta").val();

	 var daticos = {"fecha_desde":fecha_desde,
     "fecha_hasta" : fecha_hasta};

     	if (fecha_desde==fecha_hasta || fecha_hasta==fecha_desde) {

		alertify.alert("Error","Las fechas no pueden ser iguales",function()
		{
			
		});
			
	}
	else if (fecha_hasta<fecha_desde) {
	alertify.alert("Error","La fecha "+fecha_desde+" tiene que ser mayor a "+fecha_hasta+"",function()
		{
			
		});
	}
	else if (fecha_desde=="" || fecha_hasta=="") {
	  alertify.alert("Error","Selecciones la fecha",function()
	  	{
			
	  	});
	}
	else{
	 alertify.alert("Correcto","Generando reporte",function()
     {
     var a = document.createElement("a");
     a.target = "_blank";
     a.href ="../reportes/barraventagastoporclienteporfecha.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"";
     a.click();
     });
	}

});



















/* BUSCAR no usado*/
$("#btn_buscar").on('click',function()
{
	var fecha_desde=$("#fecha-desde").val();
	var fecha_hasta=$("#fecha-hasta").val();

	if (fecha_desde==fecha_hasta || fecha_hasta==fecha_desde) {

		alertify.alert("Error","Las fechas no pueden ser iguales",function()
		{
			
		});
			
	}
	else if (fecha_hasta<fecha_desde) {
	alertify.alert("Error","La fecha "+fecha_desde+" tiene que ser mayor a "+fecha_hasta+"",function()
		{
			
		});
	}
	else if (fecha_desde=="" || fecha_hasta=="") {
	  alertify.alert("Error","Selecciones la fecha",function()
	  	{
			
	  	});
	}
	else
	{
	  var daticos = {"fecha_desde":fecha_desde,
      "fecha_hasta" : fecha_hasta};

        $.ajax({
          data: daticos,
                  url:   '../control/control_ajax_buscarporfecha.php',
                  type:  'post',
                  success:  function (response) {
                        //$("#cinco").html(response);
                        //alertify.alert("Correcto",response);
                        $("#resultado_fechas").html(response);
                        if (tablaporfecha.rows.length<2)
                        {
                        	alertify.alert("No hay ventas","No se encuentran ventas",function()
							{
			
							});
                        }
                  }, 
          error: function(response) {
                alert("Me equivoqué"+ response);
          }
          });
	}

	//alert("Tus fechas son :"+fecha_desde+" "+fecha_hasta);

	  
});
/*NO USADO TODAVIA*/
$("#btn_imprimir_fechas").on('click',function()
{
	var fecha_desde=$("#fecha-desde").val();
	var fecha_hasta=$("#fecha-hasta").val();


	 var daticos = {"fecha_desde":fecha_desde,
     "fecha_hasta" : fecha_hasta};

     	if (fecha_desde==fecha_hasta || fecha_hasta==fecha_desde) {

		alertify.alert("Error","Las fechas no pueden ser iguales",function()
		{
			
		});
			
	}
	else if (fecha_hasta<fecha_desde) {
	alertify.alert("Error","La fecha "+fecha_desde+" tiene que ser mayor a "+fecha_hasta+"",function()
		{
			
		});
	}
	else if (fecha_desde=="" || fecha_hasta=="") {
	  alertify.alert("Error","Selecciones la fecha",function()
	  	{
			
	  	});
	}
	else  if (tablaporfecha.rows.length<2) {
    alertify.error("La tabla esta vacia");
    //return false;
  	}
	else{
	 alertify.alert("Correcto","Generando reporte",function()
     {
     var a = document.createElement("a");
     a.target = "_blank";
     a.href ="../reportes/pdfporfechas.php?desde="+fecha_desde+"&hasta="+fecha_hasta+"";
     a.click();
     });	
	
	}

});
