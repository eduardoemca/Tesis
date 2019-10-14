<?php 

	include '../modelo/modelo-venta.php';

	class control_venta
	{
		function __construct()
		{
			$this->venta = new venta();
		}

		function generar()
		{
			$datos=$this->venta->codigo_venta();	
			return $datos;
		}
	}

	$objetoventa= new venta();

	if (isset($_POST['Agregar'])) 
	{
		$objetoventa->set_codigo_venta($_POST['codigo-venta']);
		$objetoventa->set_identificacion($_POST['identificacion-cliente']);

		if ($objetoventa->incluir()) 
		{
			echo "Venta Registrada exitosamente";
		}
	}

?>