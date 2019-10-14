$(document).ready(function()
{
	load_tabla_inventario(1);
});

function load_tabla_inventario(page)
{
	var FiltroInventario = $("#FiltroInventario").val();
	var parametros = {"accion":"tabla","page":page,"FiltroInventario":FiltroInventario};
	//$("#loader").fadeIn('slow');
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
			$(".inventario_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	});
}