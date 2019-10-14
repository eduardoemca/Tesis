<?php 

	require_once("../modelo/modelo-proveedor_producto.php");
	$objprove_produc= new proveedor_producto();

	if (isset($_POST['Agregar'])) 
	{
		$objprove_produc->set_proveedor($_POST['codigo_proveedor']);
		$objprove_produc->set_codigo($_POST['codigo_producto']);

		if ($objprove_produc->incluir()) 
		{
			echo "Producto Registrado Exitosamente";
		}
	}

	if (isset($_POST['Reactivar_Proveedor'])) 
	{
		$objprove_produc->set_proveedor($_POST['codigo_proveedor']);
		$objprove_produc->set_codigo($_POST['codigo_producto']);

		if ($objprove_produc->reactivar()) 
		{
			echo "Producto Registrado Exitosamente";
		}
	}
?>