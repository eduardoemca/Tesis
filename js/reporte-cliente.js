$(document).ready(function()
{
	load_tabla_cliente(1);

    $("#btn-imprimir-clientes").on('click',function()
    {
       alertify.confirm("Confirmación","Desea generar el reporte?",function()
       {
       	$("#reporte_cliente").click();
       },
       function()
       {
       	
       });
    });
});

function load_tabla_cliente(page)
{
	var FiltroCliente = $("#FiltroCliente").val();
	var parametros = {"accion":"tabla","page":page,"FiltroCliente":FiltroCliente};
	//$("#loader").fadeIn('slow');
	$.ajax(
	{
		url:'../listas/listar_cliente_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".cliente_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}