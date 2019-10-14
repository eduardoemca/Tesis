<?php 

	require_once("../modelo/modelo-inventario.php");

	class control_inventario
	{
		function __construct()
		{
			$this->inventario = new inventario();
		}

		function generar()
		{
			$datos=$this->inventario->codigo_inventario();	
			return $datos;
		}

		function consulta_inventario()
		{
			$datos=$this->inventario->consulta_inventario();
			return $datos;
		}

		function inventario()
		{
			$datos=$this->inventario->inventario();
			return $datos;
		}

		function consultar_inventario()
		{
			$datos=$this->inventario->consultar_inventario_master();
			return $datos;
		}
	}
	
	$objetoinventario = new inventario();

	if (isset($_POST['Agregar']))
	{
		$objetoinventario->set_codigo($_POST['codigo-inventario']);
		$objetoinventario->set_descripcion($_POST['descripcion-inventario']);

		if ($objetoinventario->incluir()) 
		{
			echo "Inventario Registrado exitosamente";
		}
	}

	if (isset($_POST['Consultar-tabla'])) 
	{
		$objetoinventario->set_codigo($_POST['codigo-tabla']);	
		$json=$objetoinventario->consultar_inventario();
		if ($json!=null) 
		{
			echo $json;
		}
	}

	if (isset($_POST['Guardar']))
	{
		$objetoinventario->set_codigo($_POST['codigo-inventario']);
		$objetoinventario->set_descripcion($_POST['descripcion-inventario']);

		if ($objetoinventario->modificar())
		{
			echo "Correcto al Actualizar";
		}
	}

	if (isset($_POST['Eliminar'])) 
	{
		$objetoinventario->set_codigo($_POST['codigo-inventario']);
		if ($objetoinventario->eliminar()) 
		{
			echo "Correcto al Eliminar";
		}
	}
?>