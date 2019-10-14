$(document).ready(function()
{
	$("#myInput1").on("keyup", function()
	{
		var value = $(this).val().toLowerCase();
    	$("#myTable tr").filter(function()
    	{
    		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    	});
    });

    load_bitacora(1);
   
});

function load_bitacora_detalle(page,session)
{	
	

	var parametros = {"action":"listar","page":page ,"session":session};

	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_detallebitacora_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".outer_divdetalle").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	})
}
function load_bitacora(page)
{
	var parametros = {"action":"ajax","page":page};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_bitacora_paginado.php',
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

  $( "#crearespaldo" ).submit(function( event ) {
  $('#actualizar_datos3').attr("disabled", true);
  
 var parametros = $(this).serialize();
   $.ajax({
      type: "POST",
      url: "../respaldo/index.php",
      data: parametros,
       beforeSend: function(objeto){
        $('.panel-body').hide();
        $("#cargando").show();
        $('.overlay').show();
     	
        
        },
      success: function(datos){
      $("#cargando").hide();
      $('.overlay').hide();
      $('.panel-body').show();
      $("#resultados_ajax3").html(datos);
      $('#actualizar_datos3').attr("disabled", false);
      //load(1);
      }
  });
  event.preventDefault();
})

$("#btn-cancelar").on('click',function(e)
{ 
	alertify.confirm('Cancelar','Desea cancelar el proceso?',
	function()
	{
		alertify.success('Ok');
		window.location.reload(true);
	},
	function()
	{
		alertify.error('Cancelado');
	});
});

