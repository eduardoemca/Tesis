/*BARRA CLIENTE GASTO GENERAL*/
$(document).ready(function() {
    $('#clientesgeneral').on('click', function() {
        $("#caja2").removeClass();
        $("#caja2").load('../reportegrafico/barraventagastoporcliente.php');
        return false;
    });   
  });

/*BARRA VENTA CANTIDAD VENDIDAS POR FECHA*/
$("#cliente_por_fecha").on('click',function()
{
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
			alert(daticos);
			 $.ajax(
			 {
			 	data: daticos,
      			url:"../reporte/pdfventacliente.php",
      			type : 'GET',
			    success:  function (response)
			   	{
			        var a = document.createElement("a");
			        a.href ="../reporte/pdfventacliente.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"";
      				a.target = "_blank";
      				a.click();
      			}, 
				        error: function(response) 
				        {
				            alert("Me equivoqué"+ response);
				        }
			        });
			    };
});

$("#comprasfecha").on('click',function()
{
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
			var daticos = {"fecha_desde":fecha_desde,"fecha_hasta" : fecha_hasta};
			 $.ajax(
			 {
			 	data: daticos,
      			url:   '../controlador/reportegrafico/control_ajax_compraporfecha.php',
      			type : 'post',
			    success:  function (response)
			   	{
			        var respuesta = response.toString();
			        var nohay = 1;

			        if (nohay == respuesta)
			        {
			            alertify.alert("No hay ventas","No existen compras dentro de este periodo de fechas",function(){    });                  	
			        }
			        else
			        {
			        	alertify.alert("Correcto","Generando reporte",function()
			        	{
			        		var a = document.createElement("a");
					        a.href ="../reporte/pdfcompras.php?fecha_desde="+fecha_desde+"&fecha_hasta="+fecha_hasta+"";
		      				a.target = "_blank";
		      				a.click();
			        	});
			        }
      			}, 
				        error: function(response) 
				        {
				            alert("Me equivoqué"+ response);
				        }
			        });
			    };
});