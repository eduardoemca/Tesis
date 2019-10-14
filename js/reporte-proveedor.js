$(document).ready(function()
{
	load_tabla_proveedor(1);

	$("#btn-imprimir-clientes").on('click',function()
    {
       alertify.confirm("Confirmación","Desea generar el reporte?",function()
       {
       	$("#reporte_proveedor").click();
       },
       function()
       {
       	
       });
    });
});

function load_tabla_proveedor(page)
{
	var FiltroProveedor = $("#FiltroProveedor").val();
	var parametros = {"accion":"tabla","page":page,"FiltroProveedor":FiltroProveedor};
	//$("#loader").fadeIn('slow');
	$.ajax({
		url:'../listas/listar_proveedor_paginado.php',
		data: parametros,
		beforeSend: function(objeto)
		{
			//$("#loader").html("<img src='loader.gif'>");
		},
		success:function(data)
		{
			$(".proveedor_div").html(data).fadeIn('slow');
			//$("#loader").html("");
		}
	})
}