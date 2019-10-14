<?php 

	require_once("../modelo/modelo-inventario_producto.php");
	$objinvent_produc= new inventario_producto();

	if (isset($_POST['Agregar'])) 
	{
		$objinvent_produc->set_inventario($_POST['codigo_inventario']);
		$objinvent_produc->set_codigo($_POST['codigo_producto']);

		if ($objinvent_produc->incluir()) 
		{
			echo "Producto Registrado Exitosamente";
		}
	}

	if (isset($_POST['Reactivar_Inventario'])) 
	{
		$objinvent_produc->set_inventario($_POST['codigo_inventario']);
		$objinvent_produc->set_codigo($_POST['codigo_producto']);

		if ($objinvent_produc->reactivar()) 
		{
			echo "Producto Registrado Exitosamente";
		}
	}
?>